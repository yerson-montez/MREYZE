@extends('welcome')

@section('title', 'Invertir — ' . $proyecto->titulo . ' · MREYZE')

@section('content')

@php
    $roiNum = $proyecto->inversion_roi_numero ?? 0;
    $tiempo = (int) $proyecto->inversion_tiempo_estimado ?: 12;
    $disponible = (float) $proyecto->inversion_monto_disponible;
    $financiado = (int) $proyecto->inversion_porcentaje_financiado;
@endphp

<style>
    .inv {
        max-width: 1280px;
        margin: 0 auto;
        padding: 6.5rem 2rem 5rem;
    }

    /* ── HERO ── */
    .inv-hero {
        position: relative;
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 3rem;
        align-items: center;
        margin-bottom: 3rem;
    }

    .inv-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 1.2rem;
    }

    .inv-hero-badge.abierto { background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.4); color: #4ade80; }
    .inv-hero-badge.disponible { background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.4); color: #4ade80; }
    .inv-hero-badge.pronto  { background: rgba(251,191,36,.12); border: 1px solid rgba(251,191,36,.4); color: #fbbf24; }
    .inv-hero-badge.en_ejecucion { background: rgba(251,191,36,.12); border: 1px solid rgba(251,191,36,.4); color: #fbbf24; }
    .inv-hero-badge.completado { background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.4); color: #4ade80; }

    .inv-hero h1 {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.12;
        margin-bottom: 1rem;
    }

    .inv-hero h1 span {
        background: linear-gradient(135deg, var(--ice-200), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .inv-hero-desc { color: var(--ice-200); font-size: 1rem; line-height: 1.7; margin-bottom: 1.6rem; max-width: 520px; }

    .inv-hero-imagen {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(0,212,255,.2);
        box-shadow: 0 20px 60px rgba(0,60,160,.35);
        aspect-ratio: 4 / 3;
        background: linear-gradient(135deg, rgba(13,31,60,.9), rgba(7,18,36,.95));
    }

    .inv-hero-imagen img { width: 100%; height: 100%; object-fit: cover; }

    .inv-hero-imagen::after {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(7,17,30,.85));
    }

    .inv-hero-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .inv-stat {
        background: rgba(13,31,60,.85);
        border: 1px solid rgba(74,144,217,.2);
        border-radius: 14px;
        padding: 1rem 1.2rem;
    }

    .inv-stat-val { font-family: 'Exo 2', sans-serif; font-size: 1.25rem; font-weight: 800; color: #fff; }
    .inv-stat-val.amarillo span { color: #fbbf24; }
    .inv-stat-val.verde span { color: #4ade80; }
    .inv-stat-val span { color: var(--accent); }
    .inv-stat-label { font-size: .68rem; color: var(--ice-300); text-transform: uppercase; letter-spacing: .08em; margin-top: 3px; }

    /* ── BARRA ── */
    .inv-barra-wrap { margin-top: 1.8rem; }
    .inv-barra-label { display: flex; justify-content: space-between; font-size: .8rem; color: var(--ice-300); margin-bottom: 8px; }
    .inv-barra-label strong { color: var(--accent); }
    .inv-barra-bg { height: 12px; background: rgba(7,26,46,.6); border-radius: 20px; overflow: hidden; border: 1px solid rgba(74,144,217,.15); }
    .inv-barra-fill { height: 100%; border-radius: 20px; background: linear-gradient(90deg,#2563c7,#00d4ff); transition: width .8s ease; box-shadow: 0 0 14px rgba(0,212,255,.5); }

    /* ── SIMULADOR ── */
    .inv-simulador {
        margin: 3rem 0;
        border-radius: 20px;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(13,31,60,.9), rgba(17,41,82,.85));
        border: 1px solid rgba(0,212,255,.2);
        position: relative;
        overflow: hidden;
    }

    .inv-simulador::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    .inv-sim-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: 2rem; align-items: center; }

    .inv-sim-rango { display: flex; flex-direction: column; gap: .8rem; }
    .inv-sim-rango input[type="range"] {
        -webkit-appearance: none; appearance: none;
        width: 100%; height: 8px; border-radius: 20px;
        background: linear-gradient(90deg, #1e4d99, #00d4ff);
        outline: none;
    }
    .inv-sim-rango input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none; appearance: none;
        width: 24px; height: 24px; border-radius: 50%;
        background: #fff; border: 4px solid var(--accent);
        cursor: pointer; box-shadow: 0 0 16px rgba(0,212,255,.7);
    }
    .inv-sim-monto {
        font-family: 'Exo 2', sans-serif; font-size: 2.2rem; font-weight: 800; color: #fff;
    }
    .inv-sim-monto small { font-size: 1.1rem; color: var(--ice-300); }
    .inv-sim-resultados { display: grid; gap: .8rem; }
    .inv-sim-fila {
        display: flex; justify-content: space-between; align-items: center;
        background: rgba(7,26,46,.5); border: 1px solid rgba(74,144,217,.15);
        border-radius: 12px; padding: .8rem 1.1rem;
    }
    .inv-sim-fila-label { font-size: .78rem; color: var(--ice-300); }
    .inv-sim-fila-val { font-family: 'Exo 2', sans-serif; font-weight: 700; color: #fff; }
    .inv-sim-fila-val.gain { color: #4ade80; }
    .inv-sim-fila-val.total { color: var(--accent); }

    /* ── BENEFICIOS ── */
    .inv-beneficios { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.2rem; margin: 3rem 0; }
    .inv-beneficio {
        background: rgba(13,31,60,.7); border: 1px solid rgba(74,144,217,.18);
        border-radius: 16px; padding: 1.6rem 1.4rem; transition: all .3s;
    }
    .inv-beneficio:hover { transform: translateY(-4px); border-color: rgba(0,212,255,.4); box-shadow: 0 12px 36px rgba(0,60,160,.3); }
    .inv-beneficio-icon {
        width: 46px; height: 46px; border-radius: 12px; margin-bottom: 1rem;
        display: flex; align-items: center; justify-content: center;
        background: rgba(0,212,255,.12); border: 1px solid rgba(0,212,255,.3); color: var(--accent);
    }
    .inv-beneficio h3 { font-family: 'Exo 2', sans-serif; font-size: 1rem; color: #fff; margin-bottom: .5rem; }
    .inv-beneficio p { font-size: .82rem; color: var(--ice-300); line-height: 1.6; margin: 0; }

    /* ── FORMULARIO ── */
    .inv-form-section { scroll-margin-top: 90px; }
    .inv-form-wrap {
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(13,31,60,.95), rgba(17,41,82,.9));
        border: 1px solid rgba(0,212,255,.25);
        padding: 2.5rem 2rem;
        position: relative; overflow: hidden;
        max-width: 860px; margin: 0 auto;
    }
    .inv-form-wrap::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }
    .inv-form-title { font-family: 'Exo 2', sans-serif; font-size: 1.6rem; font-weight: 800; color: #fff; margin-bottom: .4rem; }
    .inv-form-sub { color: var(--ice-300); font-size: .88rem; margin-bottom: 2rem; }
    .inv-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.1rem; }
    .inv-campo { display: flex; flex-direction: column; gap: 6px; }
    .inv-campo.full { grid-column: 1 / -1; }
    .inv-campo label { font-size: .76rem; font-weight: 600; color: var(--ice-100); text-transform: uppercase; letter-spacing: .05em; }
    .inv-campo input, .inv-campo select {
        background: rgba(7,26,46,.6); border: 1px solid rgba(74,144,217,.25);
        border-radius: 10px; padding: .75rem 1rem; color: #fff; font-size: .9rem; outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .inv-campo input:focus, .inv-campo select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,212,255,.15); }
    .inv-campo input::placeholder { color: rgba(122,154,184,.6); }
    .inv-campo input[type="file"] { padding: .5rem; }
    .inv-campo input[type="file"]::file-selector-button {
        background: rgba(0,212,255,.15); border: 1px solid rgba(0,212,255,.35); color: var(--accent);
        border-radius: 8px; padding: .45rem .9rem; margin-right: .8rem; cursor: pointer; font-weight: 600; font-size: .8rem;
    }
    .inv-error { font-size: .74rem; color: #ff6b6b; }
    .inv-hint { font-size: .72rem; color: var(--ice-300); }

    /* Métodos de pago */
    .inv-metodos { display: grid; gap: .9rem; margin-top: 1.4rem; }
    .inv-metodo {
        border: 1px solid rgba(74,144,217,.22); border-radius: 14px; padding: 1rem 1.2rem;
        background: rgba(7,26,46,.5); cursor: pointer; transition: all .2s; display: flex; gap: 1rem; align-items: flex-start;
    }
    .inv-metodo:hover { border-color: rgba(0,212,255,.45); }
    .inv-metodo.sel { border-color: var(--accent); background: rgba(0,212,255,.07); box-shadow: 0 0 0 3px rgba(0,212,255,.12); }
    .inv-metodo input { margin-top: 4px; accent-color: var(--accent); }
    .inv-metodo-info { flex: 1; }
    .inv-metodo-nombre { font-weight: 700; color: #fff; font-size: .95rem; margin-bottom: 6px; }
    .inv-metodo-linea { font-size: .8rem; color: var(--ice-200); line-height: 1.7; }
    .inv-metodo-linea strong { color: var(--accent); font-weight: 600; }
    .inv-metodo-qr { width: 64px; height: 64px; border-radius: 10px; border: 1px solid rgba(74,144,217,.3); object-fit: cover; flex-shrink: 0; }

    .inv-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        padding: 1rem 2.4rem; border-radius: 12px; border: none; cursor: pointer;
        font-size: 1rem; font-weight: 700; color: #fff; letter-spacing: .02em;
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        box-shadow: 0 8px 26px rgba(0,136,204,.4);
        transition: all .25s; text-decoration: none;
    }
    .inv-btn:hover { background: linear-gradient(135deg, #0099dd, #00bbff); transform: translateY(-2px); box-shadow: 0 12px 34px rgba(0,136,204,.55); }
    .inv-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    /* ── OTROS PROYECTOS ── */
    .inv-otros-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.2rem; }
    .inv-otra {
        background: rgba(13,31,60,.75); border: 1px solid rgba(74,144,217,.18);
        border-radius: 16px; overflow: hidden; transition: all .3s; text-decoration: none; display: block;
    }
    .inv-otra:hover { transform: translateY(-4px); border-color: rgba(0,212,255,.4); }
    .inv-otra-img { aspect-ratio: 16/9; background: linear-gradient(135deg, rgba(30,77,153,.6), rgba(7,18,36,.9)); }
    .inv-otra-img img { width: 100%; height: 100%; object-fit: cover; }
    .inv-otra-body { padding: 1rem 1.1rem; }
    .inv-otra-titulo { font-family: 'Exo 2', sans-serif; font-weight: 700; color: #fff; font-size: .92rem; margin-bottom: 6px; }
    .inv-otra-meta { font-size: .76rem; color: var(--ice-300); display: flex; justify-content: space-between; }
    .inv-otra-meta strong { color: var(--accent); }

    @media (max-width: 900px) {
        .inv-hero { grid-template-columns: 1fr; }
        .inv-sim-grid { grid-template-columns: 1fr; }
        .inv-beneficios { grid-template-columns: 1fr; }
        .inv-otros-grid { grid-template-columns: 1fr; }
        .inv-form-grid { grid-template-columns: 1fr; }
        .inv { padding: 5.5rem 1rem 4rem; }
    }
</style>

<div class="inv">

    {{-- ══════════════════ HERO ══════════════════ --}}
    <section class="inv-hero reveal">
        <div>
            <span class="inv-hero-badge {{ $proyecto->inversion_estado ?: 'pronto' }}">
                ● {{ $proyecto->inversion_estado_label ?? ($proyecto->inversion_etiqueta ?: 'Próximamente') }}
            </span>
            <h1>Invierte en <span>{{ $proyecto->titulo }}</span></h1>
            <p class="inv-hero-desc">{{ $proyecto->descripcion_corta }}</p>

            <a href="#inv-form" class="inv-btn">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                Comenzar mi inversión
            </a>
        </div>

        <div class="inv-hero-imagen">
            @if($proyecto->imagen_principal_url)
                <img src="{{ $proyecto->imagen_principal_url }}" alt="{{ $proyecto->titulo }}">
            @endif
        </div>
    </section>

    {{-- ══════════════════ DETALLE FINANCIERO ══════════════════ --}}
    <section class="reveal">
        <div class="inv-barra-wrap">
            <div class="inv-barra-label">
                <span>Financiamiento del proyecto</span>
                <strong>{{ $financiado }}%</strong>
            </div>
            <div class="inv-barra-bg">
                <div class="inv-barra-fill" style="width:{{ $financiado }}%"></div>
            </div>
        </div>

        <div class="inv-hero-stats">
            <div class="inv-stat">
                <div class="inv-stat-val amarillo">S/ <span>{{ number_format($proyecto->inversion_monto_total, 0, ',', ',') }}</span></div>
                <div class="inv-stat-label">Inversión total</div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-val">S/ <span>{{ number_format($disponible, 0, ',', ',') }}</span></div>
                <div class="inv-stat-label">Disponible aún</div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-val">{{ $tiempo }}<span> meses</span></div>
                <div class="inv-stat-label">Tiempo de ejecución</div>
            </div>
            <div class="inv-stat">
                <div class="inv-stat-val verde">ROI <span>{{ $proyecto->inversion_roi_label }}</span></div>
                <div class="inv-stat-label">Retorno estimado</div>
            </div>
        </div>
    </section>

    {{-- ══════════════════ SIMULADOR ══════════════════ --}}
    <section class="inv-simulador reveal">
        <div class="section-label">Simulador de rendimiento</div>
        <div class="inv-sim-grid">
            <div>
                <div class="inv-sim-monto">S/ <span id="simMonto">5,000</span></div>
                <div class="inv-sim-rango" style="margin-top:1.2rem;">
                    <input type="range" id="simRango" min="500" max="{{ max(500, floor($disponible)) }}" step="500" value="5000">
                    <div style="display:flex;justify-content:space-between;font-size:.72rem;color:var(--ice-300);">
                        <span>S/ 500</span>
                        <span>S/ {{ number_format($disponible, 0, ',', ',') }}</span>
                    </div>
                </div>
                <p class="inv-hint" style="margin-top:1rem;">Desliza para elegir cuánto quieres invertir. El monto mínimo es S/ 50.</p>
            </div>
            <div class="inv-sim-resultados">
                <div class="inv-sim-fila">
                    <span class="inv-sim-fila-label">Tu inversión</span>
                    <span class="inv-sim-fila-val" id="simBase">S/ 5,000</span>
                </div>
                <div class="inv-sim-fila">
                    <span class="inv-sim-fila-label">Retorno estimado ({{ $proyecto->inversion_roi_label }})</span>
                    <span class="inv-sim-fila-val gain" id="simGanancia">S/ 0</span>
                </div>
                <div class="inv-sim-fila">
                    <span class="inv-sim-fila-label">Total proyectado ({{ $tiempo }} meses)</span>
                    <span class="inv-sim-fila-val total" id="simTotal">S/ 0</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════ BENEFICIOS ══════════════════ --}}
    <section class="inv-beneficios reveal">
        <div class="inv-beneficio">
            <div class="inv-beneficio-icon">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:22px;height:22px"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
            </div>
            <h3>Retornos atractivos</h3>
            <p>ROI estimado de <strong style="color:#4ade80;">{{ $proyecto->inversion_roi_label }}</strong> con proyecciones claras y plazos definidos por proyecto.</p>
        </div>
        <div class="inv-beneficio">
            <div class="inv-beneficio-icon">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:22px;height:22px"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
            </div>
            <h3>Transparencia total</h3>
            <p>Cada inversión cuenta con contrato, seguimiento del financiamiento en tiempo real y reportes del avance del proyecto.</p>
        </div>
        <div class="inv-beneficio">
            <div class="inv-beneficio-icon">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:22px;height:22px"><path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/></svg>
            </div>
            <h3>Sin barreras de entrada</h3>
            <p>Puedes participar desde <strong style="color:var(--accent);">S/ 50</strong>. Aporta total o parcialmente y sé parte del resultado.</p>
        </div>
    </section>

    {{-- ══════════════════ FORMULARIO ══════════════════ --}}
    <section class="inv-form-section" id="inv-form">
        <div class="inv-form-wrap reveal">
            <h2 class="inv-form-title">Solicita tu inversión</h2>
            <p class="inv-form-sub">Completa tus datos y elige el medio de pago. Nuestro equipo confirmará tu aporte para activar tu participación.</p>

            @if($errors->any())
                <div style="background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.3);color:#ff9c9c;border-radius:10px;padding:.9rem 1.1rem;font-size:.85rem;margin-bottom:1.4rem;">
                    <strong>Revisa los siguientes campos:</strong>
                    <ul style="margin:6px 0 0 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('inversiones.store', $proyecto) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="inv-form-grid">
                    <div class="inv-campo">
                        <label for="nombre">Nombre completo *</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej. María Pérez" required>
                        @error('nombre') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="inv-campo">
                        <label for="email">Correo electrónico *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="tu@correo.com" required>
                        @error('email') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="inv-campo">
                        <label for="telefono">Teléfono / WhatsApp</label>
                        <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="999 999 999">
                    </div>

                    <div class="inv-campo">
                        <label for="dni">DNI / RUC</label>
                        <input type="text" id="dni" name="dni" value="{{ old('dni') }}" placeholder="Ej. 12345678">
                    </div>

                    <div class="inv-campo full">
                        <label for="monto">Monto a invertir (S/) * <span class="inv-hint">— Disponible: S/ {{ number_format($disponible, 2) }}</span></label>
                        <input type="number" step="0.01" min="50" max="{{ $disponible }}" id="monto" name="monto" value="{{ old('monto', 5000) }}" required>
                        @error('monto') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($metodosPago->count())
                    <div class="inv-metodos">
                        @foreach($metodosPago as $metodo)
                            <label class="inv-metodo" id="metodo-{{ $metodo->id }}">
                                <input type="radio" name="metodo_pago_id" value="{{ $metodo->id }}" {{ old('metodo_pago_id', $metodosPago->first()->id) == $metodo->id ? 'checked' : '' }} required>
                                <div class="inv-metodo-info">
                                    <div class="inv-metodo-nombre">{{ $metodo->emoji }} {{ $metodo->nombre }}</div>
                                    @if(!empty($metodo->datos['numero']))
                                        <div class="inv-metodo-linea">Cuenta: <strong>{{ $metodo->datos['numero'] }}</strong></div>
                                    @endif
                                    @if(!empty($metodo->datos['cci']))
                                        <div class="inv-metodo-linea">CCI: <strong>{{ $metodo->datos['cci'] }}</strong></div>
                                    @endif
                                    @if(!empty($metodo->datos['titular']))
                                        <div class="inv-metodo-linea">Titular: <strong>{{ $metodo->datos['titular'] }}</strong></div>
                                    @endif
                                    @if(!empty($metodo->datos['alias']))
                                        <div class="inv-metodo-linea">Alias: <strong>{{ $metodo->datos['alias'] }}</strong></div>
                                    @endif
                                </div>
                                @if(!empty($metodo->datos['qr']))
                                    <img src="{{ asset('storage/' . $metodo->datos['qr']) }}" alt="QR {{ $metodo->nombre }}" class="inv-metodo-qr">
                                @endif
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="inv-error" style="margin-top:1.4rem;">Por el momento no hay medios de pago habilitados. Escríbenos por WhatsApp para coordinar tu aporte.</p>
                @endif

                <div class="inv-form-grid" style="margin-top:1.4rem;">
                    <div class="inv-campo">
                        <label for="numero_operacion">N.º de operación</label>
                        <input type="text" id="numero_operacion" name="numero_operacion" value="{{ old('numero_operacion') }}" placeholder="Opcional">
                    </div>

                    <div class="inv-campo">
                        <label for="comprobante">Adjuntar comprobante</label>
                        <input type="file" id="comprobante" name="comprobante" accept="image/*,.pdf">
                        @error('comprobante') <span class="inv-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-top:2rem;display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
                    <button type="submit" class="inv-btn">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        Enviar solicitud de inversión
                    </button>
                    <span class="inv-hint">Al enviar aceptas los términos y condiciones de MREYZE.</span>
                </div>
            </form>
        </div>
    </section>

    @if($activos->count() || $completados->count())
    <section style="margin-top:4rem;">
        <div class="section-label" style="justify-content:center;">Más oportunidades</div>
        <h2 class="section-title" style="text-align:center;">Otros proyectos <span>de inversión</span></h2>

        @if($activos->count())
            <div class="inv-otros-grid" style="margin-top:1.5rem;">
                @foreach($activos as $otro)
                    <a href="{{ route('inversiones.create', $otro) }}" class="inv-otra">
                        <div class="inv-otra-img">
                            @if($otro->imagen_principal_url)
                                <img src="{{ $otro->imagen_principal_url }}" alt="{{ $otro->titulo }}">
                            @endif
                        </div>
                        <div class="inv-otra-body">
                            <div class="inv-otra-titulo">{{ $otro->titulo }}</div>
                            <div class="inv-otra-meta">
                                <span>Financiado <strong>{{ $otro->inversion_porcentaje_financiado }}%</strong></span>
                                <span>ROI <strong>{{ $otro->inversion_roi_label }}</strong></span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($completados->count())
            <div class="inv-otros-grid" style="margin-top:1.5rem;">
                @foreach($completados as $otro)
                    <a href="{{ route('proyectos.show', $otro) }}" class="inv-otra">
                        <div class="inv-otra-img">
                            @if($otro->imagen_principal_url)
                                <img src="{{ $otro->imagen_principal_url }}" alt="{{ $otro->titulo }}">
                            @endif
                        </div>
                        <div class="inv-otra-body">
                            <div class="inv-otra-titulo">{{ $otro->titulo }}</div>
                            <div class="inv-otra-meta">
                                <span style="color:#4ade80;">✓ Completado</span>
                                <span>ROI <strong>{{ $otro->inversion_roi_label }}</strong></span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
    @endif
</div>

@endsection

@push('scripts')
<script>
(function () {
    var rango   = document.getElementById('simRango');
    var monto   = document.getElementById('simMonto');
    var base    = document.getElementById('simBase');
    var ganancia = document.getElementById('simGanancia');
    var total   = document.getElementById('simTotal');

    if (!rango) return;

    var roi = {{ $roiNum }};
    var meses = {{ $tiempo }};

    function fmt(n) {
        return 'S/ ' + n.toLocaleString('es-PE', { maximumFractionDigits: 2 });
    }

    function update() {
        var v = parseInt(rango.value, 10) || 0;
        monto.textContent = v.toLocaleString('es-PE');
        base.textContent = fmt(v);
        var gan = v * (roi / 100) * (meses / 12);
        ganancia.textContent = fmt(gan);
        total.textContent = fmt(v + gan);
    }

    rango.addEventListener('input', update);
    update();
})();
</script>
@endpush
