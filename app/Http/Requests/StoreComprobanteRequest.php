<?php

namespace App\Http\Requests;

use App\Rules\ComprobanteValido;
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
            'comprobante'      => ['nullable', 'file', new ComprobanteValido],
        ];
    }

    public function messages(): array
    {
        return [
            'comprobante.max'   => 'El comprobante no debe superar los 5 MB.',
        ];
    }
}
