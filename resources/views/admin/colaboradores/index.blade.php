<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Colaboradores</h1>
            <a href="{{ route('admin.colaboradores.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Nuevo colaborador
            </a>
        </div>

        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-xs text-gray-400 uppercase tracking-wide">
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Código</th>
                        <th class="px-4 py-3 text-center">Comisión %</th>
                        <th class="px-4 py-3 text-right">Pedidos</th>
                        <th class="px-4 py-3 text-right">Ventas</th>
                        <th class="px-4 py-3 text-right">Comisión acum.</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($colaboradores as $col)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $col->nombre }}</td>
                        <td class="px-4 py-3 font-mono text-gray-400 text-xs">{{ $col->codigo_ref }}</td>
                        <td class="px-4 py-3 text-center text-gray-700">{{ $col->comision_porcentaje }}%</td>
                        <td class="px-4 py-3 text-right text-gray-700">{{ $col->pedidos_count }}</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-800">
                            S/ {{ number_format($col->pedidos_entregados_sum_total ?? 0, 2) }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-green-700">
                            S/ {{ number_format($col->comision_total_acumulada, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $col->activo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ $col->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.colaboradores.show', $col) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                Ver →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-300 text-sm">
                            No hay colaboradores registrados aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $colaboradores->links() }}</div>
    </div>
</x-admin-layout>