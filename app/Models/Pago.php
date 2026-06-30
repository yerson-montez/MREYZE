<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'pedido_id',
        'user_id',
        'numero_operacion',
        'comprobante_pago',
        'metodo_pago',
        'monto',
        'estado_pago',
        'observaciones_admin',
        'fecha_pago',
        'aprobado_por',
    ];

    protected $casts = [
        'monto'      => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado_pago', 'pendiente');
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado_pago', 'aprobado');
    }

    public function scopeRechazados($query)
    {
        return $query->where('estado_pago', 'rechazado');
    }

    public function getEstadoPagoColorAttribute(): string
    {
        return match ($this->estado_pago) {
            'pendiente'  => 'bg-amber-100 text-amber-700',
            'aprobado'   => 'bg-green-100 text-green-700',
            'rechazado'  => 'bg-red-100 text-red-700',
            default      => 'bg-gray-100 text-gray-700',
        };
    }
}
