<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'monto',
        'metodo_pago_id',
        'numero_operacion',
        'comprobante',
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

    public function getEstadoLabelAttribute(): string
    {
        return [
            'pendiente' => 'Pendiente',
            'aprobado'  => 'Aprobado',
            'rechazado' => 'Rechazado',
            'cancelado' => 'Cancelado',
        ][$this->estado] ?? ucfirst($this->estado);
    }

    public function getComprobanteUrlAttribute(): ?string
    {
        return $this->comprobante ? Storage::disk('public')->url($this->comprobante) : null;
    }
}
