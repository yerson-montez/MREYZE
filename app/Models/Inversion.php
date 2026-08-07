<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Inversion extends Model
{
    use HasFactory;

    protected $table = 'inversiones';

    protected $fillable = [
        'user_id',
        'proyecto_id',
        'nombre',
        'email',
        'telefono',
        'dni',
        'banco',
        'cuenta_bancaria',
        'cci_bancario',
        'titular_cuenta',
        'monto',
        'metodo_pago_id',
        'numero_operacion',
        'comprobante',
        'firma',
        'contrato_pdf',
        'estado',
        'notas_admin',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public const ESTADOS = ['pendiente', 'aprobado', 'rechazado', 'cancelado'];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function ultimoPago(): HasOne
    {
        return $this->hasOne(Pago::class)->latestOfMany();
    }

    public function getEstadoLabelAttribute(): string
    {
        return [
            'pendiente' => 'Inversión en revisión',
            'aprobado'  => 'Inversión aprobada',
            'rechazado' => 'Rechazada',
            'cancelado' => 'Cancelada',
        ][$this->estado] ?? ucfirst($this->estado);
    }

    public function getEstadoPagoLabelAttribute(): string
    {
        return match ($this->ultimoPago?->estado_pago) {
            'aprobado'  => 'Pago confirmado',
            'rechazado' => 'Pago rechazado',
            'pendiente' => 'Pago en revisión',
            default     => 'Sin registro de pago',
        };
    }

    public function getEstadoProyectoLabelAttribute(): string
    {
        return [
            'disponible'   => 'Aceptando inversiones',
            'en_ejecucion' => 'Proyecto en ejecución',
            'completado'   => 'Finalizado',
        ][$this->proyecto?->inversion_estado] ?? 'En preparación';
    }

    public function getParticipacionActivaAttribute(): bool
    {
        return $this->estado === 'aprobado'
            && $this->proyecto?->inversion_estado === 'en_ejecucion';
    }

    public function getComprobanteUrlAttribute(): ?string
    {
        return $this->comprobante ? Storage::disk('public')->url($this->comprobante) : null;
    }

    public function getContratoUrlAttribute(): ?string
    {
        return $this->contrato_pdf ? Storage::disk('public')->url($this->contrato_pdf) : null;
    }
}
