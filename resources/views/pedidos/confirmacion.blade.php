@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto mt-10 px-4">
        <div class="bg-white rounded-2xl shadow-sm border p-8 text-center">

            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-1">¡Pedido confirmado!</h1>
            <p class="text-gray-500 text-sm mb-6">Nos comunicaremos contigo pronto para coordinar la entrega.</p>

            <div class="bg-gray-50 rounded-xl p-4 text-left space-y-2.5 text-sm mb-6">
                <div class="flex justify-between">
                    <span class="text-gray-400">Pedido #</span>
                    <span class="font-semibold text-gray-800">{{ $pedido->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Producto</span>
                    <span class="font-medium text-gray-800">{{ $pedido->producto->nombre }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Cliente</span>
                    <span class="font-medium text-gray-800">{{ $pedido->cliente_nombre }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Teléfono</span>
                    <span class="font-medium text-gray-800">{{ $pedido->cliente_telefono }}</span>
                </div>
                <hr class="border-gray-200">
                <div class="flex justify-between">
                    <span class="text-gray-400">Total</span>
                    <span class="font-semibold text-gray-800">S/ {{ number_format($pedido->total, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-green-600 font-medium">✓ Adelanto pagado</span>
                    <span class="font-bold text-green-700">S/ {{ number_format($pedido->adelanto, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-amber-600">Saldo contra entrega</span>
                    <span class="font-bold text-amber-700">S/ {{ number_format($pedido->saldo, 2) }}</span>
                </div>
            </div>

            <a href="{{ route('pedidos.mis') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-xl text-sm transition">
                Volver a la tienda
            </a>
        </div>
    </div>
@endsection
