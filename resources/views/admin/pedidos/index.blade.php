@extends('layouts.admin')

@section('breadcrumb')
    <span class="adm-breadcrumb-current">Pedidos</span>
@endsection

@section('content')

<div class="adm-page-header">
    <div class="adm-page-title">Pedidos</div>
    <div class="adm-page-sub">{{ $ventasHoy }} pedidos hoy</div>
</div>

{{-- Tarjetas resumen --}}
<div class="adm-stats" style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));">
    <div class="adm-stat">
        <div class="adm-stat-label">Pendientes</div>
        <div class="adm-stat-value" style="color: #f59e0b;">{{ $totales['pendientes'] }}</div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-label">Confirmados</div>
        <div class="adm-stat-value accent">{{ $totales['confirmados'] }}</div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-label">Entregados</div>
        <div class="adm-stat-value" style="color: #10b981;">{{ $totales['entregados'] }}</div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-label">Cancelados</div>
        <div class="adm-stat-value" style="color: #f43f5e;">{{ $totales['cancelados'] }}</div>
    </div>
</div>

{{-- Filtros --}}
<div style="display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem;">
    @foreach (['' => 'Todos', 'pendiente' => 'Pendiente', 'confirmado' => 'Confirmado', 'entregado' => 'Entregado', 'cancelado' => 'Cancelado'] as $val => $label)
        <a href="{{ route('admin.pedidos.index', $val ? ['estado' => $val] : []) }}"
           class="adm-pill {{ request('estado') == $val ? 'adm-pill-approved' : '' }}"
           style="{{ request('estado') != $val ? 'background: rgba(0,212,255,.05); color: var(--muted); border: 1px solid var(--border);' : '' }}">
            {{ $label }}
        </a>
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
                <td style="color: var(--muted); font-size: .78rem;">#{{ $pedido->id }}</td>
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
                        $pillClass = [
                            'pendiente'  => 'adm-pill-pending',
                            'confirmado' => 'adm-pill-approved',
                            'entregado'  => 'adm-pill-approved',
                            'cancelado'  => 'adm-pill-rejected',
                        ][$pedido->estado] ?? '';
                    @endphp
                    <span class="adm-pill {{ $pillClass }}">{{ ucfirst($pedido->estado) }}</span>
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

@endsection