<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProyectoInversion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proyectos_inversion';

    public const ESTADOS = [
        'disponible'   => 'Disponible para invertir',
        'en_ejecucion' => 'En ejecución',
        'completado'   => 'Proyecto completado',
    ];

    public const CATEGORIAS = [
        'energia'      => 'Energía',
        'agro'         => 'Agroindustria',
        'inmobiliario' => 'Inmobiliario',
        'tecnologia'   => 'Tecnología',
        'comercio'     => 'Comercio',
        'otro'         => 'Otro',
    ];

    protected $fillable = [
        'nombre', 'slug', 'categoria', 'descripcion_corta', 'descripcion_completa',
        'imagen_principal', 'inversion_total', 'inversion_disponible', 'tiempo_meses',
        'retorno_estimado', 'retorno_final', 'estado', 'fecha_inicio', 'fecha_fin',
        'visible', 'orden',
    ];

    protected $casts = [
        'inversion_total'      => 'decimal:2',
        'inversion_disponible' => 'decimal:2',
        'tiempo_meses'         => 'integer',
        'retorno_estimado'     => 'decimal:2',
        'retorno_final'        => 'decimal:2',
        'fecha_inicio'         => 'date',
        'fecha_fin'            => 'date',
        'visible'              => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProyectoInversion $proyecto) {
            if (empty($proyecto->slug)) {
                $proyecto->slug = static::slugUnico($proyecto->nombre);
            }
        });

        static::updating(function (ProyectoInversion $proyecto) {
            if (empty($proyecto->slug)) {
                $proyecto->slug = static::slugUnico($proyecto->nombre, $proyecto->id);
            }
        });
    }

    protected static function slugUnico(string $nombre, ?int $ignoreId = null): string
    {
        $base = Str::slug($nombre);
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeVisibles($query)
    {
        return $query->where('visible', true);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible');
    }

    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getImagenUrlAttribute(): ?string
    {
        return $this->imagen_principal
            ? Storage::disk('public')->url($this->imagen_principal)
            : null;
    }

    public function getEstadoLabelAttribute(): ?string
    {
        return empty($this->estado)
            ? null
            : (self::ESTADOS[$this->estado] ?? $this->estado);
    }

    public function getCategoriaLabelAttribute(): ?string
    {
        return self::CATEGORIAS[$this->categoria] ?? $this->categoria;
    }

    public function getRetornoEstimadoLabelAttribute(): ?string
    {
        return $this->retornoLabel((float) $this->retorno_estimado);
    }

    public function getRetornoFinalLabelAttribute(): ?string
    {
        if ($this->retorno_final === null || $this->retorno_final === '') {
            return null;
        }

        return $this->retornoLabel((float) $this->retorno_final);
    }

    public function getFinanciadoPorcentajeAttribute(): int
    {
        $total = (float) $this->inversion_total;

        if ($total <= 0) {
            return 0;
        }

        $disponible = (float) $this->inversion_disponible;
        $financiado = (int) round((1 - $disponible / $total) * 100);

        return max(0, min(100, $financiado));
    }

    protected function retornoLabel(float $valor): string
    {
        return rtrim(rtrim(number_format($valor, 1, '.', ''), '0'), '.') . '%';
    }
}
