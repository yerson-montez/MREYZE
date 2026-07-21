<?php

namespace App\Listeners;

use App\Enums\EstadoValidacionOcr;
use App\Events\PagoValidado;
use Illuminate\Support\Facades\Log;

class NotificarResultadoPago
{
    public function handle(PagoValidado $event): void
    {
        $pago = $event->pago;
        $resultado = $event->resultadoOcr;

        $mensajes = [
            EstadoValidacionOcr::Validado->value      => 'Tu pago ha sido confirmado automáticamente.',
            EstadoValidacionOcr::Sospechoso->value     => 'Tu pago está en revisión manual por el administrador.',
            EstadoValidacionOcr::ConfianzaBaja->value  => 'Tu pago está en revisión manual porque el comprobante no se leyó con claridad.',
            EstadoValidacionOcr::Rechazado->value      => 'Tu pago ha sido rechazado. Verifica los datos e intenta nuevamente.',
            EstadoValidacionOcr::OcrFallido->value     => 'Tu pago está en revisión manual porque no se pudo leer el comprobante.',
            EstadoValidacionOcr::ErrorOcr->value      => 'Tu pago está en revisión manual por un error temporal del sistema.',
            EstadoValidacionOcr::Procesando->value     => 'Tu pago está siendo verificado automáticamente.',
        ];

        $mensaje = $mensajes[$resultado->value] ?? 'Tu pago está siendo procesado.';

        Log::info("Notificacion Pago #{$pago->id}: {$mensaje}", [
            'pago_id'          => $pago->id,
            'resultado_ocr'    => $resultado->value,
            'estado_pago'      => $pago->estado_pago,
        ]);

        // TODO: Enviar notificación al usuario (email/SMS/WebSocket)
    }
}
