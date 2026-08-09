@extends('layouts.admin')

@section('title', 'Admin · Solicitudes de Tarjeta MReyze')

@section('breadcrumb')
    <span class="adm-breadcrumb-current">Solicitudes de Tarjeta MReyze</span>
@endsection

@section('content')

{{-- Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Solicitudes de Tarjeta MReyze</h1>
        <p class="adm-page-sub">Revisa y gestiona las solicitudes de Tarjeta Débito enviadas desde el sitio.</p>
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
        <div class="adm-stat-value" style="font-size: 1.6rem; font-weight: 800; color: #f59e0b;">{{ $solicitudes->where('estado','pendiente')->count() }}</div>
    </div>
    <div class="adm-card" style="padding: 1rem; text-align: center;">
        <div class="adm-stat-label" style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">En revisión</div>
        <div class="adm-stat-value" style="font-size: 1.6rem; font-weight: 800; color: #38bdf8;">{{ $solicitudes->where('estado','en_revision')->count() }}</div>
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
            <div class="adm-empty-icon">💳</div>
            <div class="adm-empty-title" style="font-size:1.1rem;font-weight:700;color:var(--text);">Sin solicitudes</div>
            <p class="adm-empty-sub">Aún no hay solicitudes de Tarjeta Débito MReyze.</p>
        </div>

    @else

        <table class="adm-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Solicitante</th>
                    <th>Documento</th>
                    <th>Contacto</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $sol)
                <tr>
                    <td style="font-weight:700;color:var(--text);white-space:nowrap;">#{{ $sol->id }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--text);">{{ $sol->nombreCompleto() }}</div>
                        <div style="font-size:.78rem;color:var(--muted);">
                            {{ $sol->tipoDocumentoLabel() }} · {{ $sol->numero_documento }}
                        </div>
                    </td>
                    <td style="font-size:.82rem;color:var(--muted);">
                        <div>{{ $sol->fecha_nacimiento?->format('d/m/Y') }}</div>
                        <div style="font-size:.76rem;opacity:.7;">{{ $sol->estadoCivilLabel() }}</div>
                    </td>
                    <td>
                        <div style="font-size:.82rem;color:var(--muted);">{{ $sol->email }}</div>
                        <div style="font-size:.8rem;color:var(--muted);margin-top:.15rem;">{{ $sol->celular }}</div>
                    </td>
                    <td style="font-size:.8rem;color:var(--muted);">
                        {{ $sol->departamento }}<br>
                        <span style="opacity:.75;">{{ $sol->distrito }}</span>
                    </td>
                    <td>
                        @if($sol->estado === 'pendiente')
                            <span class="adm-pill adm-pill-pending">⏳ Pendiente</span>
                        @elseif($sol->estado === 'en_revision')
                            <span class="adm-pill" style="background:rgba(56,189,248,.1);border:1px solid rgba(56,189,248,.3);color:#38bdf8;">🔍 En revisión</span>
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
                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                            <a href="{{ route('admin.solicitudes-tarjeta.show', $sol) }}" class="adm-btn adm-btn-ghost" style="font-size:.78rem;padding:.4rem .85rem;">
                                Revisar
                            </a>
                            <form action="{{ route('admin.solicitudes-tarjeta.destroy', $sol) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta solicitud? Esta acción no se puede deshacer.');">
                                @csrf @method('DELETE')
                                <button type="submit" class="adm-btn adm-btn-danger" style="font-size:.78rem;padding:.4rem .85rem;">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    @endif

</div>

@endsection
