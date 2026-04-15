<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // El middleware 'admin' ya protege la ruta
    }

    public function rules(): array
    {
        return [
            'nombre'          => ['required', 'string', 'max:120'],
            'descripcion'     => ['nullable', 'string', 'max:500'],
            'categoria'       => ['required', 'in:iot,software,kits'],
            'precio'          => ['required', 'numeric', 'min:0'],
            'precio_anterior' => ['nullable', 'numeric', 'min:0'],
            'estado'          => ['required', 'in:nuevo,disponible,agotado'],
            'icono_imagen'    => ['nullable', 'image', 'max:2048'],
            'activo'          => ['nullable', 'boolean'],
            'orden'           => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'    => 'El nombre del producto es obligatorio.',
            'categoria.required' => 'Debes seleccionar una categoría.',
            'precio.required'    => 'El precio es obligatorio.',
            'precio.numeric'     => 'El precio debe ser un número válido.',
            'imagen.image'       => 'El archivo debe ser una imagen (jpg, png, gif).',
            'imagen.max'         => 'La imagen no puede pesar más de 2MB.',
        ];
    }
}