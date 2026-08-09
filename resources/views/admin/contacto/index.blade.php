@extends('layouts.admin')

@section('title', 'Admin · Mensajes de Contacto')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Panel Admin</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">Mensajes de Contacto</span>
@endsection

@section('content')

{{-- Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Mensajes de Contacto</h1>
        <p class="adm-page-sub">Solicitudes recibidas desde el formulario "Envíanos un mensaje" de la página de contacto.</p>
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
        <div class="adm-stat-label" style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">Nuevos</div>
        <div class="adm-stat-value" style="font-size: 1.6rem; font-weight: 800; color: var(--accent);">{{ $mensajes->where('estado','nuevo')->count() }}</div>
    </div>
    <div class="adm-card" style="padding: 1rem; text-align: center;">
        <div class="adm-stat-label" style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">Leídos</div>
        <div class="adm-stat-value" style="font-size: 1.6rem; font-weight: 800; color: var(--text);">{{ $mensajes->where('estado','leido')->count() }}</div>
    </div>
    <div class="adm-card" style="padding: 1rem; text-align: center;">
        <div class="adm-stat-label" style="font-size: .72rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: .35rem;">Total</div>
        <div class="adm-stat-value" style="font-size: 1.6rem; font-weight: 800; color: var(--text);">{{ $mensajes->total() }}</div>
    </div>
</div>

{{-- Tabla --}}
<div class="adm-table-container">

    @if($mensajes->isEmpty())

        <div class="adm-empty">
            <div class="adm-empty-icon">📭</div>
            <div class="adm-empty-title">Sin mensajes</div>
            <p class="adm-empty-sub">Aún no has recibido mensajes del formulario de contacto.</p>
        </div>

    @else

        <table class="adm-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Contacto</th>
                    <th>Servicio</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mensajes as $msg)
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--text);">
                            @if($msg->estado === 'nuevo')
                                <span style="color:var(--accent);">●</span>
                            @endif
                            {{ $msg->nombre }}
                        </div>
                        @if($msg->empresa)
                            <div style="font-size:.78rem;color:var(--muted);margin-top:.15rem;">{{ $msg->empresa }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:.82rem;color:var(--muted);">{{ $msg->email }}</div>
                        @if($msg->telefono)
                            <div style="font-size:.8rem;color:var(--muted);margin-top:.15rem;">{{ $msg->telefono }}</div>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:var(--muted);">{{ $msg->servicio }}</td>
                    <td style="max-width:240px;">
                        <span style="font-size:.8rem;color:var(--muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $msg->mensaje }}
                        </span>
                    </td>
                    <td>
                        @if($msg->estado === 'nuevo')
                            <span class="adm-pill adm-pill-pending">🟡 Nuevo</span>
                        @else
                            <span class="adm-pill adm-pill-approved">✓ Leído</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem;color:var(--muted);white-space:nowrap;">
                        {{ $msg->created_at->format('d M Y') }}<br>
                        <span style="opacity:.6;">{{ $msg->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                            <a href="{{ route('admin.contacto.show', $msg) }}" class="adm-btn adm-btn-ghost" style="font-size:.78rem;padding:.4rem .85rem;">
                                Ver
                            </a>
                            @if($msg->estado === 'nuevo')
                                <form action="{{ route('admin.contacto.leido', $msg) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="adm-btn adm-btn-success" style="font-size:.78rem;padding:.4rem .85rem;">
                                        Marcar leído
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.contacto.destroy', $msg) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?');">
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

@if($mensajes instanceof \Illuminate\Pagination\LengthAwarePaginator && $mensajes->hasPages())
    <div class="adm-pagination">
        <span class="adm-pagination-info">
            Mostrando {{ $mensajes->firstItem() }}–{{ $mensajes->lastItem() }}
            de {{ $mensajes->total() }} mensajes
        </span>
        {{ $mensajes->links() }}
    </div>
@endif

@endsection
