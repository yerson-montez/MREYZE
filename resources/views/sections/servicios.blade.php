@extends('welcome')

@section('title', 'Servicios — MREYZE')

@section('content')


{{-- resources/views/sections/servicios.blade.php --}}
{{-- Uso: @include('sections.servicios') --}}

<style>
    /* ═══════════════════════════════════════
       SERVICIOS SECTION
    ═══════════════════════════════════════ */
    .servicios {
        padding: 7rem 2rem;
        background: var(--ice-800);
        position: relative;
        overflow: hidden;
    }

    .servicios::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .servicios-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 10% 80%, rgba(0,212,255,0.06) 0%, transparent 50%),
            radial-gradient(ellipse at 90% 20%, rgba(30,77,153,0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .servicios-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .servicios-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .servicios-header .section-label { justify-content: center; }

    .servicios-header .section-desc {
        margin: 0 auto;
        text-align: center;
    }

    /* Grid de servicios */
    .servicios-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    /* Tarjeta de servicio */
    .service-card {
        position: relative;
        padding: 2rem;
        background: rgba(10,22,40,0.6);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 18px;
        transition: all 0.35s;
        overflow: hidden;
        cursor: default;
    }

    /* Tarjeta grande (ocupa 2 columnas) */
    .service-card.featured {
        grid-column: span 2;
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 2rem;
        align-items: start;
    }

    .service-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(30,77,153,0.0), rgba(0,212,255,0.0));
        transition: all 0.35s;
    }

    .service-card:hover {
        border-color: rgba(0,212,255,0.35);
        transform: translateY(-5px);
        box-shadow: 0 16px 48px rgba(0,30,90,0.4), 0 0 0 1px rgba(0,212,255,0.1);
    }

    .service-card:hover::before {
        background: linear-gradient(135deg, rgba(30,77,153,0.15), rgba(0,212,255,0.05));
    }

    /* Icono del servicio */
    .service-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-bottom: 1.5rem;
        position: relative;
        transition: all 0.3s;
    }

    .service-card.featured .service-icon-wrap {
        width: 64px;
        height: 64px;
        margin-bottom: 0;
    }

    .service-card:hover .service-icon-wrap {
        transform: scale(1.08);
    }

    .service-icon-wrap svg { width: 26px; height: 26px; }
    .service-card.featured .service-icon-wrap svg { width: 30px; height: 30px; }

    /* Colores por servicio */
    .service-icon-wrap.blue {
        background: rgba(30,77,153,0.25);
        border: 1px solid rgba(37,99,199,0.4);
        box-shadow: 0 0 20px rgba(37,99,199,0.2);
    }
    .service-icon-wrap.blue svg { color: var(--ice-200); }

    .service-icon-wrap.cyan {
        background: rgba(0,212,255,0.1);
        border: 1px solid rgba(0,212,255,0.3);
        box-shadow: 0 0 20px rgba(0,212,255,0.15);
    }
    .service-icon-wrap.cyan svg { color: var(--accent); }

    .service-icon-wrap.teal {
        background: rgba(20,184,166,0.1);
        border: 1px solid rgba(20,184,166,0.3);
        box-shadow: 0 0 20px rgba(20,184,166,0.15);
    }
    .service-icon-wrap.teal svg { color: #2dd4bf; }

    .service-icon-wrap.indigo {
        background: rgba(99,102,241,0.1);
        border: 1px solid rgba(99,102,241,0.3);
        box-shadow: 0 0 20px rgba(99,102,241,0.15);
    }
    .service-icon-wrap.indigo svg { color: #a5b4fc; }

    .service-body {}

    .service-name {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.6rem;
        letter-spacing: -0.01em;
    }

    .service-desc {
        font-size: 0.875rem;
        color: var(--ice-200);
        line-height: 1.7;
        margin-bottom: 1.2rem;
    }

    /* Lista de items del servicio */
    .service-items {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .service-item-tag {
        font-size: 0.73rem;
        padding: 4px 10px;
        border-radius: 20px;
        background: rgba(37,99,199,0.15);
        border: 1px solid rgba(74,144,217,0.2);
        color: var(--ice-200);
        font-weight: 500;
        transition: all 0.2s;
    }

    .service-card:hover .service-item-tag {
        background: rgba(37,99,199,0.25);
        border-color: rgba(74,144,217,0.35);
        color: var(--ice-100);
    }

    /* Flecha de enlace */
    .service-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 1.2rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--accent);
        text-decoration: none;
        opacity: 0;
        transform: translateX(-8px);
        transition: all 0.3s;
    }

    .service-link svg { width: 14px; height: 14px; transition: transform 0.2s; }

    .service-card:hover .service-link,
    .service-card:focus-within .service-link {
        opacity: 1;
        transform: translateX(0);
    }

    .service-card:hover .service-link:hover svg {
        transform: translateX(4px);
    }

    /* CTA al final */
    .servicios-cta {
        text-align: center;
        margin-top: 3.5rem;
    }

    .servicios-cta-text {
        font-size: 1.05rem;
        color: var(--ice-200);
        margin-bottom: 1.5rem;
    }

    .servicios-cta-text strong { color: #fff; }

    @media (max-width: 900px) {
        .servicios-grid { grid-template-columns: 1fr; }
        .service-card.featured {
            grid-column: span 1;
            grid-template-columns: 1fr;
        }
        .service-card.featured .service-icon-wrap { margin-bottom: 1.5rem; }
    }

    /* Service catalog layer: category-led, scannable and consistent with MREYZE UI. */
    .servicios {
        padding: clamp(4rem, 8vw, 7rem) clamp(1rem, 4vw, 2rem);
        background:
            radial-gradient(circle at 12% 10%, rgba(20, 216, 255, .08), transparent 28%),
            linear-gradient(180deg, #071522 0%, #091d30 100%);
    }

    .servicios-container { width: min(100%, 78rem); }
    .servicios-header {
        position: relative;
        isolation: isolate;
        padding: clamp(2rem, 5vw, 4rem) 1rem;
        border: 1px solid rgba(130, 190, 240, .12);
        border-radius: 1rem;
        overflow: hidden;
        background-image: linear-gradient(90deg, rgba(7, 21, 34, .95), rgba(7, 21, 34, .7)), url('/img/fondo2.png');
        background-size: cover;
        background-position: 62% bottom;
    }
    .servicios-header { margin-bottom: 2rem; }
    .servicios-header .section-title { font-size: clamp(2.5rem, 6vw, 5rem); }
    .servicios-header .section-desc { max-width: 46rem; }

    .servicios-nav {
        display: flex;
        flex-wrap: wrap;
        gap: .55rem;
        justify-content: center;
        margin: 0 auto clamp(2.5rem, 6vw, 4rem);
    }

    .servicios-nav a {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        min-height: 44px;
        padding: .6rem .85rem;
        border: 1px solid rgba(130, 190, 240, .15);
        border-radius: .7rem;
        color: #b7cde0;
        background: rgba(8, 25, 41, .7);
        text-decoration: none;
        font-size: .78rem;
        font-weight: 650;
    }

    .servicios-nav a strong { color: #8ce4ff; font-family: 'Exo 2', sans-serif; }
    .servicios-nav a:hover { color: #f4fbff; border-color: rgba(125, 226, 255, .4); }

    .servicios-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .service-card {
        min-width: 0;
        padding: clamp(1.25rem, 3vw, 2rem);
        border-radius: 1rem;
        background: rgba(8, 25, 41, .78);
        border-color: rgba(130, 190, 240, .14);
    }

    .service-card.featured { grid-template-columns: auto 1fr; }
    .service-card:hover { transform: translateY(-3px); box-shadow: 0 20px 42px rgba(0, 0, 0, .18); }
    .service-icon-wrap { width: 3.25rem; height: 3.25rem; margin-bottom: 1rem; }
    .service-card.featured .service-icon-wrap { width: 4rem; height: 4rem; }
    .service-name { font-size: clamp(1.15rem, 2vw, 1.45rem); }
    .service-desc { max-width: 62ch; color: #b4c8da; }
    .service-items { gap: .45rem; }
    .service-item-tag { padding: .42rem .7rem; }
    .service-link { min-height: 44px; opacity: 1; transform: none; }

    @media (max-width: 900px) {
        .servicios-grid { grid-template-columns: 1fr 1fr; }
        .service-card.featured { grid-column: 1 / -1; }
    }

    @media (max-width: 620px) {
        .servicios-header {
            position: relative;
            isolation: isolate;
            padding: 2rem 1rem;
            border: 1px solid rgba(130, 190, 240, .12);
            border-radius: 1rem;
            overflow: hidden;
            background-image: linear-gradient(180deg, rgba(7, 21, 34, .84), rgba(7, 21, 34, .7)), url('/img/fondo2.png');
            background-size: cover;
            background-position: 62% bottom;
        }
        .servicios-nav { justify-content: flex-start; flex-wrap: nowrap; overflow-x: auto; padding-bottom: .3rem; }
        .servicios-nav a { flex: 0 0 auto; }
        .servicios-grid { grid-template-columns: 1fr; }
        .service-card.featured { grid-template-columns: 1fr; }
        .service-card.featured .service-icon-wrap { margin-bottom: 1rem; }
        .service-link { width: 100%; justify-content: center; }
    }
</style>

<section class="servicios" id="servicios">
    <div class="servicios-bg"></div>
    <div class="servicios-container">

        {{-- Header --}}
        <div class="servicios-header reveal">
            <div class="section-label">Lo que hacemos</div>
            <h2 class="section-title">Nuestros <span>Servicios</span></h2>
            <p class="section-desc">
                Ofrecemos soluciones completas desde el hardware industrial hasta el software empresarial,
                cubriendo todo el espectro tecnológico que tu empresa necesita.
            </p>
        </div>

        <nav class="servicios-nav reveal" aria-label="Categorías de servicios">
            <a href="#servicio-automatizacion"><strong>01</strong> Automatización</a>
            <a href="#servicio-iot"><strong>02</strong> IoT y embebidos</a>
            <a href="#servicio-software"><strong>03</strong> Software</a>
            <a href="#servicio-consultoria"><strong>04</strong> Consultoría</a>
        </nav>

        {{-- Grid de servicios --}}
        <div class="servicios-grid">

            {{-- Automatización (featured) --}}
            <div class="service-card featured reveal" id="servicio-automatizacion">
                <div class="service-icon-wrap blue">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                </div>
                <div class="service-body">
                    <div class="service-name">Automatización y Control Industrial</div>
                    <p class="service-desc">
                        Diseñamos e implementamos sistemas de automatización completos para procesos industriales.
                        Desde tableros de control hasta sistemas SCADA, optimizamos tus operaciones reduciendo
                        tiempos y costos operativos.
                    </p>
                    <div class="service-items">
                        <span class="service-item-tag">Diseño de Tableros Eléctricos</span>
                        <span class="service-item-tag">Programación PLC</span>
                        <span class="service-item-tag">Sistemas SCADA</span>
                        <span class="service-item-tag">Control de Procesos</span>
                        <span class="service-item-tag">Integración Industrial</span>
                        <span class="service-item-tag">Mantenimiento Preventivo</span>
                    </div>
                    <a href="{{ url('/servicios/automatizacion') }}" class="service-link">
                        Ver más detalles
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
                    </a>
                </div>
            </div>

            {{-- IoT --}}
            <div class="service-card reveal reveal-delay-1" id="servicio-iot">
                <div class="service-icon-wrap cyan">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z"/></svg>
                </div>
                <div class="service-name">IoT & Electrónica Embebida</div>
                <p class="service-desc">
                    Conectamos el mundo físico con el digital. Diseñamos dispositivos inteligentes
                    con ESP32, Arduino y Raspberry Pi para monitoreo remoto y control vía web.
                </p>
                <div class="service-items">
                    <span class="service-item-tag">Sensores Inteligentes</span>
                    <span class="service-item-tag">ESP32 / Arduino</span>
                    <span class="service-item-tag">Raspberry Pi</span>
                    <span class="service-item-tag">Dashboards IoT</span>
                    <span class="service-item-tag">Monitoreo Remoto</span>
                </div>
                <a href="{{ url('/servicios/iot') }}" class="service-link">
                    Ver más detalles
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
                </a>
            </div>

            {{-- Desarrollo de Software --}}
            <div class="service-card reveal reveal-delay-2" id="servicio-software">
                <div class="service-icon-wrap teal">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                </div>
                <div class="service-name">Desarrollo de Software</div>
                <p class="service-desc">
                    Creamos aplicaciones web, sistemas empresariales y APIs a medida.
                    Desde una landing page hasta un sistema de gestión completo con panel admin.
                </p>
                <div class="service-items">
                    <span class="service-item-tag">Aplicaciones Web</span>
                    <span class="service-item-tag">Sistemas a Medida</span>
                    <span class="service-item-tag">APIs REST</span>
                    <span class="service-item-tag">Panel Admin</span>
                    <span class="service-item-tag">Laravel / React</span>
                </div>
                <a href="{{ url('/servicios/desarrollo') }}" class="service-link">
                    Ver más detalles
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
                </a>
            </div>

            {{-- Consultoría --}}
            <div class="service-card reveal reveal-delay-3" id="servicio-consultoria">
                <div class="service-icon-wrap indigo">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                </div>
                <div class="service-name">Consultoría & Soporte</div>
                <p class="service-desc">
                    Asesoramos en la transformación digital de tu empresa. Diagnóstico tecnológico,
                    optimización de procesos y soporte técnico continuo post-implementación.
                </p>
                <div class="service-items">
                    <span class="service-item-tag">Diagnóstico Tecnológico</span>
                    <span class="service-item-tag">Migración Digital</span>
                    <span class="service-item-tag">Soporte Técnico</span>
                    <span class="service-item-tag">Capacitación</span>
                </div>
                <a href="{{ url('/servicios/consultoria') }}" class="service-link">
                    Ver más detalles
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
                </a>
            </div>

        </div>

        {{-- CTA --}}
        <div class="servicios-cta reveal">
            <p class="servicios-cta-text">
                ¿No sabes cuál servicio necesitas? <strong>Te ayudamos a identificarlo.</strong>
            </p>
            <a href="{{ url('/contacto') }}" class="hero-btn-primary" style="display:inline-flex;">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                Solicitar Asesoría Gratuita
            </a>
        </div>

    </div>
</section>

@endsection