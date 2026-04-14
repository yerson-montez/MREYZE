<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'precio_anterior',
        'stock', 'categoria', 'estado', 'icono_emoji',
        'icono_color', 'slug', 'activo',
    ];

    protected $casts = [
        'precio'          => 'decimal:2',
        'precio_anterior' => 'decimal:2',
        'activo'          => 'boolean',
    ];

    // Auto-genera slug desde el nombre si no se provee
    protected static function booted(): void
    {
        static::creating(function (Producto $producto) {
            if (empty($producto->slug)) {
                $producto->slug = Str::slug($producto->nombre);
            }
        });
    }

    // Scope: solo activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope: por categoría
    public function scopeCategoria($query, string $cat)
    {
        return $query->where('categoria', $cat);
    }

    // Determina el estado automáticamente según el stock
    public function actualizarEstado(): void
    {
        if ($this->stock === 0) {
            $this->estado = 'agotado';
        } elseif ($this->wasRecentlyCreated) {
            $this->estado = 'nuevo';
        } else {
            $this->estado = 'disponible';
        }
    }

    // Helpers de presentación
    public function getPrecioFormateadoAttribute(): string
    {
        return 'S/ ' . number_format($this->precio, 2);
    }

    public function tieneDescuento(): bool
    {
        return !is_null($this->precio_anterior) && $this->precio_anterior > $this->precio;
    }

    public function getPorcentajeDescuentoAttribute(): ?int
    {
        if (!$this->tieneDescuento()) return null;
        return (int) round((1 - $this->precio / $this->precio_anterior) * 100);
    }
}
