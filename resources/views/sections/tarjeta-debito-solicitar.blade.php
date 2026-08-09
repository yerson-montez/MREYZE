@extends('welcome')

@section('title', 'Solicita tu Tarjeta Débito — MREYZE')
@section('meta_description', 'Registra tus datos y solicita tu Tarjeta Débito MReyze. Apertura de cuenta sin costo.')

@section('content')

<style>
    /* ════════════════════════════════════════════════════
       SOLICITUD DE TARJETA DÉBITO — estilos propios (prefijo tds-)
       No afecta otras secciones del sitio.
    ════════════════════════════════════════════════════ */

    /* ── Contenedor general ── */
    .tds-page { padding: 2.5rem 1.5rem 4.5rem; }

    /* ── Cabecera de la solicitud ── */
    .tds-header {
        max-width: 800px;
        margin: 0 auto 1.8rem;
    }
    .tds-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--ice-200);
        text-decoration: none;
        margin-bottom: 1.1rem;
        transition: color 0.2s;
    }
    .tds-back:hover { color: var(--accent); }
    .tds-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.7rem;
    }
    .tds-kicker::before {
        content: "";
        width: 26px;
        height: 2px;
        background: linear-gradient(90deg, var(--accent), transparent);
        border-radius: 2px;
    }
    .tds-title {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(1.6rem, 3.4vw, 2.3rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.18;
        letter-spacing: -0.02em;
        margin: 0 0 0.7rem;
    }
    .tds-title span {
        background: linear-gradient(90deg, #00d4ff, #7ab8f5);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .tds-sub {
        font-size: 0.95rem;
        color: var(--ice-200);
        line-height: 1.7;
        margin: 0;
        max-width: 620px;
    }
    .tds-nota {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 1rem;
        padding: 0.5rem 0.95rem;
        border-radius: 10px;
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--ice-200);
        background: rgba(0,212,255,0.07);
        border: 1px solid rgba(0,212,255,0.25);
    }

    /* ── Tarjeta del formulario ── */
    .tds-card {
        max-width: 800px;
        margin: 0 auto;
        padding: 2.2rem 2.2rem 2rem;
        border-radius: 20px;
        background: rgba(13,31,60,0.7);
        border: 1px solid rgba(74,144,217,0.2);
        box-shadow: 0 24px 60px rgba(0,0,0,0.35);
    }

    /* ── Asistente de pasos ── */
    .tds-wizard-bar {
        display: flex;
        align-items: flex-start;
        gap: 0.4rem;
        margin: 0.4rem 0 1.8rem;
    }
    .tds-wizard-step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.45rem;
        text-align: center;
        position: relative;
        padding: 0 0.35rem;
    }
    .tds-wizard-step::before {
        content: '';
        position: absolute;
        top: 15px;
        left: calc(-50% + 26px);
        right: calc(50% + 26px);
        height: 2px;
        background: rgba(74,144,217,0.18);
        z-index: 0;
    }
    .tds-wizard-step:first-child::before { display: none; }
    .tds-wizard-step.is-done::before { background: linear-gradient(90deg, var(--accent), rgba(0,212,255,0.35)); }
    .tds-wizard-num {
        position: relative;
        z-index: 1;
        width: 30px; height: 30px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 700;
        color: var(--ice-200);
        background: rgba(13,31,60,0.9);
        border: 1px solid rgba(74,144,217,0.3);
        transition: all 0.25s;
    }
    .tds-wizard-step.is-active .tds-wizard-num {
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 0 0 4px rgba(0,212,255,0.12), 0 4px 14px rgba(0,136,204,0.4);
    }
    .tds-wizard-step.is-done .tds-wizard-num {
        background: rgba(16,185,129,0.18);
        color: #4ade80;
        border-color: rgba(16,185,129,0.4);
    }
    .tds-wizard-txt {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--ice-300);
        letter-spacing: 0.04em;
        line-height: 1.3;
    }
    .tds-wizard-step.is-active .tds-wizard-txt { color: #fff; }
    .tds-wizard-step.is-done .tds-wizard-txt { color: var(--ice-200); }

    /* ── Título de cada paso ── */
    .tds-paso-titulo {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .tds-paso-titulo::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, rgba(74,144,217,0.3), transparent);
    }

    /* ── Campos del formulario ── */
    .tds-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .tds-form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .tds-form-group.full { grid-column: 1 / -1; }
    .tds-label {
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--ice-100);
        letter-spacing: 0.04em;
    }
    .tds-input, .tds-textarea, .tds-select {
        width: 100%;
        padding: 0.72rem 0.95rem;
        border-radius: 10px;
        font-size: 0.9rem;
        color: #fff;
        background: rgba(7,26,46,0.6);
        border: 1px solid rgba(74,144,217,0.25);
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: 'Inter', sans-serif;
    }
    .tds-select { appearance: none; -webkit-appearance: none; cursor: pointer; }
    .tds-select option { background: #0d1f33; color: #fff; }
    .tds-input::placeholder, .tds-textarea::placeholder { color: var(--ice-300); }
    .tds-input:focus, .tds-textarea:focus, .tds-select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(0,212,255,0.12);
    }
    .tds-textarea { resize: vertical; min-height: 90px; }
    .tds-error { font-size: 0.72rem; color: #f87171; }
    .tds-input.tds-invalid, .tds-select.tds-invalid, .tds-textarea.tds-invalid {
        border-color: #f87171;
        box-shadow: 0 0 0 3px rgba(248,113,113,0.12);
    }

    /* ── Navegación de pasos ── */
    .tds-paso-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        margin-top: 1.6rem;
        padding-top: 1.2rem;
        border-top: 1px solid rgba(74,144,217,0.14);
    }

    /* ── Botones ── */
    .tds-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.85rem 1.7rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.25s;
        font-family: 'Inter', sans-serif;
    }
    .tds-btn-primary {
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        color: #fff;
        box-shadow: 0 8px 24px rgba(0,136,204,0.35);
    }
    .tds-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(0,136,204,0.5);
        background: linear-gradient(135deg, #0099dd, #00bbff);
    }
    .tds-btn-ghost {
        background: rgba(74,144,217,0.06);
        border-color: rgba(74,144,217,0.3);
        color: var(--ice-100);
    }
    .tds-btn-ghost:hover {
        background: rgba(74,144,217,0.14);
        border-color: rgba(0,212,255,0.5);
        color: #fff;
    }
    .tds-btn-block { width: 100%; }

    /* ── Seguro Sí / No ── */
    .tds-radio-row {
        display: flex;
        gap: 0.7rem;
        flex-wrap: wrap;
    }
    .tds-radio-opt {
        position: relative;
        flex: 1;
        min-width: 150px;
    }
    .tds-radio-opt input { position: absolute; opacity: 0; pointer-events: none; }
    .tds-radio-opt span {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--ice-200);
        background: rgba(7,26,46,0.55);
        border: 1px solid rgba(74,144,217,0.25);
        cursor: pointer;
        transition: all 0.2s;
    }
    .tds-radio-opt input:checked + span {
        background: rgba(0,212,255,0.12);
        border-color: rgba(0,212,255,0.55);
        color: #fff;
        box-shadow: 0 0 0 3px rgba(0,212,255,0.08);
    }
    .tds-radio-opt.tds-radio-no input:checked + span {
        background: rgba(244,63,94,0.12);
        border-color: rgba(244,63,94,0.5);
    }

    .tds-seguro-extra { display: none; }
    .tds-seguro-extra.is-visible { display: block; }

    /* ── Resumen ── */
    .tds-resumen {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(74,144,217,0.2);
        margin-bottom: 1.4rem;
    }
    .tds-resumen-item {
        padding: 0.7rem 1rem;
        border-bottom: 1px solid rgba(74,144,217,0.1);
        background: rgba(7,26,46,0.4);
    }
    .tds-resumen-item:nth-child(even) { background: rgba(13,31,60,0.4); }
    .tds-resumen-label {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ice-300);
        margin-bottom: 0.2rem;
    }
    .tds-resumen-val { font-size: 0.86rem; font-weight: 600; color: #fff; word-break: break-word; }

    /* ── Checkboxes ── */
    .tds-checkbox-row {
        display: flex;
        align-items: flex-start;
        gap: 0.7rem;
        padding: 0.85rem 1rem;
        border-radius: 10px;
        background: rgba(7,26,46,0.45);
        border: 1px solid rgba(74,144,217,0.16);
        cursor: pointer;
        margin-bottom: 0.8rem;
        transition: border-color 0.2s, background 0.2s;
    }
    .tds-checkbox-row:hover { border-color: rgba(74,144,217,0.35); }
    .tds-checkbox-row input { width: 17px; height: 17px; margin-top: 1px; accent-color: #00d4ff; flex-shrink: 0; }
    .tds-checkbox-row span { font-size: 0.8rem; color: var(--ice-100); line-height: 1.55; }
    .tds-checkbox-row a { color: var(--accent); }
    .tds-checkbox-row.tds-invalid { border-color: #f87171; background: rgba(248,113,113,0.06); }

    /* ── Mensaje de error por paso ── */
    .tds-paso-error {
        display: none;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #f87171;
        background: rgba(248,113,113,0.08);
        border: 1px solid rgba(248,113,113,0.25);
        border-radius: 10px;
        padding: 0.7rem 1rem;
        margin-bottom: 1rem;
    }
    .tds-paso-error.is-visible { display: flex; }

    /* ── Seguridad / pie ── */
    .tds-seguridad {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 1.4rem;
        font-size: 0.74rem;
        color: var(--ice-300);
        text-align: center;
    }

    @media (max-width: 560px) {
        .tds-card { padding: 1.6rem 1.1rem; }
        .tds-form-grid { grid-template-columns: 1fr; }
        .tds-wizard-txt { display: none; }
        .tds-resumen { grid-template-columns: 1fr; }
        .tds-paso-nav { flex-direction: column-reverse; }
        .tds-paso-nav .tds-btn { width: 100%; }
    }
</style>

<div class="tds-page">

    {{-- Cabecera de la solicitud --}}
    <div class="tds-header">
        <a href="{{ route('tarjeta.debito') }}" class="tds-back">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
            Volver a la Tarjeta Débito
        </a>
        <div class="tds-kicker">Apertura de cuenta · Tarjeta Débito MReyze</div>
        <h1 class="tds-title">Solicita tu <span>Tarjeta Débito</span></h1>
        <p class="tds-sub">
            Completa el siguiente formulario con tus datos para registrar tu solicitud.
            Nuestro equipo la revisará y te contactaremos con el resultado. El proceso toma unos minutos.
        </p>
        <div class="tds-nota">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm7 10c0 4.52-2.98 8.69-7 9.93-4.02-1.24-7-5.41-7-9.93V6.3l7-3.11 7 3.11V11z"/></svg>
            Registro gratuito · Tus datos son tratados de forma confidencial.
        </div>
    </div>

    <div class="tds-card">

        {{-- Barra de progreso --}}
        <div class="tds-wizard-bar" id="tdsWizardBar">
            <div class="tds-wizard-step is-active" data-wstep="1">
                <span class="tds-wizard-num">1</span>
                <span class="tds-wizard-txt">Datos del cliente</span>
            </div>
            <div class="tds-wizard-step" data-wstep="2">
                <span class="tds-wizard-num">2</span>
                <span class="tds-wizard-txt">Dirección</span>
            </div>
            <div class="tds-wizard-step" data-wstep="3">
                <span class="tds-wizard-num">3</span>
                <span class="tds-wizard-txt">Información adicional</span>
            </div>
            <div class="tds-wizard-step" data-wstep="4">
                <span class="tds-wizard-num">4</span>
                <span class="tds-wizard-txt">Confirmación</span>
            </div>
        </div>

        <div class="tds-paso-error" id="tdsPasoError">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;flex-shrink:0"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
            <span>Revisa los campos marcados antes de continuar.</span>
        </div>

        @if($errors->any())
            <div class="tds-paso-error is-visible" id="tdsErroresServidor">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;flex-shrink:0"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                <span>No se pudo enviar la solicitud. Revisa los datos e inténtalo de nuevo.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('tarjeta.debito.store') }}" id="tdsForm" novalidate>
            @csrf

            {{-- PASO 1: DATOS DEL CLIENTE --}}
            <div class="tds-paso" data-paso="1">
                <h4 class="tds-paso-titulo">Datos del cliente</h4>
                <div class="tds-form-grid">
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_nombres">Nombres *</label>
                        <input id="tds_nombres" name="nombres" type="text" class="tds-input" data-required
                               value="{{ old('nombres') }}" placeholder="Tus nombres" autocomplete="given-name">
                        @error('nombres')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_apellidos">Apellidos *</label>
                        <input id="tds_apellidos" name="apellidos" type="text" class="tds-input" data-required
                               value="{{ old('apellidos') }}" placeholder="Tus apellidos" autocomplete="family-name">
                        @error('apellidos')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_tipo_documento">Tipo de documento *</label>
                        <select id="tds_tipo_documento" name="tipo_documento" class="tds-select" data-required>
                            <option value="">Selecciona una opción</option>
                            @foreach($tiposDocumento as $k => $label)
                                <option value="{{ $k }}" @selected(old('tipo_documento') === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipo_documento')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_numero_documento">Número de documento *</label>
                        <input id="tds_numero_documento" name="numero_documento" type="text" class="tds-input" data-required
                               value="{{ old('numero_documento') }}" placeholder="Ej. 12345678" inputmode="numeric">
                        @error('numero_documento')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_fecha_nacimiento">Fecha de nacimiento *</label>
                        <input id="tds_fecha_nacimiento" name="fecha_nacimiento" type="date" class="tds-input" data-required
                               value="{{ old('fecha_nacimiento') }}" max="{{ now()->toDateString() }}">
                        @error('fecha_nacimiento')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_celular">Celular *</label>
                        <input id="tds_celular" name="celular" type="text" class="tds-input" data-required
                               value="{{ old('celular') }}" placeholder="Ej. 987654321" inputmode="tel" autocomplete="tel">
                        @error('celular')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_email">Correo electrónico *</label>
                        <input id="tds_email" name="email" type="email" class="tds-input" data-required
                               value="{{ old('email') }}" placeholder="tucorreo@ejemplo.com" autocomplete="email">
                        @error('email')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_password">Contraseña *</label>
                        <input id="tds_password" name="password" type="password" class="tds-input" data-required
                               placeholder="Mínimo 8 caracteres" autocomplete="new-password">
                        @error('password')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_password_confirmation">Confirmar contraseña *</label>
                        <input id="tds_password_confirmation" name="password_confirmation" type="password" class="tds-input" data-required
                               placeholder="Repite tu contraseña" autocomplete="new-password">
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_estado_civil">Estado civil *</label>
                        <select id="tds_estado_civil" name="estado_civil" class="tds-select" data-required>
                            <option value="">Selecciona una opción</option>
                            @foreach($estadosCiviles as $k => $label)
                                <option value="{{ $k }}" @selected(old('estado_civil') === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('estado_civil')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_nombre_padre">Nombre del padre *</label>
                        <input id="tds_nombre_padre" name="nombre_padre" type="text" class="tds-input" data-required
                               value="{{ old('nombre_padre') }}" placeholder="Nombre y apellidos del padre">
                        @error('nombre_padre')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_nombre_madre">Nombre de la madre *</label>
                        <input id="tds_nombre_madre" name="nombre_madre" type="text" class="tds-input" data-required
                               value="{{ old('nombre_madre') }}" placeholder="Nombre y apellidos de la madre">
                        @error('nombre_madre')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_emergencia_parentesco">Contacto de emergencia — parentesco *</label>
                        <input id="tds_emergencia_parentesco" name="emergencia_parentesco" type="text" class="tds-input" data-required
                               value="{{ old('emergencia_parentesco') }}" placeholder="Ej. Hermana, tío, amigo...">
                        @error('emergencia_parentesco')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_emergencia_telefono">Teléfono de emergencia *</label>
                        <input id="tds_emergencia_telefono" name="emergencia_telefono" type="text" class="tds-input" data-required
                               value="{{ old('emergencia_telefono') }}" placeholder="Ej. 987654321" inputmode="tel">
                        @error('emergencia_telefono')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="tds-paso-nav">
                    <button type="button" class="tds-btn tds-btn-ghost" data-nav="prev" hidden>Anterior</button>
                    <button type="button" class="tds-btn tds-btn-primary" data-nav="next">Continuar</button>
                </div>
            </div>

            {{-- PASO 2: DIRECCIÓN --}}
            <div class="tds-paso" data-paso="2" style="display:none;">
                <h4 class="tds-paso-titulo">Dirección de residencia</h4>
                <div class="tds-form-grid">
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_departamento">Departamento *</label>
                        <select id="tds_departamento" name="departamento" class="tds-select" data-required>
                            <option value="">Cargando departamentos...</option>
                        </select>
                        @error('departamento')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_provincia">Provincia *</label>
                        <select id="tds_provincia" name="provincia" class="tds-select" data-required>
                            <option value="">Selecciona tu departamento</option>
                        </select>
                        @error('provincia')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group full">
                        <label class="tds-label" for="tds_distrito">Distrito *</label>
                        <select id="tds_distrito" name="distrito" class="tds-select" data-required>
                            <option value="">Selecciona tu provincia</option>
                        </select>
                        @error('distrito')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group full">
                        <label class="tds-label" for="tds_direccion_calle">Calle / Avenida / Jirón *</label>
                        <input id="tds_direccion_calle" name="direccion_calle" type="text" class="tds-input" data-required
                               value="{{ old('direccion_calle') }}" placeholder="Nombre de la calle o avenida">
                        @error('direccion_calle')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_direccion_numero">Número / interior *</label>
                        <input id="tds_direccion_numero" name="direccion_numero" type="text" class="tds-input" data-required
                               value="{{ old('direccion_numero') }}" placeholder="Ej. 123, Mz. B Lt. 5, dpto. 402">
                        @error('direccion_numero')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="tds-paso-nav">
                    <button type="button" class="tds-btn tds-btn-ghost" data-nav="prev">Anterior</button>
                    <button type="button" class="tds-btn tds-btn-primary" data-nav="next">Continuar</button>
                </div>
            </div>

            {{-- PASO 3: INFORMACIÓN ADICIONAL --}}
            <div class="tds-paso" data-paso="3" style="display:none;">
                <h4 class="tds-paso-titulo">Información adicional</h4>
                <div class="tds-form-grid">
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_ocupacion">Ocupación *</label>
                        <input id="tds_ocupacion" name="ocupacion" type="text" class="tds-input" data-required
                               value="{{ old('ocupacion') }}" placeholder="Ej. Empleado, independiente, estudiante...">
                        @error('ocupacion')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_empresa">Empresa / Centro de trabajo *</label>
                        <input id="tds_empresa" name="empresa" type="text" class="tds-input" data-required
                               value="{{ old('empresa') }}" placeholder="Nombre de la empresa donde laboras">
                        @error('empresa')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_ingresos">Ingresos aproximados (S/.) *</label>
                        <input id="tds_ingresos" name="ingresos_aproximados" type="number" step="0.01" min="0" class="tds-input" data-required
                               value="{{ old('ingresos_aproximados') }}" placeholder="Ej. 2500" inputmode="decimal">
                        @error('ingresos_aproximados')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group">
                        <label class="tds-label" for="tds_antiguedad">Antigüedad laboral *</label>
                        <input id="tds_antiguedad" name="antiguedad_laboral" type="text" class="tds-input" data-required
                               value="{{ old('antiguedad_laboral') }}" placeholder="Ej. 2 años, 1 año y 6 meses...">
                        @error('antiguedad_laboral')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group full">
                        <label class="tds-label" for="tds_direccion_laboral">Dirección laboral *</label>
                        <input id="tds_direccion_laboral" name="direccion_laboral" type="text" class="tds-input" data-required
                               value="{{ old('direccion_laboral') }}" placeholder="Calle, distrito, provincia, departamento">
                        @error('direccion_laboral')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group full">
                        <label class="tds-label">¿Deseas contratar un seguro adicional? *</label>
                        <div class="tds-radio-row">
                            <label class="tds-radio-opt">
                                <input type="radio" name="desea_seguro" value="1" @checked(old('desea_seguro') == '1')>
                                <span>Sí, quiero un seguro</span>
                            </label>
                            <label class="tds-radio-opt tds-radio-no">
                                <input type="radio" name="desea_seguro" value="0" @checked(old('desea_seguro') == '0')>
                                <span>No, por ahora</span>
                            </label>
                        </div>
                        @error('desea_seguro')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="tds-form-group full tds-seguro-extra" id="tdsSeguroExtra">
                        <label class="tds-label" for="tds_tipo_seguro">Tipo de seguro *</label>
                        <select id="tds_tipo_seguro" name="tipo_seguro" class="tds-select">
                            <option value="">Selecciona una opción</option>
                            @foreach($tiposSeguro as $k => $label)
                                <option value="{{ $k }}" @selected(old('tipo_seguro') === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipo_seguro')<span class="tds-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="tds-paso-nav">
                    <button type="button" class="tds-btn tds-btn-ghost" data-nav="prev">Anterior</button>
                    <button type="button" class="tds-btn tds-btn-primary" data-nav="next">Continuar</button>
                </div>
            </div>

            {{-- PASO 4: CONFIRMACIÓN --}}
            <div class="tds-paso" data-paso="4" style="display:none;">
                <h4 class="tds-paso-titulo">Confirma tus datos</h4>
                <div class="tds-resumen" id="tdsResumen"></div>

                <label class="tds-checkbox-row" for="tds_acepta_terminos">
                    <input type="checkbox" id="tds_acepta_terminos" name="acepta_terminos" value="1" @checked(old('acepta_terminos'))>
                    <span>He leído y acepto los <a href="#" onclick="return false;">términos y condiciones</a> y la
                        <a href="#" onclick="return false;">política de privacidad</a> de MReyze. *</span>
                </label>
                @error('acepta_terminos')<span class="tds-error">{{ $message }}</span>@enderror

                <label class="tds-checkbox-row" for="tds_datos_correctos">
                    <input type="checkbox" id="tds_datos_correctos" name="datos_correctos" value="1" @checked(old('datos_correctos'))>
                    <span>Confirmo que los datos proporcionados son correctos, reales y verificables. *</span>
                </label>
                @error('datos_correctos')<span class="tds-error">{{ $message }}</span>@enderror

                <div class="tds-paso-nav">
                    <button type="button" class="tds-btn tds-btn-ghost" data-nav="prev">Anterior</button>
                    <button type="submit" class="tds-btn tds-btn-primary">Enviar solicitud</button>
                </div>
            </div>
        </form>

        <div class="tds-seguridad">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
            Información protegida · Solo el equipo autorizado de MReyze puede ver esta solicitud.
        </div>
    </div>
</div>

{{-- ══════ JS DEL FORMULARIO (asistente de pasos + ubigeo) ══════ --}}
<script>
(function () {
    const numPasos = 4;
    const form = document.getElementById('tdsForm');
    const bar = document.getElementById('tdsWizardBar');
    const errorBox = document.getElementById('tdsPasoError');
    const pasos = Array.from(form.querySelectorAll('.tds-paso'));

    let actual = 1;

    const campo = function (id) { return document.getElementById(id); };

    function mostrarPaso(n) {
        actual = n;
        pasos.forEach(function (p) { p.style.display = p.dataset.paso === String(n) ? 'block' : 'none'; });
        bar.querySelectorAll('.tds-wizard-step').forEach(function (s) {
            const k = parseInt(s.dataset.wstep, 10);
            s.classList.toggle('is-active', k === n);
            s.classList.toggle('is-done', k < n);
        });
        errorBox.classList.remove('is-visible');
        if (n === 4) actualizarResumen();
        const target = form.getBoundingClientRect().top + window.pageYOffset - 110;
        window.scrollTo({ top: target, behavior: 'smooth' });
    }

    function marcar(el, ok) {
        el.classList.toggle('tds-invalid', !ok);
    }

    function validarPaso(n) {
        const paso = pasos.find(function (p) { return p.dataset.paso === String(n); });
        const errores = [];

        paso.querySelectorAll('[data-required]').forEach(function (el) {
            if (el.type === 'checkbox') return;
            const ok = el.value.trim() !== '';
            marcar(el, ok);
            if (!ok) errores.push(el);
        });

        if (n === 1) {
            const email = campo('tds_email');
            if (email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
                marcar(email, false); errores.push(email);
            }
            const pass = campo('tds_password');
            const pass2 = campo('tds_password_confirmation');
            const passOk = pass.value.trim().length >= 8;
            marcar(pass, passOk);
            if (!passOk) errores.push(pass);
            const pass2Ok = pass2.value.trim() === pass.value.trim() && pass.value.trim() !== '';
            marcar(pass2, pass2Ok);
            if (!pass2Ok) errores.push(pass2);
            const nac = campo('tds_fecha_nacimiento');
            if (nac.value && new Date(nac.value) >= new Date()) {
                marcar(nac, false); errores.push(nac);
            }
        }

        if (n === 3) {
            const val = form.querySelector('input[name="desea_seguro"]:checked');
            if (!val) errores.push('seguro');
            const ts = campo('tds_tipo_seguro');
            if (val && val.value === '1' && ts.value.trim() === '') {
                marcar(ts, false); errores.push(ts);
            } else {
                marcar(ts, true);
            }
        }

        if (n === 4) {
            [campo('tds_acepta_terminos'), campo('tds_datos_correctos')].forEach(function (c) {
                const row = c.closest('.tds-checkbox-row');
                row.classList.toggle('tds-invalid', !c.checked);
                if (!c.checked) errores.push(c);
            });
        }

        return errores.length === 0;
    }

    form.addEventListener('click', function (e) {
        const nav = e.target.closest('[data-nav]');
        if (!nav) return;
        if (nav.dataset.nav === 'next') {
            if (validarPaso(actual)) {
                mostrarPaso(actual + 1);
            } else {
                errorBox.classList.add('is-visible');
                const firstErr = form.querySelector('.tds-invalid');
                if (firstErr) firstErr.focus();
            }
        } else if (nav.dataset.nav === 'prev') {
            mostrarPaso(actual - 1);
        }
    });

    form.addEventListener('submit', function (e) {
        for (let n = 1; n <= numPasos; n++) {
            if (!validarPaso(n)) {
                e.preventDefault();
                mostrarPaso(n);
                errorBox.classList.add('is-visible');
                const firstErr = form.querySelector('.tds-invalid');
                if (firstErr) firstErr.focus();
                return;
            }
        }
    });

    // ── Resumen ──
    function txt(sel) {
        return sel.selectedOptions.length ? sel.selectedOptions[0].text : '';
    }
    function actualizarResumen() {
        const seguro = form.querySelector('input[name="desea_seguro"]:checked');
        const items = [
            ['Nombre completo', (campo('tds_nombres').value.trim() + ' ' + campo('tds_apellidos').value.trim()).trim()],
            ['Documento', txt(campo('tds_tipo_documento')) + ' · ' + campo('tds_numero_documento').value.trim()],
            ['Fecha de nacimiento', campo('tds_fecha_nacimiento').value],
            ['Correo electrónico', campo('tds_email').value.trim()],
            ['Celular', campo('tds_celular').value.trim()],
            ['Estado civil', txt(campo('tds_estado_civil'))],
            ['Departamento', campo('tds_departamento').value],
            ['Provincia', campo('tds_provincia').value],
            ['Distrito', campo('tds_distrito').value],
            ['Dirección', (campo('tds_direccion_calle').value.trim() + ' ' + campo('tds_direccion_numero').value.trim()).trim()],
            ['Ocupación', campo('tds_ocupacion').value.trim()],
            ['Empresa', campo('tds_empresa').value.trim()],
            ['Ingresos aproximados', 'S/ ' + campo('tds_ingresos').value.trim()],
            ['Antigüedad laboral', campo('tds_antiguedad').value.trim()],
            ['Dirección laboral', campo('tds_direccion_laboral').value.trim()],
            ['Seguro adicional', seguro ? (seguro.value === '1' ? 'Sí' : 'No') : ''],
            ['Tipo de seguro', txt(campo('tds_tipo_seguro')) || '—']
        ];
        const cont = campo('tdsResumen');
        cont.innerHTML = items.map(function (it) {
            return '<div class="tds-resumen-item"><div class="tds-resumen-label">' + it[0] +
                '</div><div class="tds-resumen-val">' + (it[1] ? it[1] : '—') + '</div></div>';
        }).join('');
    }

    // ── Ubigeo en cascada ──
    const DATA = {
        deps: '{{ asset('data/ubigeo-departamentos.json') }}',
        provs: '{{ asset('data/ubigeo-provincias.json') }}',
        dists: '{{ asset('data/ubigeo-distritos.json') }}'
    };
    const depSel = campo('tds_departamento');
    const provSel = campo('tds_provincia');
    const disSel = campo('tds_distrito');
    const oldDep = '{{ old('departamento') }}';
    const oldProv = '{{ old('provincia') }}';
    const oldDis = '{{ old('distrito') }}';
    let deps = [], provs = [], dists = [];

    function llenarSelect(sel, nombres, placeholder) {
        sel.innerHTML = '';
        const opt0 = document.createElement('option');
        opt0.value = '';
        opt0.textContent = placeholder;
        sel.appendChild(opt0);
        nombres.forEach(function (nm) {
            const o = document.createElement('option');
            o.value = nm;
            o.textContent = nm;
            sel.appendChild(o);
        });
    }
    function depId() {
        const d = deps.find(function (x) { return x.departamento === depSel.value; });
        return d ? d.id : null;
    }
    function provId() {
        const did = depId();
        const p = provs.find(function (x) { return x.provincia === provSel.value && x.departamento_id == did; });
        return p ? p.id : null;
    }
    function llenarProvincias() {
        const id = depId();
        llenarSelect(provSel, id ? provs.filter(function (p) { return p.departamento_id == id; }).map(function (p) { return p.provincia; }) : [], 'Selecciona tu provincia');
        if (oldProv) provSel.value = oldProv;
        llenarDistritos();
    }
    function llenarDistritos() {
        const id = provId();
        llenarSelect(disSel, id ? dists.filter(function (d) { return d.provincia_id == id; }).map(function (d) { return d.distrito; }) : [], 'Selecciona tu distrito');
        if (oldDis) disSel.value = oldDis;
    }

    async function cargarUbigeo() {
        try {
            const res = await Promise.all([
                fetch(DATA.deps).then(function (r) { return r.json(); }),
                fetch(DATA.provs).then(function (r) { return r.json(); }),
                fetch(DATA.dists).then(function (r) { return r.json(); })
            ]);
            deps = res[0].ubigeo_departamentos || [];
            provs = res[1].ubigeo_provincias || [];
            dists = res[2].ubigeo_distritos || [];
            llenarSelect(depSel, deps.map(function (x) { return x.departamento; }), 'Selecciona tu departamento');
            if (oldDep) { depSel.value = oldDep; llenarProvincias(); }
        } catch (err) {
            depSel.innerHTML = '<option value="">No se pudo cargar los departamentos</option>';
        }
    }

    depSel.addEventListener('change', function () { llenarProvincias(); });
    provSel.addEventListener('change', function () { llenarDistritos(); });

    // ── Seguro adicional ──
    const seguroExtra = campo('tdsSeguroExtra');
    const seguroRadios = form.querySelectorAll('input[name="desea_seguro"]');
    function toggleSeguro() {
        const val = form.querySelector('input[name="desea_seguro"]:checked');
        seguroExtra.classList.toggle('is-visible', !!(val && val.value === '1'));
    }
    seguroRadios.forEach(function (r) { r.addEventListener('change', toggleSeguro); });

    // ── Inicio ──
    mostrarPaso(1);
    toggleSeguro();
    cargarUbigeo();
})();
</script>
@endsection
