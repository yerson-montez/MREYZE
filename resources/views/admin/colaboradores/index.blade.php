<x-admin-layout>
    
    <div class="adm-page-header">
        <div>
            <h1 class="adm-page-title">Colaboradores</h1>
            <p class="adm-page-sub">Gestiona tu red de colaboradores y las comisiones acumuladas.</p>
        </div>
        <a href="{{ route('admin.colaboradores.create') }}"
           class="adm-btn adm-btn-primary">
            + Nuevo colaborador
        </a>
    </div>

    <div class="adm-table-container">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th style="text-align: center;">Comisión %</th>
                    <th style="text-align: right;">Pedidos</th>
                    <th style="text-align: right;">Ventas</th>
                    <th style="text-align: right;">Comisión acum.</th>
                    <th style="text-align: center;">Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($colaboradores as $col)
                <tr>
                    <td style="font-weight: 700; color: var(--text);">{{ $col->nombre }}</td>
                    <td style="font-family: monospace; font-size: .78rem; color: var(--muted);">{{ $col->codigo_ref }}</td>
                    <td style="text-align: center;">{{ $col->comision_porcentaje }}%</td>
                    <td style="text-align: right;">{{ $col->pedidos_count }}</td>
                    <td style="text-align: right; font-weight: 600;">
                        S/ {{ number_format($col->pedidos_entregados_sum_total ?? 0, 2) }}
                    </td>
                    <td style="text-align: right; font-weight: 800; color: #10b981;">
                        S/ {{ number_format($col->comision_total_acumulada, 2) }}
                    </td>
                    <td style="text-align: center;">
                        <span class="adm-pill {{ $col->activo ? 'adm-pill-approved' : 'adm-pill-rejected' }}">
                            {{ $col->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.colaboradores.show', $col) }}"
                           class="adm-btn adm-btn-ghost" style="padding: .35rem .75rem; font-size: .78rem;">
                            Ver →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="adm-empty">
                            <div class="adm-empty-icon">👥</div>
                            <div class="adm-empty-title">No hay colaboradores</div>
                            <div class="adm-empty-sub">No hay colaboradores registrados aún.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($colaboradores->hasPages())
    <div class="adm-pagination">
        <span class="adm-pagination-info">
            Mostrando {{ $colaboradores->firstItem() }}–{{ $colaboradores->lastItem() }}
            de {{ $colaboradores->total() }} colaboradores
        </span>
        {{ $colaboradores->links() }}
    </div>
    @endif

</x-admin-layout>