@extends('welcome')

@section('title', 'Contacto — MREYZE')

@section('content')
{{-- resources/views/sections/contacto.blade.php --}}
{{-- Uso: @include('sections.contacto') --}}

<style>
    /* ═══════════════════════════════════════
       CONTACTO SECTION
    ═══════════════════════════════════════ */
    .contacto {
        padding: 7rem 2rem 0;
        background: var(--ice-800);
        position: relative;
        overflow: hidden;
    }

    .contacto::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .contacto-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 70% 30%, rgba(0,212,255,0.06) 0%, transparent 50%),
            radial-gradient(ellipse at 20% 80%, rgba(30,77,153,0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .contacto-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .contacto-header {
        text-align: center;
        margin-bottom: 4rem;
    }
    .contacto-header .section-label { justify-content: center; }
    .contacto-header .section-desc { margin: 0 auto; text-align: center; }

    /* Grid principal */
    .contacto-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 3rem;
        align-items: start;
    }

    /* ── Columna izquierda: info ── */
    .contacto-info {}

    .contacto-info-title {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 1.5rem;
    }

    /* Tarjetas de contacto */
    .contacto-cards {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .contacto-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.1rem 1.3rem;
        background: rgba(10,22,40,0.5);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.28s;
        position: relative;
        overflow: hidden;
    }

    .contacto-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--card-color, var(--accent));
        border-radius: 3px;
        transform: scaleY(0);
        transition: transform 0.28s;
    }

    .contacto-card:hover {
        border-color: rgba(0,212,255,0.3);
        background: rgba(30,77,153,0.15);
        transform: translateX(5px);
    }

    .contacto-card:hover::before { transform: scaleY(1); }

    .contacto-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.25s;
    }

    .contacto-card:hover .contacto-card-icon { transform: scale(1.08); }
    .contacto-card-icon svg { width: 20px; height: 20px; }

    .contacto-card-info {}

    .contacto-card-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--ice-300);
        margin-bottom: 3px;
    }

    .contacto-card-value {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        color: #fff;
    }

    .contacto-card-sub {
        font-size: 0.72rem;
        color: var(--ice-300);
        margin-top: 2px;
    }

    /* Horario */
    .contacto-horario {
        padding: 1.2rem 1.5rem;
        background: rgba(10,22,40,0.4);
        border: 1px solid rgba(74,144,217,0.12);
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .contacto-horario-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--ice-200);
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .contacto-horario-title svg { width: 14px; height: 14px; color: var(--accent); }

    .horario-row {
        display: flex;
        justify-content: space-between;
        padding: 0.3rem 0;
        font-size: 0.8rem;
        border-bottom: 1px solid rgba(74,144,217,0.07);
    }
    .horario-row:last-child { border-bottom: none; }
    .horario-dia { color: var(--ice-200); }
    .horario-hora { color: var(--accent); font-weight: 600; font-family: 'Exo 2', sans-serif; }

    /* Redes sociales */
    .contacto-redes-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--ice-300);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }

    .contacto-redes {
        display: flex;
        gap: 0.75rem;
    }

    .red-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(10,22,40,0.5);
        border: 1px solid rgba(74,144,217,0.2);
        cursor: pointer;
        transition: all 0.25s;
        text-decoration: none;
    }
    .red-btn svg { width: 18px; height: 18px; }
    .red-btn:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,30,90,0.4); }
    .red-btn.whatsapp:hover { background: rgba(37,211,102,0.15); border-color: rgba(37,211,102,0.4); }
    .red-btn.linkedin:hover  { background: rgba(10,102,194,0.2);  border-color: rgba(10,102,194,0.5); }
    .red-btn.github:hover    { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.25); }
    .red-btn.instagram:hover { background: rgba(225,48,108,0.12);  border-color: rgba(225,48,108,0.35); }

    /* ── Columna derecha: formulario ── */
    .contacto-form-wrap {
        background: rgba(10,22,40,0.5);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .contacto-form-wrap::before {
        content: '';
        position: absolute;
        top: -1px; left: 20%; right: 20%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    .form-title {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.4rem;
    }

    .form-subtitle {
        font-size: 0.83rem;
        color: var(--ice-300);
        margin-bottom: 2rem;
    }

    /* Campos del formulario */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 1rem;
    }

    .form-group.full { grid-column: span 2; }

    .form-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--ice-200);
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .form-label span { color: var(--accent); }

    .form-input, .form-select, .form-textarea {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        background: rgba(13,31,60,0.7);
        border: 1px solid rgba(74,144,217,0.2);
        color: #fff;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.22s;
        font-family: 'Inter', sans-serif;
        width: 100%;
    }

    .form-input::placeholder, .form-textarea::placeholder { color: var(--ice-300); }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: rgba(0,212,255,0.5);
        background: rgba(13,31,60,0.9);
        box-shadow: 0 0 0 3px rgba(0,212,255,0.08);
    }

    .form-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%234a90d9' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 18px;
        padding-right: 2.5rem;
    }

    .form-select option { background: #0d1f3c; color: #fff; }

    .form-textarea {
        resize: vertical;
        min-height: 110px;
        line-height: 1.6;
    }

    /* Checkbox */
    .form-check {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 1.5rem;
    }

    .form-check input[type="checkbox"] {
        width: 16px;
        height: 16px;
        margin-top: 2px;
        accent-color: var(--accent);
        flex-shrink: 0;
        cursor: pointer;
    }

    .form-check-label {
        font-size: 0.78rem;
        color: var(--ice-300);
        line-height: 1.5;
    }

    .form-check-label a { color: var(--accent); text-decoration: none; }
    .form-check-label a:hover { text-decoration: underline; }

    /* Botón enviar */
    .form-submit {
        width: 100%;
        padding: 0.9rem;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--ice-500), var(--ice-400));
        border: 1px solid rgba(74,144,217,0.4);
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Exo 2', sans-serif;
        letter-spacing: 0.04em;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 20px rgba(30,77,153,0.4);
    }
    .form-submit svg { width: 18px; height: 18px; }
    .form-submit:hover {
        background: linear-gradient(135deg, var(--ice-400), var(--glow));
        box-shadow: 0 8px 28px rgba(59,130,246,0.55), 0 0 0 1px rgba(0,212,255,0.2);
        transform: translateY(-2px);
    }

    /* Mensaje de éxito */
    .form-success {
        display: none;
        text-align: center;
        padding: 2rem;
    }
    .form-success-icon {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: rgba(34,197,94,0.15);
        border: 1px solid rgba(34,197,94,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }
    .form-success-icon svg { width: 28px; height: 28px; color: #4ade80; }
    .form-success-title { font-family: 'Exo 2', sans-serif; font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 0.4rem; }
    .form-success-desc { font-size: 0.85rem; color: var(--ice-200); }

    @media (max-width: 900px) {
        .contacto-grid { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
        .form-group.full { grid-column: span 1; }
    }
</style>

<style>
    /* Contact layer: clear information first, form second. */
    .contacto {
        padding: clamp(4rem, 8vw, 7rem) clamp(1rem, 4vw, 2rem) 0;
        background: linear-gradient(180deg, #071522 0%, #091d30 100%);
    }
    .contacto-container { width: min(100%, 78rem); }
    .contacto-header { margin-bottom: 2.5rem; }
    .contacto-grid { gap: clamp(1.25rem, 4vw, 3rem); }
    .contacto-card, .contacto-horario, .contacto-form-wrap { border-color: rgba(130, 190, 240, .14); }
    .contacto-card { min-height: 5rem; }
    .contacto-card-icon, .red-btn { width: 44px; height: 44px; }
    .contacto-form-wrap { padding: clamp(1.15rem, 4vw, 2.5rem); border-radius: 1rem; background: rgba(8, 25, 41, .82); }
    .form-input, .form-select, .form-textarea, .form-submit { min-height: 48px; font-size: 1rem; }
    .form-submit { padding: .8rem 1rem; }
    .form-check input[type="checkbox"] { width: 20px; height: 20px; }

    @media (max-width: 900px) {
        .contacto-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 620px) {
        .form-row { grid-template-columns: 1fr; }
        .form-group.full { grid-column: auto; }
        .contacto-card-value { overflow-wrap: anywhere; }
    }
</style>

<section class="contacto" id="contacto">
    <div class="contacto-bg"></div>
    <div class="contacto-container">

        {{-- Header --}}
        <div class="contacto-header reveal">
            <div class="section-label">Hablemos</div>
            <h2 class="section-title">¿Tienes un <span>Proyecto</span>?</h2>
            <p class="section-desc">
                Escríbenos o llámanos. Respondemos en menos de 24 horas con una propuesta
                técnica personalizada para tu necesidad.
            </p>
        </div>

        <div class="contacto-grid">

            {{-- ── Info de contacto ── --}}
            <div class="contacto-info reveal">
                <div class="contacto-info-title">Medios de contacto</div>

                <div class="contacto-cards">

                    {{-- Teléfono --}}
                    <a href="tel:{{ config('empresa.telefono') }}" class="contacto-card" style="--card-color: #25D366;">
                        <div class="contacto-card-icon" style="background:rgba(37,211,102,0.12);border:1px solid rgba(37,211,102,0.3)">
                            <svg viewBox="0 0 24 24" fill="rgba(37,211,102,0.9)"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        </div>
                        <div class="contacto-card-info">
                            <div class="contacto-card-label">Teléfono</div>
                            <div class="contacto-card-value">{{ config('empresa.telefono') }}</div>
                            <div class="contacto-card-sub">{{ config('empresa.horario_linea') }}</div>
                        </div>
                    </a>

                    {{-- WhatsApp directo --}}
                    <a href="{{ config('empresa.whatsapp_url') }}?text=Hola,%20me%20interesa%20saber%20más%20sobre%20sus%20servicios" target="_blank" class="contacto-card" style="--card-color: #25D366;">
                        <div class="contacto-card-icon" style="background:rgba(37,211,102,0.12);border:1px solid rgba(37,211,102,0.3)">
                            <svg viewBox="0 0 24 24" fill="rgba(37,211,102,0.9)"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div class="contacto-card-info">
                            <div class="contacto-card-label">WhatsApp</div>
                            <div class="contacto-card-value">Mensaje directo</div>
                            <div class="contacto-card-sub">Respuesta rápida garantizada</div>
                        </div>
                    </a>

                    {{-- Email --}}
                    <a href="mailto:{{ config('empresa.email') }}" class="contacto-card" style="--card-color: var(--accent);">
                        <div class="contacto-card-icon" style="background:rgba(0,212,255,0.1);border:1px solid rgba(0,212,255,0.3)">
                            <svg viewBox="0 0 24 24" fill="rgba(0,212,255,0.9)"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div class="contacto-card-info">
                            <div class="contacto-card-label">Correo electrónico</div>
                            <div class="contacto-card-value" style="font-size:0.82rem">{{ config('empresa.email') }}</div>
                            <div class="contacto-card-sub">Respuesta en menos de 24 horas</div>
                        </div>
                    </a>

                    {{-- Ubicación --}}
                    <div class="contacto-card">
                        <div class="contacto-card-icon" style="background:rgba(99,102,241,0.12);border:1px solid rgba(99,102,241,0.3)">
                            <svg viewBox="0 0 24 24" fill="rgba(165,180,252,0.9)"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div class="contacto-card-info">
                            <div class="contacto-card-label">Ubicación</div>
                            <div class="contacto-card-value">{{ config('empresa.direccion') }}</div>
                            <div class="contacto-card-sub">Servicios presenciales y remotos</div>
                        </div>
                    </div>

                </div>

                {{-- Horario --}}
                <div class="contacto-horario">
                    <div class="contacto-horario-title">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.5 5v5.25l4.5 2.67-.75 1.23L11 13V7h1.5z"/></svg>
                        Horario de atención
                    </div>
                    <div class="horario-row"><span class="horario-dia">Lunes – Viernes</span><span class="horario-hora">8:00 – 18:00</span></div>
                    <div class="horario-row"><span class="horario-dia">Sábados y Domingos</span><span class="horario-hora" style="color:var(--ice-300)">Cerrado</span></div>
                </div>

                {{-- Redes --}}
                <div class="contacto-redes-title">Síguenos</div>
                <div class="contacto-redes">
                    <a href="{{ config('empresa.whatsapp_url') }}" target="_blank" class="red-btn whatsapp" title="WhatsApp">
                        <svg viewBox="0 0 24 24" fill="rgba(37,211,102,0.8)"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <a href="{{ config('empresa.redes.facebook') }}" target="_blank" class="red-btn" title="Facebook" style="--card-color: #1877F2;">
                        <svg viewBox="0 0 24 24" fill="rgba(24,119,242,0.9)"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                    </a>
                    <a href="{{ config('empresa.redes.tiktok') }}" target="_blank" class="red-btn" title="TikTok">
                        <svg viewBox="0 0 24 24" fill="rgba(200,200,200,0.8)"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.9 2.89 2.89 0 01-2.88-2.89 2.89 2.89 0 012.88-2.89c.3 0 .59.05.87.14v-3.5a6.37 6.37 0 00-.87-.05A6.33 6.33 0 103.9 15.8a6.33 6.33 0 005.69 3.7 6.33 6.33 0 006.33-6.33V8.96a8.27 8.27 0 004.78 1.49v-3.5a4.8 4.8 0 01-3.11-1.26z"/></svg>
                    </a>
                    <a href="{{ config('empresa.redes.linkedin') }}" target="_blank" class="red-btn linkedin" title="LinkedIn">
                        <svg viewBox="0 0 24 24" fill="rgba(10,102,194,0.9)"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="{{ config('empresa.redes.instagram') }}" target="_blank" class="red-btn instagram" title="Instagram">
                        <svg viewBox="0 0 24 24" fill="rgba(225,48,108,0.8)"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>

            </div>

            {{-- ── Formulario ── --}}
            <div class="contacto-form-wrap reveal reveal-delay-1" id="contactoFormWrap">

                <div id="contactoForm">
                    <div class="form-title">Envíanos un mensaje</div>
                    <p class="form-subtitle">Completa el formulario y te contactamos en breve con una propuesta.</p>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nombre <span>*</span></label>
                            <input type="text" class="form-input" placeholder="Tu nombre" id="f-nombre">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-input" placeholder="Nombre de tu empresa" id="f-empresa">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Correo <span>*</span></label>
                            <input type="email" class="form-input" placeholder="tu@correo.com" id="f-email">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-input" placeholder="+51 xxx xxx xxx" id="f-telefono">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Servicio de interés <span>*</span></label>
                        <select class="form-select" id="f-servicio">
                            <option value="" disabled selected>Selecciona un servicio...</option>
                            <option value="automatizacion">Automatización y Control Industrial</option>
                            <option value="iot">IoT & Electrónica Embebida</option>
                            <option value="software">Desarrollo de Software</option>
                            <option value="consultoria">Consultoría & Soporte</option>
                            <option value="tienda">Compra de productos</option>
                            <option value="otro">Otro / No estoy seguro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mensaje <span>*</span></label>
                        <textarea class="form-textarea" placeholder="Cuéntanos sobre tu proyecto, necesidad o consulta..." id="f-mensaje"></textarea>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="f-privacidad">
                        <label class="form-check-label" for="f-privacidad">
                            Acepto la <a href="#">política de privacidad</a> y autorizo el uso de mis datos para recibir una respuesta a mi consulta.
                        </label>
                    </div>

                    <button class="form-submit" onclick="enviarFormulario()">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        Enviar Mensaje
                    </button>
                </div>

                <div class="form-success" id="formSuccess">
                    <div class="form-success-icon">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </div>
                    <div class="form-success-title">¡Mensaje recibido correctamente!</div>
                    <p class="form-success-desc">Gracias por contactar con MReyze Automatización y Sistemas S.A.C.<br><br>Nuestro equipo ha recibido tu solicitud y uno de nuestros ejecutivos revisará la información proporcionada. Nos pondremos en contacto contigo para conocer mejor tu necesidad y brindarte una propuesta técnica personalizada.<br><br>Te responderemos en un plazo máximo de 24 horas hábiles.</p>
                </div>

            </div>
        </div>
    </div>
</section>
 <script>
 function enviarFormulario() {
     const nombre     = document.getElementById('f-nombre').value.trim();
     const empresa    = document.getElementById('f-empresa').value.trim();
     const email      = document.getElementById('f-email').value.trim();
     const telefono   = document.getElementById('f-telefono').value.trim();
     const servicio   = document.getElementById('f-servicio').value;
     const mensaje    = document.getElementById('f-mensaje').value.trim();
     const privacidad = document.getElementById('f-privacidad').checked;

     if (!nombre || !email || !servicio || !mensaje) {
         alert('Por favor completa los campos obligatorios (*).');
         return;
     }
     if (!privacidad) {
         alert('Debes aceptar la política de privacidad para continuar.');
         return;
     }

     const btn = document.querySelector('.form-submit');
     if (btn) { btn.disabled = true; btn.textContent = 'Enviando...'; }

     fetch('/api/contacto', {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
             'Accept': 'application/json',
             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
         },
         body: JSON.stringify({ nombre, empresa, email, telefono, servicio, mensaje })
     })
     .then(res => res.json())
     .then(data => {
         if (data.ok) {
             document.getElementById('contactoForm').style.display = 'none';
             document.getElementById('formSuccess').style.display = 'block';
         } else {
             alert(data.error || 'Ocurrió un error. Inténtalo de nuevo.');
             if (btn) { btn.disabled = false; btn.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg> Enviar Mensaje'; }
         }
     })
     .catch(err => {
         console.error('ERROR REAL:', err);
         alert('No se pudo enviar el mensaje. Inténtalo de nuevo.');
         if (btn) { btn.disabled = false; btn.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg> Enviar Mensaje'; }
     });
 }
 </script>
@endsection