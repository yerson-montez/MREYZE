<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Proyecto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titulo', 'slug', 'descripcion_corta', 'descripcion_completa',
        'categoria', 'estado', 'tipo', 'imagen_principal', 'tecnologias',
        'cliente', 'fecha_inicio', 'fecha_fin', 'visible', 'orden',
        'inversion_monto_total', 'inversion_monto_disponible',
        'inversion_porcentaje_financiado', 'inversion_tiempo_estimado',
        'inversion_roi', 'inversion_retorno_final',
        'inversion_estado', 'inversion_etiqueta',
        'whatsapp_numero', 'whatsapp_mensaje', 'contrato_pdf',
    ];

    protected $casts = [
        'visible'                        => 'boolean',
        'inversion_monto_total'          => 'decimal:2',
        'inversion_monto_disponible'     => 'decimal:2',
        'inversion_porcentaje_financiado' => 'integer',
        'inversion_retorno_final'        => 'decimal:2',
        'fecha_inicio'                   => 'date',
        'fecha_fin'                      => 'date',
    ];

    public const INVERSION_ESTADOS = [
        'disponible'   => 'Disponible',
        'en_ejecucion' => 'En ejecución',
        'completado'   => 'Completado',
    ];

    protected static function booted(): void
    {
        static::creating(function (Proyecto $proyecto) {
            if (empty($proyecto->slug)) {
                $proyecto->slug = Str::slug($proyecto->titulo);
            }
        });

        static::updating(function (Proyecto $proyecto) {
            if (empty($proyecto->slug)) {
                $proyecto->slug = Str::slug($proyecto->titulo);
            }
        });
    }

    // ── Relaciones ─────────────────────────────────────────────

    public function imagenes(): HasMany
    {
        return $this->hasMany(ProyectoImagen::class)->orderBy('orden');
    }

    public function imagenPrincipal(): HasOne
    {
        return $this->hasOne(ProyectoImagen::class)->where('es_principal', true);
    }

    public function inversiones(): HasMany
    {
        return $this->hasMany(Inversion::class);
    }

    public function inversionesAprobadas(): HasMany
    {
        return $this->inversiones()->where('estado', 'aprobado');
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getImagenPrincipalUrlAttribute(): ?string
    {
        if ($this->imagen_principal) {
            return asset('storage/' . $this->imagen_principal);
        }

        $img = $this->imagenPrincipal()->first();
        if ($img) {
            return asset('storage/' . $img->ruta);
        }

        $primera = $this->imagenes()->first();
        if ($primera) {
            return asset('storage/' . $primera->ruta);
        }

        return null;
    }

    public function getRutaImagenPrincipalAttribute(): ?string
    {
        if ($this->imagen_principal) {
            return $this->imagen_principal;
        }

        $img = $this->imagenPrincipal()->first();
        return $img?->ruta ?? $this->imagenes()->first()?->ruta;
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeVisibles($query)
    {
        return $query->where('visible', true);
    }

    public function scopeCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePortafolio($query)
    {
        return $query->where('tipo', 'portafolio');
    }

    public function scopeInversion($query)
    {
        return $query->where('tipo', 'inversion');
    }

    // ── Helpers ────────────────────────────────────────────────

    public function getWhatsappLinkAttribute(): ?string
    {
        if (empty($this->whatsapp_numero)) {
            return null;
        }

        $mensaje = $this->whatsapp_mensaje
            ?: "Hola, estoy interesado en invertir en el proyecto: {$this->titulo}. Me gustaría recibir más información.";

        return 'https://api.whatsapp.com/send?phone=' . urlencode($this->whatsapp_numero) . '&text=' . urlencode($mensaje);
    }

    public function getContratoUrlAttribute(): ?string
    {
        return $this->contrato_pdf
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->contrato_pdf)
            : null;
    }

    public function getInversionRoiNumeroAttribute(): ?float
    {
        if (empty($this->inversion_roi)) {
            return null;
        }

        preg_match('/\d+(?:\.\d+)?/', $this->inversion_roi, $coincidencias);

        return $coincidencias ? (float) $coincidencias[0] : null;
    }

    public function getInversionRoiLabelAttribute(): ?string
    {
        $roi = $this->inversion_roi_numero;

        return $roi === null
            ? null
            : rtrim(rtrim(number_format($roi, 1, '.', ''), '0'), '.') . '%';
    }

    public function getInversionRetornoFinalNumeroAttribute(): ?float
    {
        if ($this->inversion_retorno_final === null || $this->inversion_retorno_final === '') {
            return null;
        }

        return (float) $this->inversion_retorno_final;
    }

    public function getInversionRetornoFinalLabelAttribute(): ?string
    {
        $retorno = $this->inversion_retorno_final_numero;

        return $retorno === null
            ? null
            : rtrim(rtrim(number_format($retorno, 1, '.', ''), '0'), '.') . '%';
    }

    public function getInversionEstadoLabelAttribute(): ?string
    {
        if (empty($this->inversion_estado)) {
            return null;
        }

        return self::INVERSION_ESTADOS[$this->inversion_estado]
            ?? ucfirst(str_replace('_', ' ', $this->inversion_estado));
    }
}
