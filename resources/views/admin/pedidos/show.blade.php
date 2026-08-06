<x-admin-layout>
    <div class="max-w-2xl mx-auto p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Pedido {{ $pedido->numero_pedido ?? '#' . $pedido->id }}</h1>
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
                <div>
                    <p class="text-gray-400 text-xs">Email</p>
                    <p class="font-medium text-gray-800">{{ $pedido->cliente_email }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Estado</p>
                    <p class="font-medium text-gray-800">{{ $pedido->estado_label }}</p>
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
                    <p class="text-green-500 text-xs mb-1">Adelanto</p>
                    <p class="font-bold text-lg text-green-700">S/ {{ number_format($pedido->adelanto, 2) }}</p>
                </div>
                <div class="bg-amber-50 rounded-lg p-3">
                    <p class="text-amber-500 text-xs mb-1">Contra entrega</p>
                    <p class="font-bold text-lg text-amber-700">S/ {{ number_format($pedido->saldo, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Pagos del pedido --}}
        @if ($pedido->pagos->count() > 0)
        <div class="bg-white rounded-xl border p-5 mb-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Pagos recibidos</h2>
            @foreach ($pedido->pagos as $pago)
            <div class="border rounded-lg p-3 mb-2 last:mb-0 text-sm">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-800">#{{ $pago->id }} — {{ ucfirst($pago->metodo_pago) }}</span>
                    <span class="font-semibold text-gray-800">S/ {{ number_format($pago->monto, 2) }}</span>
                </div>
                <div class="flex justify-between items-center mt-1">
                    <span class="text-xs text-gray-400">
                        @if ($pago->numero_operacion)
                        Nro: {{ $pago->numero_operacion }} ·
                        @endif
                        {{ $pago->created_at->format('d/m/Y H:i') }}
                    </span>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $pago->estado_pago === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $pago->estado_pago === 'aprobado' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $pago->estado_pago === 'rechazado' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst($pago->estado_pago) }}
                    </span>
                </div>
                @if ($pago->comprobante_pago)
                <div class="mt-1">
                    <a href="{{ asset('storage/' . $pago->comprobante_pago) }}" target="_blank"
                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">Ver comprobante →</a>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

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
                        <p class="text-xs text-gray-400 italic">Se calculará al completar</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Actualizar estado --}}
        <div class="bg-white rounded-xl border p-5 mb-4">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Actualizar estado</h2>
            <form action="{{ route('admin.pedidos.estado', $pedido) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="flex gap-3 items-start mb-3">
                    <select name="estado"
                        class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        @foreach (\App\Models\Pedido::ESTADOS as $estado => $estadoLabel)
                            <option value="{{ $estado }}" @selected($pedido->estado === $estado)>
                                {{ $estadoLabel }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap">
                        Guardar cambio
                    </button>
                </div>
                <textarea name="descripcion" rows="2" placeholder="Descripción del cambio (opcional)…"
                    class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
            </form>
            @if ($pedido->estado === 'completado' && $pedido->entregado_at)
            <p class="text-xs text-gray-400 mt-2">
                Completado el {{ $pedido->entregado_at->format('d/m/Y H:i') }}
            </p>
            @endif
        </div>

        {{-- Historial de estados --}}
        @php
            $historial = $pedido->estados()->latest()->get();
            $estadoLabels = [
                'pendiente' => 'Pendiente de pago',
                'pago_confirmado' => 'Pago confirmado',
                'preparando' => 'Preparando pedido',
                'enviado' => 'Enviado',
                'en_camino' => 'En camino',
                'listo_recojo' => 'Listo para recojo',
                'completado' => 'Completado',
                'cancelado' => 'Cancelado',
            ];
        @endphp
        @if ($historial->count() > 0)
        <div class="bg-white rounded-xl border p-5">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Historial de estados</h2>
            <div class="space-y-2">
                @foreach ($historial as $h)
                <div class="flex items-start gap-3 text-sm border-b border-gray-100 pb-2 last:border-0">
                    <div class="w-2 h-2 rounded-full mt-1.5 shrink-0
                        {{ $h->estado === 'cancelado' ? 'bg-red-500' : 'bg-blue-500' }}"></div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-800">{{ $estadoLabels[$h->estado] ?? ucfirst(str_replace('_', ' ', $h->estado)) }}</span>
                            <span class="text-xs text-gray-400">{{ $h->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($h->descripcion)
                        <p class="text-gray-500 text-xs mt-0.5">{{ $h->descripcion }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-admin-layout>
