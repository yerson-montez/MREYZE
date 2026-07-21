<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Boleta extends Model
{
    protected $table = 'boletas';

    protected $fillable = [
        'pedido_id',
        'user_id',
        'numero_boleta',
        'serie',
        'correlativo',
        'fecha_emision',
        'subtotal',
        'igv',
        'total',
        'estado',
        'cliente_nombre',
        'cliente_email',
        'cliente_documento',
        'cliente_tipo_documento',
        'tipo_comprobante',
        'ruc',
        'sunat_response',
        'xml_generado_at',
        'sunat_enviado_at',
        'sunat_baja_at',
        'sunat_cdr_path',
        'motivo_baja',
    ];

    protected $casts = [
        'fecha_emision'    => 'datetime',
        'subtotal'         => 'decimal:2',
        'igv'              => 'decimal:2',
        'total'            => 'decimal:2',
        'correlativo'      => 'integer',
        'sunat_response'   => 'array',
        'xml_generado_at'  => 'datetime',
        'sunat_enviado_at' => 'datetime',
        'sunat_baja_at'    => 'datetime',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(BoletaDetalle::class);
    }
}
