<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensajeContacto extends Model
{
    protected $table = 'mensajes_contacto';

    protected $fillable = [
        'nombre',
        'empresa',
        'email',
        'telefono',
        'servicio',
        'mensaje',
        'estado',
    ];
}
