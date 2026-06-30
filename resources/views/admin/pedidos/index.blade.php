@extends('layouts.admin')

@section('breadcrumb')
    <span class="adm-breadcrumb-current">Pedidos</span>
@endsection

@section('content')

<div class="adm-page-header" style="margin-bottom: 1rem;">
    <div>
        <div class="adm-page-title">Pedidos</div>
        <div class="adm-page-sub">{{ $ventasHoy }} pedidos hoy</div>
    </div>
    <div class="flex items-center gap-3 text-sm">
        <span class="text-muted font-medium">Estado:</span>
        <a href="{{ route('admin.pedidos.index') }}" class="adm-pill {{ !request('estado') ? 'adm-pill-approved' : '' }}"
           style="{{ request('estado') ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Todos</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'pendiente_pago']) }}" class="adm-pill {{ request('estado') === 'pendiente_pago' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'pendiente_pago' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Por pagar</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'pendiente_aprobacion']) }}" class="adm-pill {{ request('estado') === 'pendiente_aprobacion' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'pendiente_aprobacion' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Por aprobar</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'preparando']) }}" class="adm-pill {{ request('estado') === 'preparando' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'preparando' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Preparando</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'entregado']) }}" class="adm-pill {{ request('estado') === 'entregado' ? 'adm-pill-approved' : '' }}"
           style="{{ request('estado') !== 'entregado' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Entregados</a>
    </div>
</div>

{{-- Tarjetas resumen --}}
<div class="adm-stats" style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));">
    @foreach ([
        'pendiente_pago' => ['label' => 'Por pagar', 'color' => '#f59e0b'],
        'pendiente_aprobacion' => ['label' => 'Por aprobar', 'color' => '#f59e0b'],
        'pagado_parcial' => ['label' => 'Pag. parcial', 'color' => '#8b5cf6'],
        'preparando' => ['label' => 'Preparando', 'color' => '#3b82f6'],
        'enviado' => ['label' => 'Enviados', 'color' => '#3b82f6'],
        'entregado' => ['label' => 'Entregados', 'color' => '#10b981'],
        'cancelado' => ['label' => 'Cancelados', 'color' => '#f43f5e'],
    ] as $key => $info)
    <div class="adm-stat">
        <div class="adm-stat-label">{{ $info['label'] }}</div>
        <div class="adm-stat-value" style="color: {{ $info['color'] }};">{{ $totales[$key] }}</div>
    </div>
    @endforeach
</div>

{{-- Tabla --}}
<div class="adm-card" style="padding: 0; overflow: hidden;">
    <table class="adm-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Colaborador</th>
                <th style="text-align: right;">Total</th>
                <th style="text-align: center;">Estado</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $pedido)
            <tr>
                <td style="color: var(--muted); font-size: .78rem;">{{ $pedido->numero_pedido ?? '#' . $pedido->id }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $pedido->cliente_nombre }}</div>
                    <div style="font-size: .75rem; color: var(--muted);">{{ $pedido->cliente_telefono }}</div>
                </td>
                <td style="color: var(--muted); font-size: .82rem;">{{ $pedido->producto->nombre }}</td>
                <td>
                    @if($pedido->colaborador)
                        <span class="adm-pill" style="background: rgba(139,92,246,.12); color: #a78bfa; border: 1px solid rgba(139,92,246,.22);">
                            {{ $pedido->colaborador->nombre }}
                        </span>
                    @else
                        <span style="color: var(--border-md);">—</span>
                    @endif
                </td>
                <td style="text-align: right; font-weight: 700;">
                    S/ {{ number_format($pedido->total, 2) }}
                </td>
                <td style="text-align: center;">
                    @php
                        $pillClass = match($pedido->estado) {
                            'pendiente_pago' => 'adm-pill-pending',
                            'pendiente_aprobacion' => 'adm-pill-pending',
                            'pagado_parcial' => 'adm-pill-pending',
                            'preparando' => 'adm-pill-pending',
                            'enviado' => 'adm-pill-pending',
                            'entregado' => 'adm-pill-approved',
                            'cancelado' => 'adm-pill-rejected',
                            default => ''
                        };
                    @endphp
                    <span class="adm-pill {{ $pillClass }}">{{ $pedido->estado_label }}</span>
                </td>
                <td style="color: var(--muted); font-size: .78rem;">
                    {{ $pedido->created_at->format('d/m/Y') }}<br>
                    {{ $pedido->created_at->format('H:i') }}
                </td>
                <td>
                    <a href="{{ route('admin.pedidos.show', $pedido) }}" class="adm-btn adm-btn-ghost" style="padding: .35rem .75rem; font-size: .78rem;">
                        Ver →
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="adm-empty">
                        <div class="adm-empty-icon">📦</div>
                        <div class="adm-empty-title">No hay pedidos aún</div>
                        <div class="adm-empty-sub">Los pedidos aparecerán aquí cuando lleguen.</div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1.25rem;">
    {{ $pedidos->links() }}
</div>

<style>
    .flex { display: flex; }
    .items-center { align-items: center; }
    .gap-3 { gap: 0.75rem; }
    .text-sm { font-size: 0.875rem; }
    .font-medium { font-weight: 500; }
    .text-muted { color: var(--muted); }
    .mb-1 { margin-bottom: 0.25rem; }
</style>

@endsection
