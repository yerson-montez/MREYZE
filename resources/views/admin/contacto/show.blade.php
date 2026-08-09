@extends('layouts.admin')

@section('title', 'Admin · Mensaje de Contacto')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Panel Admin</a>
    <span class="adm-breadcrumb-sep">/</span>
    <a href="{{ route('admin.contacto.index') }}">Mensajes de Contacto</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">#{{ $mensaje->id }}</span>
@endsection

@section('content')

{{-- Header --}}
<div class="adm-page-header">
    <div>
        <h1 class="adm-page-title">Mensaje de {{ $mensaje->nombre }}</h1>
        <p class="adm-page-sub">Recibido el {{ $mensaje->created_at->format('d M Y') }} a las {{ $mensaje->created_at->format('H:i') }}.</p>
    </div>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
        @if($mensaje->estado === 'nuevo')
            <form action="{{ route('admin.contacto.leido', $mensaje) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="adm-btn adm-btn-success">✓ Marcar como leído</button>
            </form>
        @endif
        <form action="{{ route('admin.contacto.destroy', $mensaje) }}" method="POST" onsubmit="return confirm('¿Eliminar este mensaje?');">
            @csrf @method('DELETE')
            <button type="submit" class="adm-btn adm-btn-danger">Eliminar</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="adm-alert adm-alert-success">✓ {{ session('success') }}</div>
@endif

<div class="adm-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;margin-bottom:1.25rem;">
    {{-- Datos del cliente --}}
    <div class="adm-card">
        <h2 style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:1rem;">Datos del cliente</h2>
        <div style="display:flex;flex-direction:column;gap:.85rem;">
            <div>
                <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Nombre</div>
                <div style="font-size:.9rem;color:var(--text);font-weight:600;margin-top:.15rem;">{{ $mensaje->nombre }}</div>
            </div>
            @if($mensaje->empresa)
            <div>
                <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Empresa</div>
                <div style="font-size:.9rem;color:var(--text);margin-top:.15rem;">{{ $mensaje->empresa }}</div>
            </div>
            @endif
            <div>
                <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Correo</div>
                <div style="font-size:.9rem;color:var(--text);margin-top:.15rem;">
                    <a href="mailto:{{ $mensaje->email }}" style="color:var(--accent);text-decoration:none;">{{ $mensaje->email }}</a>
                </div>
            </div>
            @if($mensaje->telefono)
            <div>
                <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Teléfono</div>
                <div style="font-size:.9rem;color:var(--text);margin-top:.15rem;">{{ $mensaje->telefono }}</div>
            </div>
            @endif
            <div>
                <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Servicio de interés</div>
                <div style="font-size:.9rem;color:var(--text);margin-top:.15rem;">{{ $mensaje->servicio }}</div>
            </div>
            <div>
                <div style="font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Estado</div>
                <div style="margin-top:.35rem;">
                    @if($mensaje->estado === 'nuevo')
                        <span class="adm-pill adm-pill-pending">🟡 Nuevo</span>
                    @else
                        <span class="adm-pill adm-pill-approved">✓ Leído</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Mensaje --}}
    <div class="adm-card">
        <h2 style="font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:1rem;">Mensaje / Proyecto</h2>
        <div style="font-size:.9rem;color:var(--text);line-height:1.7;white-space:pre-wrap;background:rgba(7,26,46,.6);border:1px solid var(--border);border-radius:12px;padding:1.1rem;">
            {{ $mensaje->mensaje }}
        </div>
    </div>
</div>

<div>
    <a href="{{ route('admin.contacto.index') }}" class="adm-btn adm-btn-ghost">← Volver a mensajes</a>
</div>

@endsection
