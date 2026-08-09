<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudTarjeta extends Model
{
    protected $table = 'solicitudes_tarjeta';

    protected $fillable = [
        // Legado (formulario básico)
        'nombre',
        'email',
        'telefono',
        'mensaje',
        'estado',

        // Datos del cliente
        'nombres',
        'apellidos',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'celular',
        'password',
        'estado_civil',
        'nombre_padre',
        'nombre_madre',
        'emergencia_parentesco',
        'emergencia_telefono',

        // Dirección
        'departamento',
        'provincia',
        'distrito',
        'direccion_calle',
        'direccion_numero',

        // Información adicional
        'ocupacion',
        'empresa',
        'ingresos_aproximados',
        'antiguedad_laboral',
        'direccion_laboral',
        'desea_seguro',
        'tipo_seguro',

        // Confirmación
        'acepta_terminos',
        'datos_correctos',
    ];

    protected $casts = [
        'fecha_nacimiento'   => 'date',
        'ingresos_aproximados' => 'decimal:2',
        'desea_seguro'       => 'boolean',
        'acepta_terminos'    => 'boolean',
        'datos_correctos'    => 'boolean',
        'password'           => 'hashed',
    ];

    public const ESTADOS = [
        'pendiente'   => 'Pendiente',
        'en_revision' => 'En revisión',
        'aprobada'    => 'Aprobada',
        'rechazada'   => 'Rechazada',
    ];

    public const TIPOS_DOCUMENTO = [
        'dni'        => 'DNI',
        'ce'         => 'Carné de Extranjería',
        'pasaporte'  => 'Pasaporte',
    ];

    public const ESTADOS_CIVILES = [
        'soltero'     => 'Soltero(a)',
        'casado'      => 'Casado(a)',
        'conviviente' => 'Conviviente',
        'divorciado'  => 'Divorciado(a)',
        'viudo'       => 'Viudo(a)',
    ];

    public const TIPOS_SEGURO = [
        'vida'       => 'Seguro de Vida',
        'salud'      => 'Seguro de Salud',
        'accidentes' => 'Seguro de Accidentes',
        'otro'       => 'Otro',
    ];

    public function nombreCompleto(): string
    {
        return trim(($this->nombres ?? '') . ' ' . ($this->apellidos ?? '')) ?: ($this->nombre ?? '—');
    }

    public function direccionCompleta(): string
    {
        $partes = array_filter([
            trim(($this->direccion_calle ?? '') . ' ' . ($this->direccion_numero ?? '')),
            $this->distrito,
            $this->provincia,
            $this->departamento,
        ]);

        return implode(', ', $partes) ?: '—';
    }

    public function estadoLabel(): string
    {
        return self::ESTADOS[$this->estado] ?? ucfirst($this->estado ?? 'pendiente');
    }

    public function tipoDocumentoLabel(): string
    {
        return self::TIPOS_DOCUMENTO[$this->tipo_documento] ?? ($this->tipo_documento ?? '—');
    }

    public function estadoCivilLabel(): string
    {
        return self::ESTADOS_CIVILES[$this->estado_civil] ?? ($this->estado_civil ?? '—');
    }

    public function tipoSeguroLabel(): string
    {
        return self::TIPOS_SEGURO[$this->tipo_seguro] ?? ($this->tipo_seguro ?? '—');
    }
}
