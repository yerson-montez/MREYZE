<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colaborador extends Model
{
    protected $table = 'colaboradores'; // ← ESTA ERA LA LÍNEA QUE FALTABA

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'codigo_ref',
        'comision_porcentaje',
        'comision_total_acumulada',
        'activo',
    ];

    protected $casts = [
        'activo'                   => 'boolean',
        'comision_porcentaje'      => 'decimal:2',
        'comision_total_acumulada' => 'decimal:2',
    ];

    // ── Relaciones ─────────────────────────────────────────────────────

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'colaborador_id');
    }

    public function pedidosEntregados(): HasMany
    {
        return $this->hasMany(Pedido::class, 'colaborador_id')
                    ->where('estado', 'entregado');
    }

    // ── Accessors ──────────────────────────────────────────────────────

    public function getLinkRefAttribute(): string
    {
        return url('/') . '?ref=' . $this->codigo_ref;
    }

    public function getTotalVentasAttribute(): float
    {
        return (float) $this->pedidosEntregados()->sum('total');
    }

    public function getComisionPendienteAttribute(): float
    {
        return (float) $this->pedidos()
            ->where('estado', 'entregado')
            ->where('comision_pagada', false)
            ->sum('comision_generada');
    }

    public function getTotalPedidosAttribute(): int
    {
        return $this->pedidos()->count();
    }
}