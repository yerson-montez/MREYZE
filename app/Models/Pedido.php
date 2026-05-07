<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedido extends Model
{
    protected $fillable = [
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

    // ── Relaciones ─────────────────────────────────────────────────────
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

    // ── Negocio ────────────────────────────────────────────────────────

    /**
     * Calcula adelanto (50%) y saldo (50%) a partir del total.
     * Llamar ANTES de save() al crear el pedido.
     */
    public function calcularPagos(): void
    {
        $this->adelanto = round($this->total * 0.5, 2);
        $this->saldo    = $this->total - $this->adelanto; // evita error de centavos
    }

    /**
     * Procesa la comisión del colaborador al marcar como "entregado".
     * Idempotente: no hace nada si ya fue calculada.
     */
    public function procesarComision(): void
    {
        if ($this->estado !== 'entregado')  return;
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

    // ── Scopes ─────────────────────────────────────────────────────────
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado', 'entregado');
    }

    public function scopeConColaborador($query)
    {
        return $query->whereNotNull('colaborador_id');
    }
}