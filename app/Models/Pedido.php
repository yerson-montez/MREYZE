<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Pedido extends Model
{
    public const ESTADOS = [
        'pendiente'      => 'Pendiente de pago',
        'pago_confirmado' => 'Pago confirmado',
        'preparando'     => 'Preparando',
        'enviado'        => 'Enviado',
        'en_camino'      => 'En camino',
        'listo_recojo'   => 'Listo para recojo',
        'completado'     => 'Completado',
        'cancelado'      => 'Cancelado',
    ];

    protected $fillable = [
        'numero_pedido',
        'producto_id',
        'colaborador_id',
        'user_id',
        'cliente_nombre',
        'cliente_telefono',
        'cliente_email',
        'cliente_direccion',
        'total',
        'adelanto',
        'saldo',
        'estado',
        'comision_generada',
        'comision_pagada',
        'entregado_at',
    ];

    protected $casts = [
        'total'             => 'decimal:2',
        'adelanto'          => 'decimal:2',
        'saldo'             => 'decimal:2',
        'comision_generada' => 'decimal:2',
        'comision_pagada'   => 'boolean',
        'entregado_at'      => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Pedido $pedido) {
            if (empty($pedido->numero_pedido)) {
                $pedido->numero_pedido = 'PED-' . strtoupper(Str::random(8));
            }
        });
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function colaborador(): BelongsTo
    {
        return $this->belongsTo(Colaborador::class);
    }

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function ultimoPago()
    {
        return $this->hasOne(Pago::class)->latestOfMany();
    }

    public function boletas(): HasMany
    {
        return $this->hasMany(Boleta::class);
    }

    public function estados(): HasMany
    {
        return $this->hasMany(PedidoEstado::class)->latest('created_at');
    }

    public function calcularPagos(): void
    {
        $this->adelanto = round($this->total * 0.5, 2);
        $this->saldo    = $this->total - $this->adelanto;
    }

    public function getTotalPagadoAttribute(): float
    {
        if ($this->relationLoaded('pagos')) {
            return (float) $this->pagos->where('estado_pago', 'aprobado')->sum('monto');
        }

        return (float) $this->pagos()->where('estado_pago', 'aprobado')->sum('monto');
    }

    public function procesarComision(): void
    {
        if ($this->estado !== 'completado')  return;
        if ($this->comision_generada > 0)   return;
        if (! $this->colaborador_id)        return;

        $colaborador = $this->colaborador;
        $comision    = round($this->total * ($colaborador->comision_porcentaje / 100), 2);

        $this->update([
            'comision_generada' => $comision,
            'entregado_at'      => now(),
        ]);

        $colaborador->increment('comision_total_acumulada', $comision);
    }

    public function getEstadoColorAttribute(): string
    {
        return match ($this->estado) {
            'pendiente'       => 'bg-amber-100 text-amber-700',
            'pago_confirmado' => 'bg-indigo-100 text-indigo-700',
            'preparando'      => 'bg-purple-100 text-purple-700',
            'enviado'         => 'bg-cyan-100 text-cyan-700',
            'en_camino'       => 'bg-orange-100 text-orange-700',
            'listo_recojo'    => 'bg-blue-100 text-blue-700',
            'completado'      => 'bg-green-100 text-green-700',
            'cancelado'       => 'bg-red-100 text-red-700',
            default           => 'bg-gray-100 text-gray-700',
        };
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado) {
            'pendiente'       => 'Pendiente',
            'pago_confirmado' => 'Pago confirmado',
            'preparando'      => 'Preparando pedido',
            'enviado'         => 'Enviado',
            'en_camino'       => 'En camino',
            'listo_recojo'    => 'Listo para recojo',
            'completado'      => 'Completado',
            'cancelado'       => 'Cancelado',
            default           => $this->estado,
        };
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopeConColaborador($query)
    {
        return $query->whereNotNull('colaborador_id');
    }
}
