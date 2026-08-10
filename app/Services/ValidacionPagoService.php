<?php

namespace App\Services;

use App\DTOs\ResultadoOcr;
use App\Enums\EstadoValidacionOcr;
use App\Models\Inversion;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;

class ValidacionPagoService
{
    private const CONFIANZA_MINIMA = 0.5;
    private const CONFIANZA_ALTA   = 0.8;
    private const TOLERANCIA_MONTO = 0.01;

    public function validar(ResultadoOcr $ocr, ?Pedido $pedido, string $codigoIngresado, int $pagoId, ?float $montoEsperado = null, ?Inversion $inversion = null): EstadoValidacionOcr
    {
        $montoEsperado ??= $pedido !== null ? (float) $pedido->adelanto : null;

        if ($montoEsperado === null) {
            Log::warning('ValidacionPago: no hay monto esperado para validar', [
                'pago_id'   => $pagoId,
                'pedido_id' => $pedido?->id,
                'hash'      => $ocr->hashSha256,
            ]);
            return EstadoValidacionOcr::Sospechoso;
        }

        $contexto = [
            'pago_id'           => $pagoId,
            'pedido_id'         => $pedido?->id,
            'hash'              => $ocr->hashSha256,
            'confianza_global'  => $ocr->confianzaGlobal,
            'monto_ocr'         => $ocr->monto,
            'monto_esperado'    => $montoEsperado,
            'codigo_ocr'        => $ocr->codigoOperacion,
            'codigo_ingresado'  => $codigoIngresado,
        ];

        if ($this->esComprobanteDuplicado($ocr->hashSha256, $pagoId)) {
            Log::warning('ValidacionPago: comprobante duplicado detectado', $contexto);
            return EstadoValidacionOcr::Rechazado;
        }

        if ($ocr->confianzaGlobal < self::CONFIANZA_MINIMA) {
            Log::warning('ValidacionPago: confianza por debajo del umbral mínimo', $contexto);
            return EstadoValidacionOcr::ConfianzaBaja;
        }

        if ($ocr->monto === null) {
            Log::warning('ValidacionPago: no se pudo extraer el monto del comprobante', $contexto);
            return EstadoValidacionOcr::Sospechoso;
        }

        if ($inversion !== null) {
            // Inversión/Proyecto: coincidencia EXACTA al 100% en centavos, sin tolerancia.
            $montoOcrCents      = (int) round((float) $ocr->monto * 100);
            $montoEsperadoCents = (int) round((float) $montoEsperado * 100);

            if ($montoOcrCents !== $montoEsperadoCents) {
                Log::warning('ValidacionPago: el monto no coincide exactamente con la inversión', $contexto);
                return EstadoValidacionOcr::Rechazado;
            }
        } else {
            // Pedido/Tienda: lógica actual con tolerancia de S/ 0.01 (intocada).
            $diferencia = abs($ocr->monto - $montoEsperado);

            if ($diferencia > self::TOLERANCIA_MONTO) {
                Log::warning('ValidacionPago: el monto no coincide con el adelanto del pedido', $contexto);
                return EstadoValidacionOcr::Rechazado;
            }
        }

        $coincideCodigo = $ocr->codigoOperacion !== null
            && str_contains($ocr->codigoOperacion, $codigoIngresado);

        if ($coincideCodigo && $ocr->confianzaGlobal >= self::CONFIANZA_ALTA) {
            Log::info('ValidacionPago: pago validado (código + confianza alta)', $contexto);
            return EstadoValidacionOcr::Validado;
        }

        if ($coincideCodigo) {
            Log::info('ValidacionPago: pago validado (código coincide)', $contexto);
            return EstadoValidacionOcr::Validado;
        }

        if ($ocr->codigoOperacion === null) {
            if ($ocr->confianzaGlobal >= 0.7) {
                Log::info('ValidacionPago: pago validado por monto y confianza, sin código visible', $contexto);
                return EstadoValidacionOcr::Validado;
            }
            Log::warning('ValidacionPago: no se extrajo código de operación y confianza media', $contexto);
            return EstadoValidacionOcr::Sospechoso;
        }

        Log::warning('ValidacionPago: código no coincide con el ingresado por el usuario', $contexto);
        return EstadoValidacionOcr::Sospechoso;
    }

    private function esComprobanteDuplicado(string $hashSha256, int $pagoActualId): bool
    {
        return Pago::where('ocr_hash', $hashSha256)
            ->where('id', '!=', $pagoActualId)
            ->whereIn('ocr_resultado', ['validado', 'sospechoso'])
            ->exists();
    }
}
