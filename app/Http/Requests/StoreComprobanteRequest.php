<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComprobanteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero_operacion' => 'nullable|string|max:100',
            'comprobante'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'comprobante.mimes' => 'El comprobante debe ser JPG, PNG o PDF.',
            'comprobante.max'   => 'El comprobante no debe superar los 5 MB.',
        ];
    }
}
