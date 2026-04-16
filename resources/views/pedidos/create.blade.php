{{-- Usa tu layout existente. Si tu layout es main.blade.php o app.blade.php cámbialo --}}
@extends('layouts.app')

@section('content')

    <div class="max-w-lg mx-auto mt-10 px-4 pb-16">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Realizar pedido</h1>
            <p class="text-gray-500 mt-1">Completa tus datos para confirmar la compra.</p>
        </div>

        {{-- Resumen del producto --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
            <p class="font-semibold text-gray-800 mb-3">{{ $producto->nombre }}</p>
            <div class="grid grid-cols-3 gap-3 text-sm text-center">
                <div class="bg-white border rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Total</p>
                    <p class="font-bold text-gray-800">S/ {{ number_format($producto->precio, 2) }}</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="text-green-600 text-xs mb-1">Adelanto (50%)</p>
                    <p class="font-bold text-green-700">S/ {{ number_format($adelanto, 2) }}</p>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <p class="text-amber-600 text-xs mb-1">Contra entrega</p>
                    <p class="font-bold text-amber-700">S/ {{ number_format($saldo, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Formulario --}}
        <form action="{{ route('pedidos.store', $producto) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre completo <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="cliente_nombre"
                    value="{{ old('cliente_nombre') }}"
                    placeholder="Ej: María López"
                    class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cliente_nombre') border-red-400 bg-red-50 @enderror"
                    required
                >
                @error('cliente_nombre')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Número de teléfono <span class="text-red-500">*</span>
                </label>
                <input
                    type="tel"
                    name="cliente_telefono"
                    value="{{ old('cliente_telefono') }}"
                    placeholder="Ej: 999 888 777"
                    class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cliente_telefono') border-red-400 bg-red-50 @enderror"
                    required
                >
                @error('cliente_telefono')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Dirección de entrega <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="cliente_direccion"
                    rows="3"
                    placeholder="Calle, número, urbanización, referencia..."
                    class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cliente_direccion') border-red-400 bg-red-50 @enderror"
                    required
                >{{ old('cliente_direccion') }}</textarea>
                @error('cliente_direccion')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 rounded-xl transition text-sm"
            >
                Confirmar pedido — Pagar adelanto S/ {{ number_format($adelanto, 2) }}
            </button>

            <p class="text-xs text-center text-gray-400">
                El saldo restante de S/ {{ number_format($saldo, 2) }} se paga al momento de la entrega.
            </p>
        </form>
    </div>
@endsection