<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ComprobanteValido implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('El archivo no es válido.');
            return;
        }

        if (! $value->isValid()) {
            $fail('El archivo está corrupto o no se pudo subir.');
            return;
        }

        $mime = $value->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

        if (! in_array($mime, $allowedMimes)) {
            $fail('El comprobante debe ser una imagen (JPG, PNG, WEBP) o PDF.');
            return;
        }

        if ($value->getSize() > 5120 * 1024) {
            $fail('El comprobante no debe superar los 5 MB.');
            return;
        }

        if (str_starts_with($mime, 'image/')) {
            [$width] = getimagesize($value->path());
            if ($width !== false && $width < 300) {
                $fail('La imagen es demasiado pequeña. Mínimo 300px de ancho.');
            }
        }
    }
}
