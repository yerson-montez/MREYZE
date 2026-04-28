<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'amount',
        'currency_code',
        'email',
        'status',
        'payment_id',
        'culqi_token',
        'culqi_response',
        'error_message',
    ];

    protected $casts = [
        'culqi_response' => 'array',
    ];

    /**
     * Retorna el monto formateado en soles.
     */
    public function getAmountFormattedAttribute(): string
    {
        return 'S/ ' . number_format($this->amount / 100, 2);
    }

    /**
     * Scopes de utilidad
     */
    public function scopePagado($query)
    {
        return $query->where('status', 'pagado');
    }

    public function scopeFallido($query)
    {
        return $query->where('status', 'fallido');
    }

    public function scopePendiente($query)
    {
        return $query->where('status', 'pendiente');
    }
}
