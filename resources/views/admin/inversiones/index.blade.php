@extends('layouts.admin')

@section('title', 'Admin · Inversiones')

@push('styles')
<style>
.adm-inv-foto { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border); background: rgba(0,212,255,.05); }
.adm-inv-nombre { font-weight: 700; color: var(--text); }
.adm-inv-mail { font-size: .72rem; color: var(--muted); margin-top: 2px; }
.adm-inv-proyecto { font-weight: 600; color: var(--ice-200); max-width: 220px; }
.adm-inv-monto { font-weight: 700; color: var(--text); white-space: nowrap; }
</style>
@endpush

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Inicio</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">Inversiones</span>
@endsection

@section('content')

    @if(session('exito'))
    <div class="adm-alert adm-alert-success">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        {{ session('exito') }}
    </div>
    @endif

    @if($errors->any())
    <div class="adm-alert adm-alert-error">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        {{ $errors->first() }}
    </div>
    @endif

    <div class="adm-page-header">
        <div>
            <div class="adm-page-sub">Gestión · Inversiones</div>
            <h1 class="adm-page-title">Inversiones de Proyectos</h1>
        </div>
        <span class="adm-pill adm-pill-pending" style="font-size:.85rem;padding:6px 14px;">
            {{ $stats['pendiente'] }} pendiente(s)
        </span>
    </div>

    <div class="adm-stats">
        <div class="adm-card" style="text-align:center;">
            <div style="font-size:1.6rem;font-weight:800;color:var(--text);">{{ $stats['total'] }}</div>
            <div style="font-size:.75rem;color:var(--muted);margin-top:4px;">Total solicitudes</div>
        </div>
        <div class="adm-card" style="text-align:center;">
            <div style="font-size:1.6rem;font-weight:800;color:#f59e0b;">{{ $stats['pendiente'] }}</div>
            <div style="font-size:.75rem;color:var(--muted);margin-top:4px;">Pendientes</div>
        </div>
        <div class="adm-card" style="text-align:center;">
            <div style="font-size:1.6rem;font-weight:800;color:#10b981;">S/ {{ number_format($stats['aprobado'], 0, ',', ',') }}</div>
            <div style="font-size:.75rem;color:var(--muted);margin-top:4px;">Capital aprobado</div>
        </div>
        <div class="adm-card" style="text-align:center;">
            <div style="font-size:1.6rem;font-weight:800;color:#f43f5e;">{{ $stats['rechazado'] }}</div>
            <div style="font-size:.75rem;color:var(--muted);margin-top:4px;">Rechazadas</div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.inversiones.index') }}" style="display:flex; align-items:center; gap:.75rem; margin-bottom:1.25rem; flex-wrap:wrap;" id="searchForm">
        <div style="flex:1; min-width:220px; position:relative; display:flex; align-items:center;">
            <svg viewBox="0 0 24 24" fill="currentColor" style="position:absolute; left:.85rem; width:15px; height:15px; color:var(--muted); pointer-events:none;">
                <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, correo u operación..." style="padding-left:2.4rem;" class="adm-input" autocomplete="off">
        </div>
        <select name="estado" class="adm-select" style="width:auto;" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="pendiente" {{ request('estado')==='pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="aprobado"  {{ request('estado')==='aprobado'  ? 'selected' : '' }}>Aprobado</option>
            <option value="rechazado" {{ request('estado')==='rechazado' ? 'selected' : '' }}>Rechazado</option>
            <option value="cancelado" {{ request('estado')==='cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
        <select name="proyecto_id" class="adm-select" style="width:auto;" onchange="this.form.submit()">
            <option value="">Todos los proyectos</option>
            @foreach($proyectos as $proy)
                <option value="{{ $proy->id }}" {{ request('proyecto_id')==(string)$proy->id ? 'selected' : '' }}>{{ $proy->titulo }}</option>
            @endforeach
        </select>
        <button type="submit" class="adm-btn adm-btn-ghost">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
            Buscar
        </button>
        @if(request()->hasAny(['buscar','estado','proyecto_id']))
        <a href="{{ route('admin.inversiones.index') }}" class="adm-btn adm-btn-ghost">✕ Limpiar</a>
        @endif
    </form>

    <div class="adm-table-container">
        @if($inversiones->count())
        <table class="adm-table">
            <thead>
                <tr>
                    <th>Inversionista</th>
                    <th>Proyecto</th>
                    <th>Monto</th>
                    <th>Medio de pago</th>
                    <th>Estado</th>
                    <th>Pago</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($inversiones as $inv)
                @php
                    $pill = match($inv->estado) {
                        'pendiente' => 'adm-pill-pending',
                        'aprobado'  => 'adm-pill-approved',
                        'rechazado' => 'adm-pill-rejected',
                        'cancelado' => 'adm-pill-rejected',
                        default => '',
                    };
                @endphp
                <tr>
                    <td>
                        <div class="adm-inv-nombre">{{ $inv->nombre }}</div>
                        <div class="adm-inv-mail">{{ $inv->email }}</div>
                    </td>
                    <td>
                        <div class="adm-inv-proyecto">{{ $inv->proyecto->titulo }}</div>
                    </td>
                    <td><span class="adm-inv-monto">S/ {{ number_format($inv->monto, 2) }}</span></td>
                    <td>
                        {{ $inv->metodoPago?->emoji }} {{ $inv->metodoPago?->nombre ?? '—' }}
                        @if($inv->numero_operacion)
                            <div class="adm-inv-mail">N.º {{ $inv->numero_operacion }}</div>
                        @endif
                    </td>
                    <td><span class="adm-pill {{ $pill }}">{{ $inv->estado_label }}</span></td>
                    <td>
                        @if($inv->ultimoPago)
                            @php
                                $pagoPill = match($inv->ultimoPago->estado_pago) {
                                    'aprobado'  => 'adm-pill-approved',
                                    'rechazado' => 'adm-pill-rejected',
                                    default     => 'adm-pill-pending',
                                };
                            @endphp
                            <span class="adm-pill {{ $pagoPill }}">{{ $inv->estado_pago_label }}</span>
                        @else
                            <span style="font-size:.72rem;color:var(--muted);">Sin pago</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:var(--muted);">{{ $inv->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.inversiones.show', $inv) }}" class="adm-btn adm-btn-ghost" style="padding:.35rem .75rem; font-size:.72rem;">Revisar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if($inversiones->hasPages())
        <div class="adm-pagination">
            <span class="adm-pagination-info">
                Mostrando {{ $inversiones->firstItem() }}–{{ $inversiones->lastItem() }}
                de {{ $inversiones->total() }} inversiones
            </span>
            {{ $inversiones->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="adm-empty">
            <div class="adm-empty-icon">📈</div>
            @if(request()->hasAny(['buscar','estado','proyecto_id']))
                <p>Sin resultados para los filtros aplicados.</p>
                <a href="{{ route('admin.inversiones.index') }}" class="adm-btn adm-btn-ghost" style="margin-top:1.25rem">Limpiar filtros</a>
            @else
                <p>Aún no hay solicitudes de inversión.</p>
            @endif
        </div>
        @endif
    </div>

@endsection
