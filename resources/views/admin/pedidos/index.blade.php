@extends('layouts.admin')

@section('breadcrumb')
    <span class="adm-breadcrumb-current">Pedidos</span>
@endsection

@section('content')

@if(session('success'))
    <div class="adm-alert adm-alert-success">✓ {{ session('success') }}</div>
@endif

<div class="adm-page-header" style="margin-bottom: 1.5rem;">
    <div>
        <h1 class="adm-page-title">Pedidos</h1>
        <div class="adm-page-sub">{{ $ventasHoy }} pedidos hoy</div>
    </div>
    <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.875rem; flex-wrap: wrap;">
        <span style="color: var(--muted); font-weight: 500;">Estado:</span>
        <a href="{{ route('admin.pedidos.index') }}" class="adm-pill {{ !request('estado') ? 'adm-pill-approved' : '' }}"
           style="{{ request('estado') ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Todos</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'pendiente']) }}" class="adm-pill {{ request('estado') === 'pendiente' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'pendiente' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Pendiente pago</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'pago_confirmado']) }}" class="adm-pill {{ request('estado') === 'pago_confirmado' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'pago_confirmado' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Pago confirmado</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'preparando']) }}" class="adm-pill {{ request('estado') === 'preparando' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'preparando' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Preparando</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'enviado']) }}" class="adm-pill {{ request('estado') === 'enviado' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'enviado' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Enviado</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'en_camino']) }}" class="adm-pill {{ request('estado') === 'en_camino' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'en_camino' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">En camino</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'listo_recojo']) }}" class="adm-pill {{ request('estado') === 'listo_recojo' ? 'adm-pill-pending' : '' }}"
           style="{{ request('estado') !== 'listo_recojo' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Listo recojo</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'completado']) }}" class="adm-pill {{ request('estado') === 'completado' ? 'adm-pill-approved' : '' }}"
           style="{{ request('estado') !== 'completado' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Completados</a>
        <a href="{{ route('admin.pedidos.index', ['estado' => 'cancelado']) }}" class="adm-pill {{ request('estado') === 'cancelado' ? 'adm-pill-rejected' : '' }}"
           style="{{ request('estado') !== 'cancelado' ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">Cancelados</a>
    </div>
</div>

{{-- Tarjetas resumen --}}
<div class="adm-stats" style="grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));">
    @foreach ([
        'pendiente' => ['label' => 'Pendiente pago', 'color' => '#f59e0b'],
        'pago_confirmado' => ['label' => 'Pago confirmado', 'color' => '#a78bfa'],
        'preparando' => ['label' => 'Preparando', 'color' => '#00d4ff'],
        'enviado' => ['label' => 'Enviado', 'color' => '#3b82f6'],
        'en_camino' => ['label' => 'En camino', 'color' => '#fb923c'],
        'listo_recojo' => ['label' => 'Listo recojo', 'color' => '#22d3ee'],
        'completado' => ['label' => 'Completados', 'color' => '#10b981'],
        'cancelado' => ['label' => 'Cancelados', 'color' => '#f43f5e'],
    ] as $key => $info)
    <div class="adm-card" style="padding: 1rem; text-align: center;">
        <div style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">{{ $info['label'] }}</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: {{ $info['color'] }};">{{ $totales[$key] ?? 0 }}</div>
    </div>
    @endforeach
</div>

{{-- Tabla --}}
<div class="adm-table-container">
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
                <td style="color: var(--muted); font-size: .78rem; font-weight: 600;">{{ $pedido->numero_pedido ?? '#' . $pedido->id }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $pedido->cliente_nombre }}</div>
                    <div style="font-size: .75rem; color: var(--muted); margin-top: 0.15rem;">{{ $pedido->cliente_telefono }}</div>
                </td>
                <td style="color: var(--text); font-size: .82rem; font-weight: 500;">{{ $pedido->producto->nombre }}</td>
                <td>
                    @if($pedido->colaborador)
                        <span class="adm-pill" style="background: rgba(139,92,246,.12); color: #a78bfa; border: 1px solid rgba(139,92,246,.22);">
                            {{ $pedido->colaborador->nombre }}
                        </span>
                    @else
                        <span style="color: var(--border-md);">—</span>
                    @endif
                </td>
                <td style="text-align: right; font-weight: 700; color: var(--text);">
                    S/ {{ number_format($pedido->total, 2) }}
                </td>
                <td style="text-align: center;">
                    @php
$pillClass = match($pedido->estado) {
'pendiente' => 'adm-pill-pending',
'pago_confirmado' => 'adm-pill-pending',
'preparando' => 'adm-pill-pending',
'enviado' => 'adm-pill-pending',
'en_camino' => 'adm-pill-pending',
'listo_recojo' => 'adm-pill-pending',
'completado' => 'adm-pill-approved',
'cancelado' => 'adm-pill-rejected',
default => 'adm-pill-pending',
};
                    @endphp
                    <span class="adm-pill {{ $pillClass }}">{{ $pedido->estado_label }}</span>
                </td>
                <td style="color: var(--muted); font-size: .78rem;">
                    {{ $pedido->created_at->format('d/m/Y') }}<br>
                    <span style="opacity: .6;">{{ $pedido->created_at->format('H:i') }}</span>
                </td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.pedidos.show', $pedido) }}" class="adm-btn adm-btn-ghost" style="padding: .35rem .75rem; font-size: .78rem;">
                        Ver →
                    </a>
                    <form method="POST" action="{{ route('admin.pedidos.destroy', $pedido) }}"
                          onsubmit="return confirm('¿Eliminar este pedido? Esta acción no se puede deshacer.');"
                          style="display: inline-block; margin-left: .35rem;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="adm-btn adm-btn-danger" style="padding: .35rem .75rem; font-size: .78rem;">
                            Eliminar
                        </button>
                    </form>
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

@if($pedidos->hasPages())
<div class="adm-pagination">
    <span class="adm-pagination-info">
        Mostrando {{ $pedidos->firstItem() }}–{{ $pedidos->lastItem() }}
        de {{ $pedidos->total() }} pedidos
    </span>
    {{ $pedidos->links() }}
</div>
@endif

@endsection
