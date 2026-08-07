@extends('layouts.admin')

@section('title', 'Admin · Inversión')

@push('styles')
<style>
.inv-det-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
.inv-det-card {
    background: var(--card); border: 1px solid var(--border);
    border-radius: 14px; padding: 1.4rem 1.5rem;
}
.inv-det-card h3 {
    font-size: .72rem; font-weight: 700; color: var(--muted);
    text-transform: uppercase; letter-spacing: .09em; margin-bottom: 1rem;
}
.inv-det-fila { display: flex; justify-content: space-between; gap: 1rem; padding: .45rem 0; border-bottom: 1px dashed rgba(0,212,255,.08); font-size: .85rem; }
.inv-det-fila:last-child { border-bottom: none; }
.inv-det-fila span:first-child { color: var(--muted); }
.inv-det-fila strong { color: var(--text); text-align: right; }
.inv-monto-grande { font-family: 'Exo 2', sans-serif; font-size: 2rem; font-weight: 800; color: var(--accent); margin-bottom: .2rem; }
.inv-proyecto-link { color: var(--accent); text-decoration: none; font-weight: 600; }
.inv-proyecto-link:hover { text-decoration: underline; }
.inv-bar-bg { height: 10px; background: rgba(7,26,46,.6); border-radius: 20px; overflow: hidden; border: 1px solid var(--border); margin: .9rem 0 .3rem; }
.inv-bar-fill { height: 100%; background: linear-gradient(90deg,#2563c7,#00d4ff); border-radius: 20px; }
.inv-acciones { display: flex; flex-wrap: wrap; gap: .6rem; margin-top: 1rem; }
.inv-comprobante { display: inline-flex; align-items: center; gap: .5rem; color: var(--text); text-decoration: none; padding: .5rem .9rem; border-radius: 10px; background: rgba(37,99,199,.15); border: 1px solid var(--border-md); font-size: .8rem; }
.inv-comprobante:hover { background: rgba(37,99,199,.3); color: #fff; }
.inv-notas textarea { width: 100%; background: rgba(7,26,46,.6); border: 1px solid var(--border-md); border-radius: 10px; padding: .8rem 1rem; color: var(--text); font-size: .85rem; font-family: inherit; outline: none; min-height: 90px; }
.inv-notas textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,212,255,.12); }
@media (max-width: 820px) { .inv-det-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}">Inicio</a>
    <span class="adm-breadcrumb-sep">/</span>
    <a href="{{ route('admin.inversiones.index') }}">Inversiones</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">#{{ $inversion->id }}</span>
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
            <div class="adm-page-sub">Solicitud · #{{ $inversion->id }}</div>
            <h1 class="adm-page-title">{{ $inversion->nombre }}</h1>
        </div>
        @php
            $pill = match($inversion->estado) {
                'pendiente' => 'adm-pill-pending',
                'aprobado'  => 'adm-pill-approved',
                'rechazado' => 'adm-pill-rejected',
                'cancelado' => 'adm-pill-rejected',
                default => '',
            };
        @endphp
        <span class="adm-pill {{ $pill }}" style="font-size:.85rem;padding:6px 14px;">{{ $inversion->estado_label }}</span>
    </div>

    <div class="inv-det-grid">
        <div class="inv-det-card">
            <h3>Inversión</h3>
            <div class="inv-monto-grande">S/ {{ number_format($inversion->monto, 2) }}</div>

            <div class="inv-det-fila"><span>Proyecto</span><strong>{{ $inversion->proyecto->titulo }}</strong></div>
            <div class="inv-det-fila"><span>Disponible tras validar</span><strong>S/ {{ number_format($inversion->proyecto->inversion_monto_disponible, 2) }}</strong></div>
            <div class="inv-det-fila"><span>Financiamiento actual</span><strong>{{ $inversion->proyecto->inversion_porcentaje_financiado }}%</strong></div>
            <div class="inv-det-fila"><span>ROI estimado</span><strong>~{{ $inversion->proyecto->inversion_roi }}%</strong></div>
            <div class="inv-det-fila"><span>Tiempo de ejecución</span><strong>{{ $inversion->proyecto->inversion_tiempo_estimado }} meses</strong></div>

            <div class="inv-bar-bg">
                <div class="inv-bar-fill" style="width:{{ $inversion->proyecto->inversion_porcentaje_financiado }}%"></div>
            </div>

            <a href="{{ route('admin.proyectos.edit', $inversion->proyecto) }}" class="inv-proyecto-link">→ Editar proyecto</a>
        </div>

        <div class="inv-det-card">
            <h3>Datos del inversionista</h3>
            <div class="inv-det-fila"><span>Nombre</span><strong>{{ $inversion->nombre }}</strong></div>
            <div class="inv-det-fila"><span>Correo</span><strong>{{ $inversion->email }}</strong></div>
            <div class="inv-det-fila"><span>Teléfono</span><strong>{{ $inversion->telefono ?? '—' }}</strong></div>
            <div class="inv-det-fila"><span>DNI / RUC</span><strong>{{ $inversion->dni ?? '—' }}</strong></div>
            <div class="inv-det-fila"><span>Cuenta</span><strong>{{ $inversion->user ? ($inversion->user->name . ' <' . $inversion->user->email . '>') : 'Invitado (sin cuenta)' }}</strong></div>
            <div class="inv-det-fila"><span>Medio de pago</span><strong>{{ $inversion->metodoPago?->emoji }} {{ $inversion->metodoPago?->nombre ?? '—' }}</strong></div>
            @if($inversion->numero_operacion)
            <div class="inv-det-fila"><span>N.º operación</span><strong>{{ $inversion->numero_operacion }}</strong></div>
            @endif
            <div class="inv-det-fila"><span>Solicitada</span><strong>{{ $inversion->created_at->format('d/m/Y H:i') }}</strong></div>

            @if($inversion->comprobante)
            <div style="margin-top:1.2rem;">
                <a href="{{ route('admin.inversiones.comprobante', $inversion) }}" class="inv-comprobante" target="_blank">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    Descargar comprobante
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="inv-det-grid">
        <div class="inv-det-card">
            <h3>Estado del pago</h3>
            @php $pagoInv = $inversion->ultimoPago; @endphp
            @if($pagoInv)
                <div class="inv-det-fila"><span>Estado del pago</span><strong>{{ $inversion->estado_pago_label }}</strong></div>
                <div class="inv-det-fila"><span>Método de pago</span><strong>{{ $pagoInv->metodo_pago }}</strong></div>
                @if($pagoInv->numero_operacion)
                <div class="inv-det-fila"><span>N.º operación</span><strong>{{ $pagoInv->numero_operacion }}</strong></div>
                @endif
                <div class="inv-det-fila"><span>Verificación automática</span><strong>
                    @if($pagoInv->ocr_resultado)
                        {{ \App\Enums\EstadoValidacionOcr::tryFrom($pagoInv->ocr_resultado)?->label() }}
                    @else
                        Sin comprobante adjunto
                    @endif
                </strong></div>
                @if($pagoInv->fecha_pago)
                <div class="inv-det-fila"><span>Confirmado el</span><strong>{{ $pagoInv->fecha_pago->format('d/m/Y H:i') }}</strong></div>
                @endif
                @if($pagoInv->observaciones_admin)
                <div class="inv-det-fila"><span>Observaciones</span><strong>{{ $pagoInv->observaciones_admin }}</strong></div>
                @endif
            @else
                <p style="font-size:.85rem;color:var(--muted);line-height:1.6;">No hay un registro de pago vinculado a esta solicitud.</p>
            @endif
        </div>

        <div class="inv-det-card">
            <h3>Contrato de inversión</h3>
            @if($inversion->estado === 'aprobado')
                <form method="POST" action="{{ route('admin.inversiones.contrato', $inversion) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="contrato_pdf" accept="application/pdf" required
                           style="width:100%;background:rgba(7,26,46,.6);border:1px solid var(--border-md);border-radius:10px;padding:.7rem;color:var(--text);font-size:.82rem;margin-bottom:.8rem;">
                    <button type="submit" class="adm-btn adm-btn-success" style="width:100%;">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                        Subir contrato PDF
                    </button>
                </form>
                @if($inversion->contrato_pdf)
                    <a href="{{ route('admin.inversiones.contrato.descargar', $inversion) }}" class="inv-comprobante" target="_blank" style="margin-top:.9rem;">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                        Descargar contrato actual
                    </a>
                @endif
            @else
                <p style="font-size:.85rem;color:var(--muted);line-height:1.6;">El contrato de inversión podrá subirse una vez que la inversión esté aprobada.</p>
            @endif
        </div>
    </div>

    <div class="inv-det-grid">
        <div class="inv-det-card inv-notas">
            <h3>Notas internas</h3>
            <form method="POST" action="{{ route('admin.inversiones.rechazar', $inversion) }}">
                @csrf
                <textarea name="notas_admin" placeholder="Motivo de rechazo o comentarios internos...">{{ old('notas_admin', $inversion->notas_admin) }}</textarea>
                <div class="inv-acciones">
                    <a href="{{ route('admin.inversiones.index') }}" class="adm-btn adm-btn-ghost">Volver</a>
                    @if($inversion->estado === 'pendiente')
                        <button type="submit" class="adm-btn adm-btn-danger">
                            <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                            Rechazar solicitud
                        </button>
                    @endif
                </div>
            </form>
        </div>

        @if(in_array($inversion->estado, ['pendiente', 'aprobado']))
        <div class="inv-det-card">
            <h3>Acciones</h3>
            @if($inversion->estado === 'pendiente')
            <form method="POST" action="{{ route('admin.inversiones.aprobar', $inversion) }}"
                  onsubmit="return confirm('¿Aprobar esta inversión por S/ {{ number_format($inversion->monto, 2) }}? Se descontará del disponible del proyecto automáticamente.');">
                @csrf
                <button type="submit" class="adm-btn adm-btn-success" style="width:100%;padding:.8rem;">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    Aprobar inversión
                </button>
            </form>
            <p style="font-size:.76rem;color:var(--muted);margin-top:.9rem;line-height:1.6;">
                Al aprobar, el monto se descontará del <strong style="color:var(--accent);">disponible del proyecto</strong> y se recalculará el porcentaje financiado automáticamente.
            </p>
            @else
            <div class="adm-alert adm-alert-success">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Inversión aprobada. Puedes rechazarla (con notas) para devolver el monto al disponible.
            </div>
            <form method="POST" action="{{ route('admin.inversiones.rechazar', $inversion) }}">
                @csrf
                <input type="hidden" name="notas_admin" value="Inversión cancelada por administración.">
                <button type="submit" class="adm-btn adm-btn-danger" style="width:100%;padding:.8rem;"
                        onclick="return confirm('¿Rechazar esta inversión aprobada? El monto se devolverá al disponible del proyecto.');">
                    Devolver monto y cancelar
                </button>
            </form>
            @endif
        </div>
        @endif
    </div>

@endsection
