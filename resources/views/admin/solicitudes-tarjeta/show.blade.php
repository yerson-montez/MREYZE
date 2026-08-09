@extends('layouts.admin')

@section('title', 'Admin · Solicitud #' . $solicitud->id)

@section('breadcrumb')
    <a href="{{ route('admin.solicitudes-tarjeta.index') }}">Solicitudes de Tarjeta MReyze</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">#{{ $solicitud->id }}</span>
@endsection

@section('content')

{{-- Header --}}
<div class="adm-page-header">
    <div>
        <a href="{{ route('admin.solicitudes-tarjeta.index') }}" style="font-size:.82rem;color:var(--muted);text-decoration:none;">← Volver a solicitudes</a>
        <h1 class="adm-page-title" style="margin-top:.4rem;">
            Solicitud #{{ $solicitud->id }} · {{ $solicitud->nombreCompleto() }}
        </h1>
        <p class="adm-page-sub">Recibida el {{ $solicitud->created_at->format('d M Y') }} a las {{ $solicitud->created_at->format('H:i') }}</p>
    </div>
    <div>
        @if($solicitud->estado === 'pendiente')
            <span class="adm-pill adm-pill-pending" style="font-size:.8rem;padding:.4rem .9rem;">⏳ Pendiente</span>
        @elseif($solicitud->estado === 'en_revision')
            <span class="adm-pill" style="background:rgba(56,189,248,.1);border:1px solid rgba(56,189,248,.3);color:#38bdf8;font-size:.8rem;padding:.4rem .9rem;">🔍 En revisión</span>
        @elseif($solicitud->estado === 'aprobada')
            <span class="adm-pill adm-pill-approved" style="font-size:.8rem;padding:.4rem .9rem;">✓ Aprobada</span>
        @else
            <span class="adm-pill adm-pill-rejected" style="font-size:.8rem;padding:.4rem .9rem;">✗ Rechazada</span>
        @endif
    </div>
</div>

{{-- Alertas --}}
@if(session('success'))
    <div class="adm-alert adm-alert-success">✓ {{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="adm-alert adm-alert-error">✗ {{ $errors->first() }}</div>
@endif

{{-- Gestión de estado --}}
<div class="adm-card" style="margin-bottom:1.5rem;display:flex;flex-wrap:wrap;gap:1rem;align-items:center;justify-content:space-between;">
    <div>
        <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:.25rem;">Cambiar estado</div>
        <div style="font-size:.78rem;color:var(--muted);">
            Al aprobar se apertura la cuenta del cliente (si su correo no está registrado).
        </div>
    </div>
    <form action="{{ route('admin.solicitudes-tarjeta.estado', $solicitud) }}" method="POST" style="display:flex;gap:.6rem;flex-wrap:wrap;">
        @csrf @method('PATCH')
        <select name="estado" class="adm-select" style="min-width:150px;">
            @foreach(\App\Models\SolicitudTarjeta::ESTADOS as $k => $label)
                <option value="{{ $k }}" @selected($solicitud->estado === $k)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="adm-btn adm-btn-primary">Guardar estado</button>
    </form>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:1.2rem;margin-bottom:1.2rem;">

    {{-- Datos personales --}}
    <div class="adm-card">
        <h2 style="font-size:.82rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem;">Datos personales</h2>
        <div style="display:flex;flex-direction:column;gap:.7rem;">
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Nombre completo</div>
                <div style="font-size:.9rem;color:var(--text);font-weight:600;">{{ $solicitud->nombreCompleto() }}</div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;">
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Documento</div>
                    <div style="font-size:.85rem;color:var(--text);">{{ $solicitud->tipoDocumentoLabel() }} · {{ $solicitud->numero_documento }}</div>
                </div>
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Fecha de nacimiento</div>
                    <div style="font-size:.85rem;color:var(--text);">{{ $solicitud->fecha_nacimiento?->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Estado civil</div>
                    <div style="font-size:.85rem;color:var(--text);">{{ $solicitud->estadoCivilLabel() }}</div>
                </div>
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Edad</div>
                    <div style="font-size:.85rem;color:var(--text);">{{ $solicitud->fecha_nacimiento ? $solicitud->fecha_nacimiento->age . ' años' : '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contacto --}}
    <div class="adm-card">
        <h2 style="font-size:.82rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem;">Contacto</h2>
        <div style="display:flex;flex-direction:column;gap:.7rem;">
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Correo electrónico</div>
                <div style="font-size:.9rem;color:var(--text);">{{ $solicitud->email }}</div>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Celular</div>
                <div style="font-size:.9rem;color:var(--text);">{{ $solicitud->celular }}</div>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Dirección completa</div>
                <div style="font-size:.85rem;color:var(--text);">{{ $solicitud->direccionCompleta() }}</div>
            </div>
        </div>
    </div>

    {{-- Emergencia --}}
    <div class="adm-card">
        <h2 style="font-size:.82rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem;">Contacto de emergencia</h2>
        <div style="display:flex;flex-direction:column;gap:.7rem;">
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Parentesco</div>
                <div style="font-size:.9rem;color:var(--text);">{{ $solicitud->emergencia_parentesco }}</div>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Teléfono</div>
                <div style="font-size:.9rem;color:var(--text);">{{ $solicitud->emergencia_telefono }}</div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;">
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Nombre del padre</div>
                    <div style="font-size:.82rem;color:var(--text);">{{ $solicitud->nombre_padre }}</div>
                </div>
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Nombre de la madre</div>
                    <div style="font-size:.82rem;color:var(--text);">{{ $solicitud->nombre_madre }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:1.2rem;">

    {{-- Información laboral --}}
    <div class="adm-card">
        <h2 style="font-size:.82rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem;">Información laboral</h2>
        <div style="display:flex;flex-direction:column;gap:.7rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.6rem;">
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Ocupación</div>
                    <div style="font-size:.85rem;color:var(--text);">{{ $solicitud->ocupacion }}</div>
                </div>
                <div>
                    <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Antigüedad</div>
                    <div style="font-size:.85rem;color:var(--text);">{{ $solicitud->antiguedad_laboral }}</div>
                </div>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Empresa / Centro de trabajo</div>
                <div style="font-size:.9rem;color:var(--text);">{{ $solicitud->empresa }}</div>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Dirección laboral</div>
                <div style="font-size:.85rem;color:var(--text);">{{ $solicitud->direccion_laboral }}</div>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Ingresos aproximados</div>
                <div style="font-size:.95rem;color:var(--text);font-weight:700;">S/ {{ number_format($solicitud->ingresos_aproximados, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Seguro y confirmación --}}
    <div class="adm-card">
        <h2 style="font-size:.82rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem;">Seguro y confirmación</h2>
        <div style="display:flex;flex-direction:column;gap:.7rem;">
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">¿Desea seguro adicional?</div>
                <div style="font-size:.9rem;color:var(--text);">
                    @if($solicitud->desea_seguro)
                        <span style="color:#34d399;font-weight:600;">Sí</span> · {{ $solicitud->tipoSeguroLabel() }}
                    @else
                        No
                    @endif
                </div>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Aceptó términos y condiciones</div>
                <div style="font-size:.9rem;color:var(--text);">{{ $solicitud->acepta_terminos ? '✓ Sí' : '—' }}</div>
            </div>
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Confirmó datos correctos</div>
                <div style="font-size:.9rem;color:var(--text);">{{ $solicitud->datos_correctos ? '✓ Sí' : '—' }}</div>
            </div>
            @if($solicitud->telefono || $solicitud->mensaje)
            <div>
                <div style="font-size:.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;">Registro básico previo</div>
                <div style="font-size:.82rem;color:var(--muted);">
                    @if($solicitud->telefono)Teléfono: {{ $solicitud->telefono }}@endif
                    @if($solicitud->mensaje) · {{ $solicitud->mensaje }}@endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Acciones --}}
<div style="display:flex;gap:.7rem;flex-wrap:wrap;margin-top:1.5rem;">
    <form action="{{ route('admin.solicitudes-tarjeta.destroy', $solicitud) }}" method="POST"
          onsubmit="return confirm('¿Eliminar esta solicitud? Esta acción no se puede deshacer.');">
        @csrf @method('DELETE')
        <button type="submit" class="adm-btn adm-btn-danger">
            <span>🗑</span> Eliminar solicitud
        </button>
    </form>
</div>

@endsection
