@extends('welcome')

@section('title', 'Desarrollo de Software — MREYZE')

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
            radial-gradient(ellipse at 20% 60%, rgba(20,184,166,0.08) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 40%, rgba(99,102,241,0.06) 0%, transparent 50%);
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
        background: rgba(20,184,166,0.1);
        border: 1px solid rgba(20,184,166,0.2);
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
        <h1>Desarrollo de <span>Software</span></h1>
        <p>Creamos aplicaciones web, sistemas empresariales y APIs a medida. Desde una landing page hasta un sistema de gestión completo con panel administrativo.</p>
    </div>
</div>

<div class="service-detail">
    <div class="service-detail-inner">
        <div class="detail-grid">

            <div class="detail-card reveal">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                    ¿Qué ofrecemos?
                </h3>
                <ul>
                    <li>Aplicaciones web responsivas con Laravel, React y Vue</li>
                    <li>Sistemas de gestión empresarial (ERP, CRM, inventarios)</li>
                    <li>APIs RESTful y GraphQL para integración de sistemas</li>
                    <li>Paneles administrativos con dashboards interactivos</li>
                    <li>E-commerce y plataformas de venta online</li>
                    <li>Automatización de procesos con software a medida</li>
                    <li>Migración y modernización de sistemas legacy</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-1">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    Metodología
                </h3>
                <ul>
                    <li>Análisis de requerimientos y alcance del proyecto</li>
                    <li>Diseño de arquitectura escalable y modular</li>
                    <li>Desarrollo ágil con entregas iterativas (Scrum)</li>
                    <li>Pruebas automatizadas y control de calidad</li>
                    <li>Despliegue en servidores cloud (AWS, DigitalOcean)</li>
                    <li>Documentación técnica y manuales de usuario</li>
                    <li>Mantenimiento y soporte post-lanzamiento</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-2">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    Proyectos típicos
                </h3>
                <ul>
                    <li>Sistema de gestión de inventarios y almacenes</li>
                    <li>Portal de ventas con carrito de compras y pagos</li>
                    <li>Plataforma de reservas y citas online</li>
                    <li>Dashboard de indicadores y reportes ejecutivos</li>
                    <li>Sistema de seguimiento de proyectos y tareas</li>
                    <li>Aplicación móvil híbrida (Flutter / React Native)</li>
                </ul>
            </div>

            <div class="detail-card reveal reveal-delay-3">
                <h3>
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                    Stack tecnológico
                </h3>
                <div class="tech-stack">
                    <span>PHP / Laravel</span>
                    <span>JavaScript / TypeScript</span>
                    <span>React</span>
                    <span>Vue.js</span>
                    <span>Node.js</span>
                    <span>MySQL / PostgreSQL</span>
                    <span>MongoDB</span>
                    <span>Docker</span>
                    <span>AWS</span>
                    <span>Git</span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="cta-section">
    <h2>¿Tienes un proyecto de software en mente?</h2>
    <p>Hablemos y convirtamos tu idea en una solución funcional.</p>
    <a href="{{ url('/contacto') }}" class="hero-btn-primary">
        <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        Solicitar Cotización
    </a>
</div>

@endsection
