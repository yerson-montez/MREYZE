@extends('welcome')

@section('title', 'Consultoría & Soporte — MREYZE')

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
            radial-gradient(ellipse at 20% 60%, rgba(99,102,241,0.08) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 40%, rgba(37,99,199,0.06) 0%, transparent 50%);
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
        background: rgba(99,102,241,0.1);
        border: 1px solid rgba(99,102,241,0.2);
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
        <div class="section-label">Software</div>
        <h1>Consultoría & <span>Soporte</span></h1>
        <p>Asesoramos en la transformación digital de tu empresa. Diagnóstico tecnológico, optimización de procesos y soporte técnico continuo post-implementación.</p>
    </div>
</div>

<div class="service-detail">
    <div class="service-detail-inner">
        <div class="detail-grid">

            <div class="detail-card reveal">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    ¿Qué ofrecemos?
                </h3>
                <ul>
                    <li>Diagnóstico tecnológico integral de tu empresa</li>
                    <li>Plan de transformación digital personalizado</li>
                    <li>Asesoría en selección e implementación de tecnología</li>
                    <li>Optimización de procesos operativos y productivos</li>
                    <li>Seguridad informática y protección de datos</li>
                    <li>Capacitación técnica para tu equipo</li>
                    <li>Soporte técnico remoto y presencial</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-1">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"/></svg>
                    Servicios de consultoría
                </h3>
                <ul>
                    <li>Auditoría de infraestructura tecnológica</li>
                    <li>Evaluación de sistemas y software existente</li>
                    <li>Roadmap de digitalización a corto, mediano y largo plazo</li>
                    <li>Análisis de ROI en inversiones tecnológicas</li>
                    <li>Selección de proveedores y soluciones</li>
                    <li>Gestión del cambio y adopción tecnológica</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-2">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/></svg>
                    Soporte técnico
                </h3>
                <ul>
                    <li>Mesa de ayuda con respuesta en menos de 4 horas</li>
                    <li>Soporte remoto y presencial en Trujillo y Lima</li>
                    <li>Mantenimiento de sistemas y aplicaciones</li>
                    <li>Monitoreo proactivo de infraestructura</li>
                    <li>Backups y recuperación ante desastres</li>
                    <li>Actualizaciones y parches de seguridad</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-3">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    ¿Por qué elegirnos?
                </h3>
                <ul>
                    <li>+8 años de experiencia en el sector tecnológico</li>
                    <li>Equipo multidisciplinario (industria + software)</li>
                    <li>Enfoque práctico orientado a resultados</li>
                    <li>Soporte en español con comunicación directa</li>
                    <li>Planes flexibles adaptados a tu presupuesto</li>
                    <li>Acompañamiento continuo post-consultoría</li>
                </ul>
            </div>

        </div>
    </div>
</div>

<div class="cta-section">
    <h2>¿Necesitas asesoría tecnológica?</h2>
    <p>Agenda una consulta gratuita con nuestro equipo de expertos.</p>
    <a href="{{ url('/contacto') }}" class="hero-btn-primary">
        <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        Agendar Consulta
    </a>
</div>

@endsection
