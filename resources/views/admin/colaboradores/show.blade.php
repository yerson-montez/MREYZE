<x-admin-layout>
    <div class="max-w-3xl mx-auto p-6">

        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.colaboradores.index') }}"
                   class="text-sm text-gray-400 hover:text-gray-600">← Colaboradores</a>
                <h1 class="text-2xl font-semibold text-gray-900 mt-1">{{ $colaborador->nombre }}</h1>
            </div>
            <a href="{{ route('admin.colaboradores.edit', $colaborador) }}"
               class="bg-white border hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                Editar
            </a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white border rounded-xl p-4 text-center">
                <p class="text-xs text-gray-400 mb-1">Total pedidos</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_pedidos'] }}</p>
            </div>
            <div class="bg-white border rounded-xl p-4 text-center">
                <p class="text-xs text-gray-400 mb-1">Ventas entregadas</p>
                <p class="text-2xl font-bold text-green-700">S/ {{ number_format($stats['total_ventas'], 2) }}</p>
            </div>
            <div class="bg-white border rounded-xl p-4 text-center">
                <p class="text-xs text-gray-400 mb-1">Comisión acumulada</p>
                <p class="text-2xl font-bold text-purple-700">S/ {{ number_format($colaborador->comision_total_acumulada, 2) }}</p>
            </div>
        </div>

        {{-- Info del colaborador --}}
        <div class="bg-white border rounded-xl p-5 mb-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Información</h2>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Código de referido</p>
                    <p class="font-mono font-medium text-gray-800">{{ $colaborador->codigo_ref }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Comisión</p>
                    <p class="font-medium text-gray-800">{{ $colaborador->comision_porcentaje }}%</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs">Enlace de referido</p>
                    <div class="flex items-center gap-2 mt-1">
                        <code class="text-xs bg-gray-50 border rounded px-2 py-1 flex-1 text-gray-700 truncate">
                            {{ $colaborador->link_ref }}
                        </code>
                        <button onclick="navigator.clipboard.writeText('{{ $colaborador->link_ref }}')"
                                class="text-xs text-blue-600 hover:text-blue-800 whitespace-nowrap border border-blue-200 px-2 py-1 rounded">
                            Copiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pedidos del colaborador --}}
        <div class="bg-white border rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b">
                <h2 class="font-semibold text-gray-800">Pedidos generados</h2>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-xs text-gray-400 uppercase tracking-wide">
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Cliente</th>
                        <th class="px-4 py-3 text-left">Producto</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">Comisión</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($pedidos as $pedido)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400 text-xs">#{{ $pedido->id }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $pedido->cliente_nombre }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $pedido->producto->nombre }}</td>
                        <td class="px-4 py-3 text-right font-medium">S/ {{ number_format($pedido->total, 2) }}</td>
                        <td class="px-4 py-3 text-right text-green-700 font-medium">
                            @if($pedido->comision_generada > 0)
                                S/ {{ number_format($pedido->comision_generada, 2) }}
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $badge = [
                                    'pendiente_pago'     => 'bg-yellow-100 text-yellow-700',
                                    'pendiente_aprobacion' => 'bg-blue-100 text-blue-700',
                                    'pagado_parcial'     => 'bg-indigo-100 text-indigo-700',
                                    'preparando'         => 'bg-purple-100 text-purple-700',
                                    'enviado'            => 'bg-cyan-100 text-cyan-700',
                                    'entregado'          => 'bg-green-100 text-green-700',
                                    'cancelado'          => 'bg-red-100 text-red-700',
                                ][$pedido->estado] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                {{ $pedido->estado_label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-300 text-sm">
                            Sin pedidos aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3">{{ $pedidos->links() }}</div>
        </div>

    </div>
</x-admin-layout>