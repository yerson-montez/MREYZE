@extends('welcome')

@section('title', 'Proyectos — MREYZE')

@section('content')

<style>
    /* ═══════════════════════════════════════
       PROYECTOS SECTION
    ═══════════════════════════════════════ */
    .proyectos {
        padding: 7rem 2rem;
        background: var(--ice-900);
        position: relative;
        overflow: hidden;
    }

    .proyectos::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .proyectos-bg {
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 50% 100%, rgba(30,77,153,0.12) 0%, transparent 60%);
        pointer-events: none;
    }

    .proyectos-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .proyectos-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .proyectos-ver-todos {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.3rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--accent);
        border: 1px solid rgba(0,212,255,0.3);
        text-decoration: none;
        transition: all 0.25s;
        white-space: nowrap;
    }

    .proyectos-ver-todos svg { width: 15px; height: 15px; transition: transform 0.2s; }
    .proyectos-ver-todos:hover { background: rgba(0,212,255,0.1); border-color: rgba(0,212,255,0.5); }
    .proyectos-ver-todos:hover svg { transform: translateX(4px); }

    /* Filtros */
    .proyectos-filtros {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .filtro-btn {
        padding: 0.45rem 1.1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid rgba(74,144,217,0.2);
        background: transparent;
        color: var(--ice-300);
        transition: all 0.22s;
        letter-spacing: 0.04em;
    }

    .filtro-btn:hover { border-color: rgba(74,144,217,0.5); color: var(--ice-100); }
    .filtro-btn.active { background: rgba(37,99,199,0.3); border-color: rgba(0,212,255,0.5); color: var(--accent); }

    /* Grid de proyectos culminados */
    .proyectos-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .proyecto-card.destacado { grid-column: span 2; }

    .proyecto-card {
        position: relative;
        background: rgba(13,31,60,0.6);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.35s;
        cursor: default;
    }

    .proyecto-card:hover {
        border-color: rgba(0,212,255,0.3);
        transform: translateY(-6px);
        box-shadow: 0 20px 56px rgba(0,30,90,0.45), 0 0 0 1px rgba(0,212,255,0.08);
    }

    .proyecto-preview {
        height: 180px;
        background: rgba(10,22,40,0.8);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .proyecto-card.destacado .proyecto-preview { height: 220px; }

    .proyecto-preview-bg {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        opacity: 0.06;
        transition: all 0.4s;
    }

    .proyecto-card:hover .proyecto-preview-bg { opacity: 0.1; transform: scale(1.1); }

    .proyecto-preview-icon {
        position: relative;
        z-index: 2;
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .proyecto-preview-icon svg { width: 32px; height: 32px; }
    .proyecto-card:hover .proyecto-preview-icon { transform: scale(1.12); }

    .proyecto-tags {
        position: absolute;
        bottom: 12px;
        left: 12px;
        display: flex;
        gap: 6px;
    }

    .proyecto-tag {
        font-size: 0.68rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .proyecto-tag.iot    { background: rgba(0,212,255,0.15);  color: var(--accent);  border: 1px solid rgba(0,212,255,0.3); }
    .proyecto-tag.auto   { background: rgba(37,99,199,0.2);   color: var(--ice-200); border: 1px solid rgba(37,99,199,0.4); }
    .proyecto-tag.web    { background: rgba(20,184,166,0.15); color: #2dd4bf;        border: 1px solid rgba(20,184,166,0.3); }
    .proyecto-tag.mobile { background: rgba(99,102,241,0.15); color: #a5b4fc;        border: 1px solid rgba(99,102,241,0.3); }

    .proyecto-status {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 0.68rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .proyecto-status.completado { background: rgba(34,197,94,0.15);  border: 1px solid rgba(34,197,94,0.3);  color: #4ade80; }
    .proyecto-status.en-curso   { background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3); color: #fbbf24; }

    .proyecto-status-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s infinite;
    }

    .proyecto-body { padding: 1.5rem; }

    .proyecto-nombre {
        font-family: 'Exo 2', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
        letter-spacing: -0.01em;
    }

    .proyecto-card.destacado .proyecto-nombre { font-size: 1.15rem; }

    .proyecto-desc {
        font-size: 0.83rem;
        color: var(--ice-200);
        line-height: 1.65;
        margin-bottom: 1.2rem;
    }

    .proyecto-metrics {
        display: flex;
        gap: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(74,144,217,0.1);
    }

    .proyecto-metric-val {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
    }

    .proyecto-metric-val span { color: var(--accent); }

    .proyecto-metric-label {
        font-size: 0.72rem;
        color: var(--ice-300);
        margin-top: 2px;
    }

    /* ═══════════════════════════════════════
       DIVISOR
    ═══════════════════════════════════════ */
    .seccion-divisor {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin: 5rem 0 4rem;
    }

    .seccion-divisor-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .seccion-divisor-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0.5rem 1.2rem;
        border-radius: 30px;
        background: rgba(13,31,60,0.8);
        border: 1px solid rgba(251,191,36,0.35);
        font-size: 0.78rem;
        font-weight: 700;
        color: #fbbf24;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    /* ═══════════════════════════════════════
       SECCIÓN INVERTIR — CABECERA
    ═══════════════════════════════════════ */
    .invertir-header {
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .invertir-subtitulo {
        font-size: 0.9rem;
        color: var(--ice-200);
        max-width: 600px;
        margin: 0 auto 3rem;
        text-align: center;
        line-height: 1.7;
    }

    /* ═══════════════════════════════════════
       TARJETAS DE PROYECTOS DE INVERSIÓN
    ═══════════════════════════════════════ */
    .inversion-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .inversion-card {
        background: rgba(13,31,60,0.65);
        border: 1px solid rgba(74,144,217,0.18);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.35s;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .inversion-card:hover {
        border-color: rgba(251,191,36,0.4);
        transform: translateY(-6px);
        box-shadow: 0 22px 60px rgba(0,20,70,0.5), 0 0 0 1px rgba(251,191,36,0.08);
    }

    /* Badge de oportunidad destacada */
    .inversion-card.destacada {
        border-color: rgba(251,191,36,0.3);
        background: rgba(20,40,80,0.7);
    }

    .inversion-badge-top {
        position: absolute;
        top: 14px;
        right: 14px;
        font-size: 0.65rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        z-index: 3;
    }

    .inversion-badge-top.abierto    { background: rgba(34,197,94,0.2);  border: 1px solid rgba(34,197,94,0.4);  color: #4ade80; }
    .inversion-badge-top.pronto     { background: rgba(251,191,36,0.2); border: 1px solid rgba(251,191,36,0.4); color: #fbbf24; }
    .inversion-badge-top.destacado  { background: rgba(0,212,255,0.15); border: 1px solid rgba(0,212,255,0.4);  color: var(--accent); }

    /* Cabecera visual de la tarjeta */
    .inversion-card-header {
        height: 160px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.5rem;
    }

    .inversion-card-header-bg {
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .inversion-card-icon-wrap {
        position: relative;
        z-index: 2;
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
    }

    .inversion-card:hover .inversion-card-icon-wrap { transform: scale(1.1); }

    .inversion-card-icon-wrap svg { width: 28px; height: 28px; }

    .inversion-card-categoria {
        position: relative;
        z-index: 2;
        font-size: 0.68rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    /* Cuerpo */
    .inversion-card-body {
        padding: 1.4rem 1.4rem 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .inversion-card-nombre {
        font-family: 'Exo 2', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
    }

    .inversion-card-desc {
        font-size: 0.8rem;
        color: var(--ice-200);
        line-height: 1.6;
        flex: 1;
    }

    /* Barra de financiamiento */
    .inversion-financiamiento {
        margin-top: 0.6rem;
    }

    .inversion-financiamiento-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        font-size: 0.72rem;
        color: var(--ice-300);
    }

    .inversion-financiamiento-header strong {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.85rem;
        color: #fff;
        font-weight: 700;
    }

    .inversion-barra-bg {
        height: 6px;
        border-radius: 10px;
        background: rgba(74,144,217,0.15);
        overflow: hidden;
    }

    .inversion-barra-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 1.2s ease;
    }

    /* Datos clave */
    .inversion-datos {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
        padding: 1rem 0;
        border-top: 1px solid rgba(74,144,217,0.1);
        margin-top: 0.8rem;
    }

    .inversion-dato-val {
        font-family: 'Exo 2', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
    }

    .inversion-dato-val span { color: var(--accent); }
    .inversion-dato-val.amarillo span { color: #fbbf24; }

    .inversion-dato-label {
        font-size: 0.7rem;
        color: var(--ice-300);
        margin-top: 1px;
    }

    /* Footer de la tarjeta */
    .inversion-card-footer {
        padding: 1rem 1.4rem 1.4rem;
    }

    .btn-invertir {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 0.7rem 1rem;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        color: #fff;
        border: none;
        cursor: pointer;
        transition: all 0.25s;
        box-shadow: 0 6px 18px rgba(0,136,204,0.3);
        letter-spacing: 0.02em;
    }

    .btn-invertir:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0,136,204,0.5);
        background: linear-gradient(135deg, #0099dd, #00bbff);
    }

    .btn-invertir svg { width: 16px; height: 16px; }

    .btn-invertir.pronto {
        background: rgba(251,191,36,0.15);
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,0.3);
        box-shadow: none;
    }

    .btn-invertir.pronto:hover {
        background: rgba(251,191,36,0.25);
        box-shadow: 0 6px 18px rgba(251,191,36,0.15);
        transform: translateY(-2px);
    }

    /* ═══════════════════════════════════════
       CTA FINAL — TELEGRAM
    ═══════════════════════════════════════ */
    .invertir-cta {
        position: relative;
        border-radius: 22px;
        overflow: hidden;
        background: rgba(13,31,60,0.7);
        border: 1px solid rgba(0,212,255,0.2);
        padding: 3.5rem 2rem;
        text-align: center;
    }

    .invertir-cta-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 20% 50%, rgba(37,99,199,0.18) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 50%, rgba(0,212,255,0.12) 0%, transparent 50%),
            radial-gradient(ellipse at 50% 0%, rgba(30,77,153,0.2) 0%, transparent 60%);
        pointer-events: none;
    }

    .invertir-cta-lines {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
        opacity: 0.15;
    }

    .invertir-cta-lines::before,
    .invertir-cta-lines::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 200%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,212,255,0.8), transparent);
        animation: scanline 4s linear infinite;
    }

    .invertir-cta-lines::before { transform: translateY(-30px); animation-delay: 0s; }
    .invertir-cta-lines::after  { transform: translateY(30px);  animation-delay: 2s; }

    @keyframes scanline {
        0%   { left: -100%; }
        100% { left: 100%; }
    }

    .invertir-cta-content { position: relative; z-index: 2; }

    .invertir-cta-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: rgba(0,212,255,0.12);
        border: 1px solid rgba(0,212,255,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        animation: float 3s ease-in-out infinite;
    }

    .invertir-cta-icon svg { width: 36px; height: 36px; }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-8px); }
    }

    .invertir-cta-titulo {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.8rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 0.8rem;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .invertir-cta-titulo span { color: var(--accent); }

    .invertir-cta-desc {
        font-size: 0.95rem;
        color: var(--ice-200);
        max-width: 560px;
        margin: 0 auto 2rem;
        line-height: 1.7;
    }

    .invertir-cta-btns {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .btn-telegram-grande {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 0.9rem 2.2rem;
        border-radius: 10px;
        font-size: 0.92rem;
        font-weight: 700;
        text-decoration: none;
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        color: #fff;
        transition: all 0.25s;
        box-shadow: 0 8px 24px rgba(0,136,204,0.35);
        letter-spacing: 0.02em;
    }

    .btn-telegram-grande:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 36px rgba(0,136,204,0.5);
        background: linear-gradient(135deg, #0099dd, #00bbff);
    }

    .btn-telegram-grande svg { width: 20px; height: 20px; }

    .btn-secundario-invert {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.9rem 1.8rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        background: transparent;
        color: var(--ice-100);
        border: 1px solid rgba(74,144,217,0.3);
        transition: all 0.25s;
    }

    .btn-secundario-invert:hover {
        background: rgba(37,99,199,0.15);
        border-color: rgba(74,144,217,0.5);
    }

    .invertir-trust {
        display: flex;
        gap: 2rem;
        justify-content: center;
        flex-wrap: wrap;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(74,144,217,0.1);
    }

    .trust-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        color: var(--ice-300);
    }

    .trust-item svg { width: 15px; height: 15px; color: #4ade80; flex-shrink: 0; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }

    @media (max-width: 1024px) {
        .proyectos-grid { grid-template-columns: repeat(2, 1fr); }
        .proyecto-card.destacado { grid-column: span 2; }
        .inversion-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .proyectos-grid { grid-template-columns: 1fr; }
        .proyecto-card.destacado { grid-column: span 1; }
        .proyectos-header { flex-direction: column; align-items: flex-start; }
        .inversion-grid { grid-template-columns: 1fr; }
        .invertir-cta-titulo { font-size: 1.4rem; }
        .invertir-trust { gap: 1rem; }
    }
</style>

<section class="proyectos" id="proyectos">
    <div class="proyectos-bg"></div>
    <div class="proyectos-container">

        {{-- ══════════════════════════════════
             PARTE 1: PROYECTOS CULMINADOS
        ══════════════════════════════════ --}}

        <div class="proyectos-header reveal">
            <div class="proyectos-header-left">
                <div class="section-label">Portafolio</div>
                <h2 class="section-title">Proyectos <span>Culminados</span></h2>
            </div>
            <a href="{{ url('/proyectos') }}" class="proyectos-ver-todos">
                Ver todos los proyectos
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
            </a>
        </div>

        <div class="proyectos-filtros reveal">
            <button class="filtro-btn active" onclick="filtrarProyectos('todos', this)">Todos</button>
            <button class="filtro-btn" onclick="filtrarProyectos('automatizacion', this)">Automatización</button>
            <button class="filtro-btn" onclick="filtrarProyectos('iot', this)">IoT</button>
            <button class="filtro-btn" onclick="filtrarProyectos('web', this)">Software</button>
        </div>

        <div class="proyectos-grid" id="proyectosGrid">

            <div class="proyecto-card destacado reveal" data-categoria="automatizacion">
                <div class="proyecto-preview">
                    <div class="proyecto-preview-bg">⚙️</div>
                    <div class="proyecto-preview-icon" style="background:rgba(30,77,153,0.3);border:1px solid rgba(37,99,199,0.4)">
                        <svg viewBox="0 0 24 24" fill="rgba(122,184,245,0.9)"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                    </div>
                    <div class="proyecto-tags">
                        <span class="proyecto-tag auto">Automatización</span>
                        <span class="proyecto-tag iot">IoT</span>
                    </div>
                    <div class="proyecto-status completado">
                        <span class="proyecto-status-dot"></span> Completado
                    </div>
                </div>
                <div class="proyecto-body">
                    <div class="proyecto-nombre">Sistema SCADA de Monitoreo Industrial</div>
                    <p class="proyecto-desc">Implementación completa de un sistema SCADA para planta de manufactura. Integración de PLCs, sensores de temperatura, presión y flujo con visualización en tiempo real y alertas automáticas vía WhatsApp y correo.</p>
                    <div class="proyecto-metrics">
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">40<span>%</span></div>
                            <div class="proyecto-metric-label">Reducción de paradas</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">12<span> sen.</span></div>
                            <div class="proyecto-metric-label">Sensores integrados</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">24<span>/7</span></div>
                            <div class="proyecto-metric-label">Monitoreo continuo</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="proyecto-card reveal reveal-delay-1" data-categoria="iot">
                <div class="proyecto-preview">
                    <div class="proyecto-preview-bg">📡</div>
                    <div class="proyecto-preview-icon" style="background:rgba(0,212,255,0.1);border:1px solid rgba(0,212,255,0.3)">
                        <svg viewBox="0 0 24 24" fill="rgba(0,212,255,0.9)"><path d="M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z"/></svg>
                    </div>
                    <div class="proyecto-tags"><span class="proyecto-tag iot">IoT</span></div>
                    <div class="proyecto-status completado"><span class="proyecto-status-dot"></span> Completado</div>
                </div>
                <div class="proyecto-body">
                    <div class="proyecto-nombre">Red de Sensores Agrícola con ESP32</div>
                    <p class="proyecto-desc">Sistema de monitoreo de humedad, temperatura y pH del suelo para invernadero. Dashboard web en tiempo real con historial y alertas.</p>
                    <div class="proyecto-metrics">
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">8<span> nod.</span></div>
                            <div class="proyecto-metric-label">Nodos inalámbricos</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">30<span>%</span></div>
                            <div class="proyecto-metric-label">Ahorro de agua</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="proyecto-card reveal reveal-delay-1" data-categoria="web">
                <div class="proyecto-preview">
                    <div class="proyecto-preview-bg">💻</div>
                    <div class="proyecto-preview-icon" style="background:rgba(20,184,166,0.1);border:1px solid rgba(20,184,166,0.3)">
                        <svg viewBox="0 0 24 24" fill="rgba(45,212,191,0.9)"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                    </div>
                    <div class="proyecto-tags"><span class="proyecto-tag web">Software</span></div>
                    <div class="proyecto-status en-curso"><span class="proyecto-status-dot"></span> En curso</div>
                </div>
                <div class="proyecto-body">
                    <div class="proyecto-nombre">Sistema de Gestión Empresarial</div>
                    <p class="proyecto-desc">ERP personalizado con módulos de inventario, ventas, facturación y reportes. Integración con SUNAT para factura electrónica.</p>
                    <div class="proyecto-metrics">
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">5<span> mod.</span></div>
                            <div class="proyecto-metric-label">Módulos integrados</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">60<span>%</span></div>
                            <div class="proyecto-metric-label">Tiempo ahorrado</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="proyecto-card reveal reveal-delay-2" data-categoria="automatizacion">
                <div class="proyecto-preview">
                    <div class="proyecto-preview-bg">🏭</div>
                    <div class="proyecto-preview-icon" style="background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.3)">
                        <svg viewBox="0 0 24 24" fill="rgba(165,180,252,0.9)"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    </div>
                    <div class="proyecto-tags"><span class="proyecto-tag auto">Automatización</span></div>
                    <div class="proyecto-status completado"><span class="proyecto-status-dot"></span> Completado</div>
                </div>
                <div class="proyecto-body">
                    <div class="proyecto-nombre">Control de Línea de Producción PLC</div>
                    <p class="proyecto-desc">Automatización de línea de envasado con PLC Siemens S7-1200. Control de velocidad, conteo de productos y parada de emergencia.</p>
                    <div class="proyecto-metrics">
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">3<span>x</span></div>
                            <div class="proyecto-metric-label">Mayor producción</div>
                        </div>
                        <div class="proyecto-metric">
                            <div class="proyecto-metric-val">0<span> err.</span></div>
                            <div class="proyecto-metric-label">Errores en 6 meses</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /proyectos-grid --}}


        {{-- ══════════════════════════════════
             DIVISOR
        ══════════════════════════════════ --}}
        <div class="seccion-divisor reveal">
            <div class="seccion-divisor-line"></div>
            <div class="seccion-divisor-badge">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px">
                    <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                </svg>
                Oportunidades de Inversión
            </div>
            <div class="seccion-divisor-line"></div>
        </div>


        {{-- ══════════════════════════════════
             PARTE 2: PROYECTOS PARA INVERTIR
        ══════════════════════════════════ --}}

        <div class="invertir-header reveal">
            <div class="section-label" style="justify-content:center;">Invierte con MREYZE</div>
            <h2 class="section-title" style="text-align:center;">Proyectos <span>Abiertos a Inversión</span></h2>
        </div>
        <p class="invertir-subtitulo reveal">
            Estos son los proyectos que estamos por ejecutar o que se encuentran en desarrollo activo.
            Puedes financiar total o parcialmente cualquiera de ellos y ser parte del resultado.
        </p>

        {{-- Tarjetas de proyectos de inversión --}}
        <div class="inversion-grid">

            {{-- Proyecto 1: SCADA Minero --}}
            <div class="inversion-card destacada reveal">
                <span class="inversion-badge-top destacado">⭐ Destacado</span>
                <div class="inversion-card-header" style="background: linear-gradient(135deg, rgba(10,22,40,0.95) 0%, rgba(20,50,100,0.8) 100%);">
                    <div class="inversion-card-header-bg" style="background: radial-gradient(ellipse at 50% 120%, rgba(37,99,199,0.4) 0%, transparent 60%);"></div>
                    <div class="inversion-card-icon-wrap" style="background:rgba(37,99,199,0.2);border:1px solid rgba(37,99,199,0.5)">
                        <svg viewBox="0 0 24 24" fill="rgba(122,184,245,0.95)"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                    </div>
                    <span class="inversion-card-categoria" style="background:rgba(37,99,199,0.2);color:var(--ice-200);border:1px solid rgba(37,99,199,0.4)">Automatización Industrial</span>
                </div>
                <div class="inversion-card-body">
                    <div class="inversion-card-nombre">SCADA para Planta Minera — Fase 2</div>
                    <p class="inversion-card-desc">Expansión del sistema SCADA existente hacia una planta minera mediana. Incluye integración de 20+ sensores, dashboard en tiempo real, reportes automáticos y alertas críticas por SMS y WhatsApp.</p>
                    <div class="inversion-financiamiento">
                        <div class="inversion-financiamiento-header">
                            <span>Financiado</span>
                            <strong>65%</strong>
                        </div>
                        <div class="inversion-barra-bg">
                            <div class="inversion-barra-fill" style="width:65%;background:linear-gradient(90deg,#2563c7,#00d4ff);"></div>
                        </div>
                    </div>
                    <div class="inversion-datos">
                        <div class="inversion-dato">
                            <div class="inversion-dato-val amarillo">S/ 18<span>,000</span></div>
                            <div class="inversion-dato-label">Inversión total</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">S/ 6<span>,300</span></div>
                            <div class="inversion-dato-label">Disponible aún</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">5<span> meses</span></div>
                            <div class="inversion-dato-label">Tiempo de ejecución</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">ROI <span>~18%</span></div>
                            <div class="inversion-dato-label">Retorno estimado</div>
                        </div>
                    </div>
                </div>
                <div class="inversion-card-footer">
                    <a href="https://t.me/+6k2JGfEJzvY1ZWNh" target="_blank" rel="noopener noreferrer" class="btn-invertir">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                        Quiero invertir en este proyecto
                    </a>
                </div>
            </div>

            {{-- Proyecto 2: App IoT Agrícola --}}
            <div class="inversion-card reveal reveal-delay-1">
                <span class="inversion-badge-top abierto">● Abierto</span>
                <div class="inversion-card-header" style="background: linear-gradient(135deg, rgba(10,22,40,0.95) 0%, rgba(0,50,40,0.8) 100%);">
                    <div class="inversion-card-header-bg" style="background: radial-gradient(ellipse at 50% 120%, rgba(20,184,166,0.35) 0%, transparent 60%);"></div>
                    <div class="inversion-card-icon-wrap" style="background:rgba(20,184,166,0.15);border:1px solid rgba(20,184,166,0.4)">
                        <svg viewBox="0 0 24 24" fill="rgba(45,212,191,0.95)"><path d="M17 8C8 10 5.9 16.17 3.82 21h2.13c1.03-2.17 2.23-4.17 3.72-5.67C10.6 17.9 12 19.9 12 22h2c0-3-1.63-5.68-3.49-7.8C11.77 13.4 14.08 12 17 12v3l4-4-4-4v3z"/></svg>
                    </div>
                    <span class="inversion-card-categoria" style="background:rgba(20,184,166,0.15);color:#2dd4bf;border:1px solid rgba(20,184,166,0.3)">IoT / Agro</span>
                </div>
                <div class="inversion-card-body">
                    <div class="inversion-card-nombre">App Móvil de Monitoreo Agrícola IoT</div>
                    <p class="inversion-card-desc">Aplicación móvil (Android/iOS) conectada a red de sensores ESP32 para agricultores. Control de riego automático, historial de cultivos y alertas de plagas. Escalable a múltiples fundos.</p>
                    <div class="inversion-financiamiento">
                        <div class="inversion-financiamiento-header">
                            <span>Financiado</span>
                            <strong>30%</strong>
                        </div>
                        <div class="inversion-barra-bg">
                            <div class="inversion-barra-fill" style="width:30%;background:linear-gradient(90deg,#0d9488,#2dd4bf);"></div>
                        </div>
                    </div>
                    <div class="inversion-datos">
                        <div class="inversion-dato">
                            <div class="inversion-dato-val amarillo">S/ 12<span>,500</span></div>
                            <div class="inversion-dato-label">Inversión total</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">S/ 8<span>,750</span></div>
                            <div class="inversion-dato-label">Disponible aún</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">4<span> meses</span></div>
                            <div class="inversion-dato-label">Tiempo de ejecución</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">ROI <span>~22%</span></div>
                            <div class="inversion-dato-label">Retorno estimado</div>
                        </div>
                    </div>
                </div>
                <div class="inversion-card-footer">
                    <a href="https://t.me/+6k2JGfEJzvY1ZWNh" target="_blank" rel="noopener noreferrer" class="btn-invertir">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                        Quiero invertir en este proyecto
                    </a>
                </div>
            </div>

            {{-- Proyecto 3: ERP SaaS --}}
            <div class="inversion-card reveal reveal-delay-2">
                <span class="inversion-badge-top pronto">◷ Próximamente</span>
                <div class="inversion-card-header" style="background: linear-gradient(135deg, rgba(10,22,40,0.95) 0%, rgba(40,20,80,0.8) 100%);">
                    <div class="inversion-card-header-bg" style="background: radial-gradient(ellipse at 50% 120%, rgba(99,102,241,0.35) 0%, transparent 60%);"></div>
                    <div class="inversion-card-icon-wrap" style="background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.4)">
                        <svg viewBox="0 0 24 24" fill="rgba(165,180,252,0.95)"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                    </div>
                    <span class="inversion-card-categoria" style="background:rgba(99,102,241,0.15);color:#a5b4fc;border:1px solid rgba(99,102,241,0.3)">Software / SaaS</span>
                </div>
                <div class="inversion-card-body">
                    <div class="inversion-card-nombre">ERP SaaS para PYMEs Peruanas</div>
                    <p class="inversion-card-desc">Plataforma de gestión empresarial en la nube con suscripción mensual. Módulos de ventas, inventario, RRHH y contabilidad. Integración nativa con SUNAT. Modelo de ingresos recurrentes escalable.</p>
                    <div class="inversion-financiamiento">
                        <div class="inversion-financiamiento-header">
                            <span>Financiado</span>
                            <strong>10%</strong>
                        </div>
                        <div class="inversion-barra-bg">
                            <div class="inversion-barra-fill" style="width:10%;background:linear-gradient(90deg,#6366f1,#a5b4fc);"></div>
                        </div>
                    </div>
                    <div class="inversion-datos">
                        <div class="inversion-dato">
                            <div class="inversion-dato-val amarillo">S/ 25<span>,000</span></div>
                            <div class="inversion-dato-label">Inversión total</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">S/ 22<span>,500</span></div>
                            <div class="inversion-dato-label">Disponible aún</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">8<span> meses</span></div>
                            <div class="inversion-dato-label">Tiempo de ejecución</div>
                        </div>
                        <div class="inversion-dato">
                            <div class="inversion-dato-val">ROI <span>~35%</span></div>
                            <div class="inversion-dato-label">Retorno estimado</div>
                        </div>
                    </div>
                </div>
                <div class="inversion-card-footer">
                    <a href="https://t.me/+6k2JGfEJzvY1ZWNh" target="_blank" rel="noopener noreferrer" class="btn-invertir pronto">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                        Notificarme cuando abra
                    </a>
                </div>
            </div>

        </div>{{-- /inversion-grid --}}


        {{-- CTA final Telegram --}}
        <div class="invertir-cta reveal">
            <div class="invertir-cta-bg"></div>
            <div class="invertir-cta-lines"></div>
            <div class="invertir-cta-content">
                <div class="invertir-cta-icon">
                    <svg viewBox="0 0 24 24" fill="rgba(0,212,255,0.9)">
                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                    </svg>
                </div>
                <h3 class="invertir-cta-titulo">¿Listo para ser parte de <span>algo grande</span>?</h3>
                <p class="invertir-cta-desc">Únete a nuestro grupo de Telegram y coordina directamente la inversión que más te interese. Te enviamos el prospecto completo con detalles técnicos, financieros y contrato.</p>
                <div class="invertir-cta-btns">
                    <a href="https://t.me/+6k2JGfEJzvY1ZWNh" target="_blank" rel="noopener noreferrer" class="btn-telegram-grande">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                        Unirme al grupo de Telegram
                    </a>
                    <a href="{{ url('/contacto') }}" class="btn-secundario-invert">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        Solicitar prospecto por correo
                    </a>
                </div>
                <div class="invertir-trust">
                    <div class="trust-item">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        Información sin costo
                    </div>
                    <div class="trust-item">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        Prospecto completo en 24h
                    </div>
                    <div class="trust-item">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        Contrato claro y detallado
                    </div>
                    <div class="trust-item">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        Reportes de avance semanales
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    function filtrarProyectos(categoria, btn) {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.proyecto-card').forEach(card => {
            if (categoria === 'todos' || card.dataset.categoria === categoria) {
                card.style.display = '';
                card.style.animation = 'fadeIn 0.4s ease';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

@endsection