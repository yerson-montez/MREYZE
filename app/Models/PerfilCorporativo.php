<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PerfilCorporativo extends Model
{
    protected $table = 'perfil_corporativo';

    protected $fillable = [
        'ruta',
        'nombre_original',
        'tipo_mime',
    ];

    public function url(): ?string
    {
        return $this->ruta ? Storage::url($this->ruta) : null;
    }

    public function esPdf(): bool
    {
        return $this->tipo_mime === 'application/pdf';
    }
}
