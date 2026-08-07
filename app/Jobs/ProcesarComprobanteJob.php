<?php

namespace App\Jobs;

use App\Enums\EstadoValidacionOcr;
use App\Events\PagoValidado;
use App\Models\Pago;
use App\Services\BoletaService;
use App\Services\ValidacionPagoService;
use App\Services\TesseractOcrService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcesarComprobanteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public int $backoff = 30;
    public int $maxExceptions = 1;

    public function __construct(
        public Pago $pago,
    ) {
        $this->onQueue('ocr');
    }

    public function handle(
        TesseractOcrService   $ocrService,
        ValidacionPagoService $validacionService,
        BoletaService         $boletaService,
    ): void {
        $pagoId = $this->pago->id;
        $pedido = $this->pago->pedido;
        $inversion = $this->pago->inversion;

        Log::info('Job.OCR: ANTES de procesar', [
            'pago_id'          => $pagoId,
            'pedido_id'        => $pedido?->id,
            'inversion_id'     => $inversion?->id,
            'pago.estado_pago' => $this->pago->estado_pago,
            'pedido.estado'    => $pedido?->estado,
        ]);

        $this->iniciarProcesamiento();

        if (! $this->pago->comprobante_pago) {
            Log::warning('Job.OCR: pago sin comprobante', ['pago_id' => $pagoId]);
            $this->marcarResultado(EstadoValidacionOcr::OcrFallido, 'No hay archivo de comprobante');
            return;
        }

        Log::info('Job.OCR: procesando comprobante', [
            'pago_id'          => $pagoId,
            'comprobante_path' => $this->pago->comprobante_pago,
            'intento'          => $this->attempts(),
        ]);

        try {
            $resultado = $ocrService->extraerTexto($this->pago->comprobante_pago);
        } catch (\Throwable $e) {
            Log::error('Job.OCR: excepción capturada en handle()', [
                'pago_id' => $pagoId,
                'error'   => $e->getMessage(),
                'class'   => get_class($e),
            ]);
            $this->marcarResultado(EstadoValidacionOcr::ErrorOcr, $e->getMessage());
            return;
        }

        if ($resultado === null) {
            $this->marcarResultado(EstadoValidacionOcr::OcrFallido, 'El servicio OCR no pudo extraer texto del comprobante');
            return;
        }

        $codigoIngresado = $this->pago->numero_operacion ?? '';

        $montoEsperado = $pedido !== null
            ? (float) $pedido->adelanto
            : (float) $inversion->monto;

        $estadoValidacion = $validacionService->validar(
            $resultado,
            $pedido,
            $codigoIngresado,
            $pagoId,
            $montoEsperado,
        );

        $this->pago->update([
            'ocr_resultado'     => $estadoValidacion->value,
            'ocr_datos'         => $resultado->toArray(),
            'ocr_confianza'     => $resultado->confianzaGlobal,
            'ocr_hash'          => $resultado->hashSha256,
            'ocr_procesado_at'  => now(),
            'ocr_intentos'      => $this->attempts(),
        ]);

        if ($estadoValidacion === EstadoValidacionOcr::Validado) {
            $this->pago->update(['estado_pago' => 'aprobado']);

            if ($pedido) {
                $pedido->update(['estado' => 'pago_confirmado']);
                $boletaService->generar($pedido, $this->pago);
                $this->limpiarComprobante();
            }

            Log::info('Job.OCR: pago aprobado automáticamente', [
                'pago_id'      => $pagoId,
                'pedido_id'    => $pedido?->id,
                'inversion_id' => $inversion?->id,
                'confianza'    => $resultado->confianzaGlobal,
                'hash'         => $resultado->hashSha256,
            ]);
        } else {
            $estadoPagoOcr = $estadoValidacion === EstadoValidacionOcr::Rechazado
                ? 'rechazado'
                : 'pendiente';

            $this->pago->update(['estado_pago' => $estadoPagoOcr]);
            if ($pedido) {
                $pedido->update(['estado' => 'pendiente']);
            }

            Log::info('Job.OCR: pago NO validado — pendiente de revisión manual', [
                'pago_id'        => $pagoId,
                'pedido_id'      => $pedido?->id,
                'inversion_id'   => $inversion?->id,
                'estado_ocr'     => $estadoValidacion->value,
                'estado_pago'    => $estadoPagoOcr,
                'confianza'      => $resultado->confianzaGlobal,
            ]);
        }

        $this->pago->refresh();
        if ($pedido) {
            $pedido->refresh();
        }

        Log::info('Job.OCR: DESPUÉS de procesar', [
            'pago_id'            => $pagoId,
            'pedido_id'          => $pedido?->id,
            'pago.estado_pago'   => $this->pago->estado_pago,
            'pago.ocr_resultado' => $this->pago->ocr_resultado,
            'pedido.estado'      => $pedido?->estado,
        ]);

        PagoValidado::dispatch($this->pago, $estadoValidacion);
    }

    public function failed(\Throwable $e): void
    {
        $pagoId = $this->pago?->id ?? '(no disponible)';

        Log::error('Job.OCR: fallo crítico después de reintentos', [
            'pago_id' => $pagoId,
            'error'   => $e->getMessage(),
            'class'   => get_class($e),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        try {
            if ($this->pago && $this->pago->exists) {
                $this->marcarResultado(EstadoValidacionOcr::ErrorOcr, $e->getMessage());
            }
        } catch (\Throwable $fallbackError) {
            Log::error('Job.OCR: no se pudo actualizar el pago en failed()', [
                'pago_id' => $pagoId,
                'error'   => $fallbackError->getMessage(),
            ]);
        }
    }

    public function retryUntil(): \DateTime
    {
        return now()->addMinutes(15);
    }

    private function iniciarProcesamiento(): void
    {
        $this->pago->update([
            'ocr_resultado'    => EstadoValidacionOcr::Procesando->value,
            'ocr_procesado_at' => now(),
            'ocr_intentos'     => $this->attempts(),
        ]);
    }

    private function marcarResultado(EstadoValidacionOcr $estado, string $motivo): void
    {
        $pagoId = $this->pago->id;
        $pedido = $this->pago->pedido;

        Log::info('Job.OCR: marcarResultado ANTES', [
            'pago_id'          => $pagoId,
            'pedido_id'        => $pedido?->id,
            'pago.estado_pago' => $this->pago->estado_pago,
            'pedido.estado'    => $pedido?->estado,
        ]);

        $this->pago->update([
            'ocr_resultado'    => $estado->value,
            'ocr_datos'        => $this->pago->ocr_datos ?? null,
            'ocr_procesado_at' => now(),
            'ocr_intentos'     => $this->attempts(),
        ]);

        $this->pago->update(['estado_pago' => 'pendiente']);
        if ($pedido) {
            $pedido->update(['estado' => 'pendiente']);
        }

        $this->pago->refresh();
        if ($pedido) {
            $pedido->refresh();
        }

        Log::info('Job.OCR: marcarResultado DESPUÉS', [
            'pago_id'            => $pagoId,
            'pedido_id'          => $pedido?->id,
            'pago.estado_pago'   => $this->pago->estado_pago,
            'pago.ocr_resultado' => $this->pago->ocr_resultado,
            'pedido.estado'      => $pedido?->estado,
            'estado_ocr'         => $estado->value,
        ]);

        PagoValidado::dispatch($this->pago, $estado);
    }

    private function limpiarComprobante(): void
    {
        if ($this->pago->comprobante_pago) {
            Storage::disk('public')->delete($this->pago->comprobante_pago);
            Log::info('Job.OCR: comprobante eliminido después de validación exitosa', [
                'pago_id' => $this->pago->id,
                'path'    => $this->pago->comprobante_pago,
            ]);
        }
    }
}
