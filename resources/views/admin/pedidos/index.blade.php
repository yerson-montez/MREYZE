<x-admin-layout>
    <div class="p-6">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Pedidos</h1>
            <span class="text-sm text-gray-400">{{ $ventasHoy }} pedidos hoy</span>
        </div>

        {{-- Tarjetas resumen --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @foreach ([
                'pendientes'  => ['color' => 'yellow', 'label' => 'Pendientes',  'icon' => '⏳'],
                'confirmados' => ['color' => 'blue',   'label' => 'Confirmados', 'icon' => '✅'],
                'entregados'  => ['color' => 'green',  'label' => 'Entregados',  'icon' => '📦'],
                'cancelados'  => ['color' => 'red',    'label' => 'Cancelados',  'icon' => '❌'],
            ] as $key => $info)
            <div class="bg-white rounded-xl border p-4">
                <p class="text-xs text-gray-400 mb-1">{{ $info['icon'] }} {{ $info['label'] }}</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totales[$key] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Filtros --}}
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach (['' => 'Todos', 'pendiente' => 'Pendiente', 'confirmado' => 'Confirmado', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado'] as $val => $label)
            <a href="{{ route('admin.pedidos.index', $val ? ['estado' => $val] : []) }}"
               class="px-3 py-1.5 rounded-full text-xs font-medium border transition
                      {{ request('estado') == $val ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-xs text-gray-400 uppercase tracking-wide">
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Cliente</th>
                        <th class="px-4 py-3 text-left">Producto</th>
                        <th class="px-4 py-3 text-left">Colaborador</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($pedidos as $pedido)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-400 text-xs">#{{ $pedido->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $pedido->cliente_nombre }}</p>
                            <p class="text-gray-400 text-xs">{{ $pedido->cliente_telefono }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-700 text-xs">{{ $pedido->producto->nombre }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            @if($pedido->colaborador)
                                <span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full text-xs">
                                    {{ $pedido->colaborador->nombre }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                            S/ {{ number_format($pedido->total, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $badge = [
                                    'pendiente'  => 'bg-yellow-100 text-yellow-700',
                                    'confirmado' => 'bg-blue-100 text-blue-700',
                                    'entregado'  => 'bg-green-100 text-green-700',
                                    'cancelado'  => 'bg-red-100 text-red-700',
                                ][$pedido->estado] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            {{ $pedido->created_at->format('d/m/Y') }}<br>
                            {{ $pedido->created_at->format('H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.pedidos.show', $pedido) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                Ver →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-300 text-sm">
                            No hay pedidos aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $pedidos->links() }}</div>
    </div>
</x-admin-layout>