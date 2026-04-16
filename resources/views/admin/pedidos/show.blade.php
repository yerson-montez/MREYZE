<x-admin-layout>
    <div class="max-w-2xl mx-auto p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Pedido #{{ $pedido->id }}</h1>
            <a href="{{ route('admin.pedidos.index') }}"
               class="text-sm text-gray-400 hover:text-gray-600">← Volver</a>
        </div>

        @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-5 text-sm">
            {{ session('success') }}
        </div>
        @endif

        {{-- Cliente --}}
        <div class="bg-white rounded-xl border p-5 mb-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Cliente</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Nombre</p>
                    <p class="font-medium text-gray-800">{{ $pedido->cliente_nombre }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Teléfono</p>
                    <p class="font-medium text-gray-800">{{ $pedido->cliente_telefono }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs">Dirección de entrega</p>
                    <p class="font-medium text-gray-800">{{ $pedido->cliente_direccion }}</p>
                </div>
            </div>
        </div>

        {{-- Producto y pagos --}}
        <div class="bg-white rounded-xl border p-5 mb-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Producto y pago</h2>
            <p class="font-semibold text-gray-800 mb-3">{{ $pedido->producto->nombre }}</p>
            <div class="grid grid-cols-3 gap-3 text-sm text-center">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-gray-400 text-xs mb-1">Total</p>
                    <p class="font-bold text-lg text-gray-800">S/ {{ number_format($pedido->total, 2) }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-3">
                    <p class="text-green-500 text-xs mb-1">Adelanto pagado</p>
                    <p class="font-bold text-lg text-green-700">S/ {{ number_format($pedido->adelanto, 2) }}</p>
                </div>
                <div class="bg-amber-50 rounded-lg p-3">
                    <p class="text-amber-500 text-xs mb-1">Contra entrega</p>
                    <p class="font-bold text-lg text-amber-700">S/ {{ number_format($pedido->saldo, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Colaborador --}}
        @if ($pedido->colaborador)
        <div class="bg-white rounded-xl border p-5 mb-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Colaborador</h2>
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-semibold text-gray-800">{{ $pedido->colaborador->nombre }}</p>
                    <p class="text-xs text-gray-400">{{ $pedido->colaborador->comision_porcentaje }}% de comisión</p>
                </div>
                <div class="text-right">
                    @if ($pedido->comision_generada > 0)
                        <p class="text-xs text-gray-400">Comisión generada</p>
                        <p class="font-bold text-green-700">S/ {{ number_format($pedido->comision_generada, 2) }}</p>
                        @if ($pedido->comision_pagada)
                            <span class="text-xs text-green-600 font-medium">✓ Pagada</span>
                        @else
                            <span class="text-xs text-amber-600 font-medium">Pendiente de pago</span>
                        @endif
                    @else
                        <p class="text-xs text-gray-400 italic">Se calculará al entregar</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Actualizar estado --}}
        <div class="bg-white rounded-xl border p-5">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Actualizar estado</h2>
            <form action="{{ route('admin.pedidos.estado', $pedido) }}" method="POST"
                  class="flex gap-3 items-center">
                @csrf
                @method('PATCH')
                <select name="estado"
                    class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    @foreach (['pendiente', 'confirmado', 'entregado', 'cancelado'] as $estado)
                        <option value="{{ $estado }}" @selected($pedido->estado === $estado)>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap">
                    Guardar cambio
                </button>
            </form>
            @if ($pedido->estado === 'entregado' && $pedido->entregado_at)
            <p class="text-xs text-gray-400 mt-2">
                Entregado el {{ $pedido->entregado_at->format('d/m/Y H:i') }}
            </p>
            @endif
        </div>

    </div>
</x-admin-layout>