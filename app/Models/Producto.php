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
        'codigo','nombre', 'descripcion', 'precio', 'precio_distribuidor', 'precio_anterior',
        'stock', 'categoria', 'estado', 'icono_imagen',
        'icono_color', 'slug', 'activo',
    ];

    protected $casts = [
        'precio'              => 'decimal:2',
        'precio_distribuidor' => 'decimal:2',
        'precio_anterior'     => 'decimal:2',
        'activo'              => 'boolean',
    ];

    /** Margen automático aplicado sobre el precio de distribuidor (20%). */
    public const MARGEN_VENTA = 0.20;

    protected static function booted(): void
{
    static::creating(function (Producto $producto) {

        if (empty($producto->slug)) {

            $base = Str::slug(($producto->codigo ?? uniqid()) . '-' . $producto->nombre);

            $slug = $base;
            $i = 1;

            while (Producto::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            $producto->slug = $slug;
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
        // Imagen subida a tu servidor
        if (str_starts_with($img->ruta, 'http')) {
            return $img->ruta;
        }

        return asset('storage/' . $img->ruta);
    }

    if ($this->icono_imagen) {

        // Imagen externa (Deltron)
        if (str_starts_with($this->icono_imagen, 'http')) {
            return $this->icono_imagen;
        }

        // Imagen local
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

    /** Precio de distribuidor efectivo (con fallback al precio de venta). */
    public function getPrecioDistribuidorEfectivoAttribute(): float
    {
        if (!is_null($this->precio_distribuidor)) {
            return (float) $this->precio_distribuidor;
        }
        return (float) $this->precio;
    }

    /** Precio de venta = precio de distribuidor × (1 + MARGEN_VENTA). */
    public static function calcularPrecioVenta(float $precioDistribuidor): float
    {
        return round($precioDistribuidor * (1 + self::MARGEN_VENTA), 2);
    }

    /** Porcentaje de margen aplicado (configurado automáticamente en el sistema). */
    public function getMargenAplicadoAttribute(): ?int
    {
        if ((float) $this->precio <= 0) {
            return null;
        }
        return (int) round(self::MARGEN_VENTA * 100);
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
