<?php

namespace App\Console\Commands;

use App\Enums\EstadoValidacionOcr;
use App\Models\Pago;
use Illuminate\Console\Command;

class PagoDebugCommand extends Command
{
    protected $signature = 'pago:debug {id : ID del pago}';
    protected $description = 'Diagnostica la sincronización entre Pago y Pedido';

    public function handle(): int
    {
        $id = (int) $this->argument('id');
        $pago = Pago::with('pedido')->find($id);

        if (! $pago) {
            $this->error("Pago #{$id} no encontrado.");
            return self::FAILURE;
        }

        $pedido = $pago->pedido;

        $this->box('PAGO', [
            "ID:                     {$pago->id}",
            "Pedido ID:              {$pago->pedido_id}",
            "estado_pago:            {$pago->estado_pago}",
            "ocr_resultado:          " . ($pago->ocr_resultado ?? 'NULL'),
            "ocr_label:              " . (EstadoValidacionOcr::tryFrom($pago->ocr_resultado ?? '')?->label() ?? '—'),
        ]);

        $this->box('PEDIDO', [
            "ID:                     {$pedido->id}",
            "numero_pedido:          " . ($pedido->numero_pedido ?? 'NULL'),
            "estado:                 {$pedido->estado}",
            "estado_label:           {$pedido->estado_label}",
        ]);

        $this->box('VISTA (show.blade.php)', [
            "Condición usada:        \$pedido->estado (línea 141, 159, 258)",
            "Badge Estado:           \$pedido->estado_label (línea 113)",
            "Badge Estado OCR:       \$pago->ocr_resultado (línea 135)",
        ]);

        $this->line(str_repeat('=', 50));

        $ocrEsFinal = $pago->ocr_resultado !== null
            && $pago->ocr_resultado !== 'pendiente'
            && $pago->ocr_resultado !== 'procesando';

        $pedidoEstaEnAprobacion = $pedido->estado === 'pendiente';
        $pedidoEsPagoPendiente = $pedido->estado === 'pendiente';

        if ($pago->ocr_resultado === 'validado') {
            $coinciden = $pedido->estado === 'pago_confirmado';
            $this->evaluar(
                'OCR=validado  →  pedido.estado=pago_confirmado',
                $coinciden,
                'ProcesarComprobanteJob::handle()',
                'Pago aprobado → pedido.estado=pago_confirmado'
            );
        } elseif ($ocrEsFinal) {
            $coinciden = $pedidoEsPagoPendiente;
            $this->evaluar(
                'OCR=' . $pago->ocr_resultado . '  →  pedido.estado=pendiente',
                $coinciden,
                'ProcesarComprobanteJob::handle() o marcarResultado()',
                match (true) {
                    $coinciden => 'OK — el Job actualizó pedido.estado a ' . $pedido->estado,
                    $pedidoEstaEnAprobacion => 'El Job debería haber cambiado pedido.estado a pendiente',
                    default => 'pedido.estado=' . $pedido->estado . ' — valor inesperado'
                }
            );
        } else {
            $this->warn('  [!] OCR aún no tiene resultado final (pendiente/procesando).');
        }

        $this->newLine();

        if ($ocrEsFinal && $pedidoEstaEnAprobacion) {
            $this->error('  *** INCONSISTENCIA DETECTADA ***');
            $this->line('  El OCR terminó (estado=' . $pago->ocr_resultado . ') pero pedido.estado');
            $this->line('  sigue siendo "pendiente". El usuario siempre verá');
            $this->line('  "Pendiente de pago" en el badge.');
            $this->line('');
            $this->line('  Causa raíz: ProcesarComprobanteJob no actualizaba pedido.estado');
            $this->line('  cuando el OCR fallaba o no se validaba.');
            return self::FAILURE;
        }

        if ($ocrEsFinal && $coinciden) {
            $this->info('  ✓ Pago y Pedido están sincronizados.');
            $this->line('  El badge mostrará: ' . $pedido->estado_label);
            $this->line('  El badge OCR mostrará: ' . (EstadoValidacionOcr::tryFrom($pago->ocr_resultado)?->label() ?? '—'));
            return self::SUCCESS;
        }

        $this->warn('  Estado no determinado — revisar manualmente.');
        return self::SUCCESS;
    }

    private function box(string $title, array $lines): void
    {
        $this->line(str_repeat('=', 50));
        $this->line("  {$title}");
        $this->line(str_repeat('=', 50));
        foreach ($lines as $line) {
            $this->line("  {$line}");
        }
        $this->newLine();
    }

    private function evaluar(string $descripcion, bool $coincide, string $archivo, string $detalle): void
    {
        $icono = $coincide ? '✓' : '✗';
        $color = $coincide ? 'info' : 'error';
        $this->$color("  {$icono} {$descripcion}");
        $this->line("     Archivo: {$archivo}");
        $this->line("     Detalle: {$detalle}");
        $this->newLine();
    }
}
