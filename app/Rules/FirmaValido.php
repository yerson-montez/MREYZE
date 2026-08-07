<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class FirmaValido implements ValidationRule
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
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

        if (! in_array($mime, $allowedMimes)) {
            $fail('La firma debe ser una imagen (JPG, JPEG, PNG o WEBP).');
            return;
        }

        if ($value->getSize() > 5120 * 1024) {
            $fail('La firma no debe superar los 5 MB.');
        }
    }
}
