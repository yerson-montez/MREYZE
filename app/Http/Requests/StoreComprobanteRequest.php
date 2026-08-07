<?php

namespace App\Http\Requests;

use App\Models\Pedido;
use App\Rules\ComprobanteValido;
use Illuminate\Foundation\Http\FormRequest;

class StoreComprobanteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        $pedido = $this->route('pedido');

        if (! $pedido instanceof Pedido) {
            return false;
        }

        return (int) $pedido->user_id === (int) $user->id;
    }

    public function rules(): array
    {
        return [
            'numero_operacion' => 'nullable|string|max:100',
            'metodo_pago'      => 'nullable|string|max:50',
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
