<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Colaborador extends Model
{
    protected $table = 'colaboradores';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'codigo_ref',
        'comision_porcentaje',
        'comision_total_acumulada',
        'activo',
        'estado',
        'mensaje_solicitud',
        'aprobado_at',
    ];

    protected $casts = [
        'activo'                   => 'boolean',
        'comision_porcentaje'      => 'decimal:2',
        'comision_total_acumulada' => 'decimal:2',
        'aprobado_at'              => 'datetime',
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

    // ── Scopes ─────────────────────────────────────────────────────────
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
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

    public function getEstadoBadgeAttribute(): array
    {
        return match($this->estado) {
            'activo'    => ['color' => '#10b981', 'bg' => 'rgba(16,185,129,.12)', 'label' => 'Activo'],
            'pendiente' => ['color' => '#f59e0b', 'bg' => 'rgba(245,158,11,.12)', 'label' => 'Pendiente'],
            'rechazado' => ['color' => '#f43f5e', 'bg' => 'rgba(244,63,94,.12)',  'label' => 'Rechazado'],
            'inactivo'  => ['color' => '#7a9ab8', 'bg' => 'rgba(122,154,184,.12)','label' => 'Inactivo'],
            default     => ['color' => '#7a9ab8', 'bg' => 'rgba(122,154,184,.12)','label' => 'Desconocido'],
        };
    }
}