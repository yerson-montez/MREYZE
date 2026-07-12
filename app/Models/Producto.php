<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'precio_anterior',
        'stock', 'categoria', 'estado', 'icono_imagen',
        'icono_color', 'slug', 'activo',
    ];

    protected $casts = [
        'precio'          => 'decimal:2',
        'precio_anterior' => 'decimal:2',
        'activo'          => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Producto $producto) {
            if (empty($producto->slug)) {
                $producto->slug = Str::slug($producto->nombre);
            }
        });
    }

    // ── Relaciones ─────────────────────────────────────────────

    public function imagenes(): HasMany
    {
        return $this->hasMany(ProductoImagen::class)->orderBy('orden');
    }

    public function imagenPrincipal(): HasOne
    {
        return $this->hasOne(ProductoImagen::class)->where('es_principal', true);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getImagenPrincipalUrlAttribute(): ?string
    {
        $img = $this->imagenPrincipal()->first();
        if ($img) {
            return asset('storage/' . $img->ruta);
        }
        if ($this->icono_imagen) {
            return asset('storage/' . $this->icono_imagen);
        }
        return null;
    }

    public function getRutaImagenPrincipalAttribute(): ?string
    {
        $img = $this->imagenPrincipal()->first();
        return $img?->ruta ?? $this->icono_imagen;
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeCategoria($query, string $cat)
    {
        return $query->where('categoria', $cat);
    }

    // ── Lógica ─────────────────────────────────────────────────

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

    // ── Helpers ────────────────────────────────────────────────

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
