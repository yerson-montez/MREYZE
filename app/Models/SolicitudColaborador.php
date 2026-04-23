<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SolicitudColaborador;

class SolicitudColaborador extends Model
{
    protected $table = 'solicitud_colaboradores';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'mensaje_solicitud',
        'estado'
    ];
}