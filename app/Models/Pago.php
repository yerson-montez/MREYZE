<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'pedido_id',
        'inversion_id',
        'user_id',
        'numero_operacion',
        'comprobante_pago',
        'metodo_pago',
        'monto',
        'estado_pago',
        'observaciones_admin',
        'fecha_pago',
        'aprobado_por',
        'ocr_resultado',
        'ocr_datos',
        'ocr_confianza',
        'ocr_procesado_at',
        'ocr_intentos',
        'ocr_hash',
    ];

    protected $casts = [
        'monto'            => 'decimal:2',
        'fecha_pago'       => 'datetime',
        'ocr_datos'        => 'array',
        'ocr_confianza'    => 'decimal:4',
        'ocr_procesado_at' => 'datetime',
        'ocr_intentos'     => 'integer',
        'ocr_hash'         => 'string',
    ];

    public function scopeOcrPendiente($query)
    {
        return $query->whereIn('ocr_resultado', ['pendiente', 'procesando']);
    }

    public function scopeOcrValidado($query)
    {
        return $query->where('ocr_resultado', 'validado');
    }

    public function scopeOcrFallido($query)
    {
        return $query->whereIn('ocr_resultado', ['ocr_fallido', 'error_ocr']);
    }

    public function scopeOcrSospechoso($query)
    {
        return $query->where('ocr_resultado', 'sospechoso');
    }

    public function getOcrResultadoColorAttribute(): string
    {
        return match ($this->ocr_resultado) {
            'validado'       => 'bg-green-100 text-green-700',
            'sospechoso'     => 'bg-amber-100 text-amber-700',
            'confianza_baja' => 'bg-yellow-100 text-yellow-700',
            'procesando'     => 'bg-blue-100 text-blue-700',
            'rechazado'      => 'bg-red-100 text-red-700',
            'ocr_fallido'    => 'bg-orange-100 text-orange-700',
            'error_ocr'      => 'bg-rose-100 text-rose-700',
            default          => 'bg-gray-100 text-gray-700',
        };
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function inversion(): BelongsTo
    {
        return $this->belongsTo(Inversion::class);
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
