<?php

namespace App\Events;

use App\Enums\EstadoValidacionOcr;
use App\Models\Pago;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PagoValidado
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Pago                $pago,
        public EstadoValidacionOcr $resultadoOcr,
    ) {}
}
