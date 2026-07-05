@extends('layouts.admin')

@section('title', 'Admin · Solicitudes')

@section('breadcrumb')
    <a href="{{ route('admin.colaboradores.index') }}">Colaboradores</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">Solicitudes Pendientes</span>
@endsection

@section('content')

{{-- Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Solicitudes de Colaboradores</h1>
        <p class="adm-page-sub">Gestiona las solicitudes de personas que quieren unirse como colaboradores.</p>
    </div>
</div>

{{-- Alertas --}}
@if(session('success'))
    <div class="adm-alert adm-alert-success">✓ {{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="adm-alert adm-alert-error">✗ {{ session('error') }}</div>
@endif

{{-- Stats --}}
<div class="adm-stats">
    <div class="adm-card" style="padding: 1rem; text-align: center;">
        <div class="adm-stat-label" style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">Pendientes</div>
        <div class="adm-stat-value accent" style="font-size: 1.6rem; font-weight: 800; color: var(--accent);">{{ $solicitudes->where('estado','pendiente')->count() }}</div>
    </div>
    <div class="adm-card" style="padding: 1rem; text-align: center;">
        <div class="adm-stat-label" style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">Aprobadas</div>
        <div class="adm-stat-value" style="font-size: 1.6rem; font-weight: 800; color: #10b981;">{{ $solicitudes->where('estado','aprobada')->count() }}</div>
    </div>
    <div class="adm-card" style="padding: 1rem; text-align: center;">
        <div class="adm-stat-label" style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">Rechazadas</div>
        <div class="adm-stat-value" style="font-size: 1.6rem; font-weight: 800; color: #f43f5e;">{{ $solicitudes->where('estado','rechazada')->count() }}</div>
    </div>
    <div class="adm-card" style="padding: 1rem; text-align: center;">
        <div class="adm-stat-label" style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">Total</div>
        <div class="adm-stat-value" style="font-size: 1.6rem; font-weight: 800; color: var(--text);">{{ $solicitudes->count() }}</div>
    </div>
</div>

{{-- Tabla --}}
<div class="adm-table-container">

    @if($solicitudes->isEmpty())

        <div class="adm-empty">
            <div class="adm-empty-icon">📭</div>
            <div class="adm-empty-title">Sin solicitudes</div>
            <p class="adm-empty-sub">No hay solicitudes de colaboradores por el momento.</p>
        </div>

    @else

        <table class="adm-table">
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Contacto</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $sol)
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--text);">{{ $sol->nombre }}</div>
                    </td>
                    <td>
                        <div style="font-size:.82rem;color:var(--muted);">{{ $sol->email }}</div>
                        <div style="font-size:.8rem;color:var(--muted);margin-top:.15rem;">{{ $sol->telefono }}</div>
                    </td>
                    <td style="max-width:220px;">
                        @if($sol->mensaje_solicitud)
                            <span style="font-size:.8rem;color:var(--muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $sol->mensaje_solicitud }}
                            </span>
                        @else
                            <span style="font-size:.78rem;color:var(--muted);opacity:.5;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($sol->estado === 'pendiente')
                            <span class="adm-pill adm-pill-pending">⏳ Pendiente</span>
                        @elseif($sol->estado === 'aprobada')
                            <span class="adm-pill adm-pill-approved">✓ Aprobada</span>
                        @else
                            <span class="adm-pill adm-pill-rejected">✗ Rechazada</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:var(--muted);white-space:nowrap;">
                        {{ $sol->created_at->format('d M Y') }}<br>
                        <span style="opacity:.6;">{{ $sol->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        @if($sol->estado === 'pendiente')
                            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                                <form action="{{ route('admin.colaboradores.solicitudes.aprobar', $sol) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="adm-btn adm-btn-ghost" style="font-size:.78rem;padding:.4rem .85rem;">
                                        ✓ Aprobar
                                    </button>
                                </form>
                                <form action="{{ route('admin.colaboradores.solicitudes.rechazar', $sol) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="adm-btn adm-btn-danger" style="font-size:.78rem;padding:.4rem .85rem;">
                                        ✗ Rechazar
                                    </button>
                                </form>
                            </div>
                        @else
                            <span style="font-size:.78rem;color:var(--muted);">Procesada</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @endif

</div>

@if($solicitudes instanceof \Illuminate\Pagination\LengthAwarePaginator && $solicitudes->hasPages())
    <div class="adm-pagination">
        <span class="adm-pagination-info">
            Mostrando {{ $solicitudes->firstItem() }}–{{ $solicitudes->lastItem() }}
            de {{ $solicitudes->total() }} solicitudes
        </span>
        {{ $solicitudes->links() }}
    </div>
@endif

@endsection