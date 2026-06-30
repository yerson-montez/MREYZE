<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AprobarPagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->esAdmin();
    }

    public function rules(): array
    {
        return [
            'accion'              => 'required|in:aprobar,rechazar',
            'observaciones_admin' => 'nullable|string|max:1000',
        ];
    }
}
