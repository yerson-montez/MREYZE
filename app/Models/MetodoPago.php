<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    protected $table = 'metodos_pago';

    protected $fillable = [
        'clave',
        'nombre',
        'emoji',
        'activo',
        'datos',
        'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'datos' => 'array',
    ];

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}