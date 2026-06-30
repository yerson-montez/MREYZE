@extends('welcome')

@section('title', 'Automatización Industrial — MREYZE')

@section('content')

<style>
    .page-header {
        padding: 6rem 2rem 3rem;
        background: var(--ice-800);
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }
    .page-header-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 20% 60%, rgba(37,99,199,0.1) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 40%, rgba(0,212,255,0.06) 0%, transparent 50%);
        pointer-events: none;
    }
    .page-header-inner {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }
    .page-header .section-label { margin-bottom: 0.75rem; }
    .page-header h1 {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(2rem, 4vw, 3.2rem);
        font-weight: 700;
        color: #fff;
        line-height: 1.15;
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
    }
    .page-header h1 span {
        background: linear-gradient(135deg, var(--ice-200), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .page-header p {
        font-size: 1.05rem;
        color: var(--ice-200);
        line-height: 1.7;
        max-width: 640px;
    }

    .service-detail {
        padding: 4rem 2rem 6rem;
        background: var(--ice-900);
    }
    .service-detail-inner {
        max-width: 1280px;
        margin: 0 auto;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: start;
    }
    .detail-card {
        padding: 2rem;
        background: rgba(10,22,40,0.6);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 18px;
        transition: all 0.35s;
    }
    .detail-card:hover {
        border-color: rgba(0,212,255,0.35);
        transform: translateY(-5px);
        box-shadow: 0 16px 48px rgba(0,30,90,0.4);
    }
    .detail-card h3 {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .detail-card h3 svg {
        width: 22px; height: 22px;
        color: var(--accent);
        flex-shrink: 0;
    }
    .detail-card p {
        font-size: 0.9rem;
        color: var(--ice-200);
        line-height: 1.7;
    }
    .detail-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .detail-card ul li {
        padding: 0.5rem 0;
        font-size: 0.9rem;
        color: var(--ice-100);
        border-bottom: 1px solid rgba(74,144,217,0.08);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .detail-card ul li::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: var(--accent);
        flex-shrink: 0;
    }
    .detail-card ul li:last-child { border-bottom: none; }

    .tech-stack {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 1.5rem;
    }
    .tech-stack span {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        background: rgba(37,99,199,0.15);
        border: 1px solid rgba(74,144,217,0.2);
        color: var(--ice-200);
    }

    .cta-section {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--ice-800);
        border-top: 1px solid rgba(74,144,217,0.15);
    }
    .cta-section h2 {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(1.5rem, 2.5vw, 2rem);
        color: #fff;
        margin-bottom: 0.75rem;
    }
    .cta-section p {
        color: var(--ice-200);
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="page-header">
    <div class="page-header-bg"></div>
    <div class="page-header-inner reveal">
        <div class="section-label">Industria</div>
        <h1>Automatización <span>Industrial</span></h1>
        <p>Diseñamos e implementamos sistemas de automatización completos para procesos industriales. Optimizamos tus operaciones reduciendo tiempos, costos y mejorando la calidad.</p>
    </div>
</div>

<div class="service-detail">
    <div class="service-detail-inner">
        <div class="detail-grid">

            <div class="detail-card reveal">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                    ¿Qué ofrecemos?
                </h3>
                <ul>
                    <li>Diseño y fabricación de tableros eléctricos de control</li>
                    <li>Programación de PLC (Siemens, Allen-Bradley, Delta)</li>
                    <li>Sistemas SCADA para monitoreo en tiempo real</li>
                    <li>Control de procesos industriales (PID, lógica ladder)</li>
                    <li>Integración de sistemas y redes industriales</li>
                    <li>Mantenimiento preventivo y correctivo</li>
                    <li>Automatización de líneas de producción</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-1">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    Beneficios
                </h3>
                <ul>
                    <li>Reducción de costos operativos hasta un 40%</li>
                    <li>Mayor precisión y calidad en los procesos</li>
                    <li>Monitoreo remoto y toma de decisiones en tiempo real</li>
                    <li>Menor tiempo de inactividad no planificada</li>
                    <li>Escalabilidad para crecimiento futuro</li>
                    <li>Reportes automáticos de producción</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-2">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/></svg>
                    Sectores
                </h3>
                <ul>
                    <li>Industria manufacturera</li>
                    <li>Agroindustria y procesos alimenticios</li>
                    <li>Minería y metalurgia</li>
                    <li>Plantas de tratamiento de agua</li>
                    <li>Energía y generación eléctrica</li>
                    <li>Logística y almacenes automatizados</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-3">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                    Tecnologías
                </h3>
                <div class="tech-stack">
                    <span>Siemens S7</span>
                    <span>Allen-Bradley</span>
                    <span>Delta PLC</span>
                    <span>WinCC SCADA</span>
                    <span>Ignition</span>
                    <span>TIA Portal</span>
                    <span>RSLogix</span>
                    <span>Modbus</span>
                    <span>Profibus</span>
                    <span>EtherNet/IP</span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="cta-section">
    <h2>¿Necesitas automatizar tu planta?</h2>
    <p>Contáctanos para una evaluación técnica gratuita de tu proceso industrial.</p>
    <a href="{{ url('/contacto') }}" class="hero-btn-primary">
        <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        Solicitar Asesoría
    </a>
</div>

@endsection
