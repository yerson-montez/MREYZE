<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'categoria',
        'precio',
        'precio_anterior',
        'estado',
        'emoji',
        'imagen',
        'activo',
        'orden',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'precio_anterior' => 'decimal:2',
        'activo' => 'boolean',
    ];

    // Auto-generar slug desde nombre
    protected static function booted(): void
    {
        static::creating(function ($producto) {
            if (empty($producto->slug)) {
                $producto->slug = Str::slug($producto->nombre);
            }
        });

        static::updating(function ($producto) {
            if ($producto->isDirty('nombre') && !$producto->isDirty('slug')) {
                $producto->slug = Str::slug($producto->nombre);
            }
        });
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }

    public function scopeCategoria($query, string $cat)
    {
        return $query->where('categoria', $cat);
    }

    // Accessors
    public function getDescuentoPorcentajeAttribute(): ?int
    {
        if ($this->precio_anterior && $this->precio_anterior > $this->precio) {
            return (int) round((1 - $this->precio / $this->precio_anterior) * 100);
        }
        return null;
    }

    public function getImagenUrlAttribute(): string
    {
        if ($this->imagen) {
            return asset('storage/' . $this->imagen);
        }
        return '';
    }
}