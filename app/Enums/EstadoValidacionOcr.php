<?php

namespace App\Enums;

enum EstadoValidacionOcr: string
{
    case Pendiente     = 'pendiente';
    case Procesando    = 'procesando';
    case Validado      = 'validado';
    case Sospechoso    = 'sospechoso';
    case ConfianzaBaja = 'confianza_baja';
    case Rechazado     = 'rechazado';
    case OcrFallido    = 'ocr_fallido';
    case ErrorOcr      = 'error_ocr';

    public function label(): string
    {
        return match ($this) {
            self::Pendiente     => 'Pendiente',
            self::Procesando    => 'Procesando',
            self::Validado      => 'Validado',
            self::Sospechoso    => 'Sospechoso',
            self::ConfianzaBaja => 'Confianza baja',
            self::Rechazado     => 'Rechazado',
            self::OcrFallido    => 'OCR fallido',
            self::ErrorOcr      => 'Error en OCR',
        };
    }

    public function esFinal(): bool
    {
        return match ($this) {
            self::Validado, self::Rechazado, self::OcrFallido, self::ErrorOcr => true,
            default => false,
        };
    }

    public function esReintentable(): bool
    {
        return match ($this) {
            self::ErrorOcr, self::OcrFallido => true,
            default => false,
        };
    }
}
