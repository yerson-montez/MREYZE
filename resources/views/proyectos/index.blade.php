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
        border: 1px solid rgba(12, 142, 168, 0.3);
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
    .inversion-badge-top.disponible { background: rgba(34,197,94,0.2);  border: 1px solid rgba(34,197,94,0.4);  color: #4ade80; }
    .inversion-badge-top.en_ejecucion { background: rgba(251,191,36,0.2); border: 1px solid rgba(251,191,36,0.4); color: #fbbf24; }
    .inversion-badge-top.pronto     { background: rgba(251,191,36,0.2); border: 1px solid rgba(251,191,36,0.4); color: #fbbf24; }
    .inversion-badge-top.destacado  { background: rgba(0,212,255,0.15); border: 1px solid rgba(0,212,255,0.4);  color: var(--accent); }
    .inversion-badge-top.completado { background: rgba(34,197,94,0.2); border: 1px solid rgba(34,197,94,0.4); color: #4ade80; }

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
    .inversion-dato-val.verde { color: #4ade80; }

    .inversion-dato-label {
        font-size: 0.7rem;
        color: var(--ice-300);
        margin-top: 1px;
    }

    /* Footer de la tarjeta */
    .inversion-card-footer {
        padding: 1rem 1.4rem 1.4rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
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

    .btn-invertir-whatsapp {
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
        color: var(--ice-200);
        border: 1px solid rgba(74,144,217,0.25);
        background: rgba(74,144,217,0.08);
        transition: all 0.25s;
    }

    .btn-invertir-whatsapp:hover {
        background: rgba(37,99,199,0.2);
        border-color: rgba(74,144,217,0.45);
        color: #fff;
    }

    .btn-invertir-whatsapp svg { width: 16px; height: 16px; }

    /* ═══════════════════════════════════════
       SIMULADOR DE INVERSIÓN
    ═══════════════════════════════════════ */
    .simulador {
        margin: 1rem 0 3rem;
        border-radius: 22px;
        padding: 2.5rem 2rem;
        background: linear-gradient(135deg, rgba(13,31,60,.9), rgba(17,41,82,.85));
        border: 1px solid rgba(0,212,255,.2);
        position: relative;
        overflow: hidden;
    }

    .simulador::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    .sim-titulo {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.7rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: .4rem;
    }

    .sim-titulo span {
        background: linear-gradient(135deg, var(--ice-200), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .sim-desc { color: var(--ice-300); font-size: .9rem; margin-bottom: 2rem; }

    .sim-grid { display: grid; grid-template-columns: 1.05fr 1fr; gap: 2rem; align-items: center; }
    .sim-campos { display: grid; gap: 1rem; }
    .sim-campo { display: flex; flex-direction: column; gap: 6px; }
    .sim-campo label { font-size: .74rem; font-weight: 600; color: var(--ice-100); text-transform: uppercase; letter-spacing: .06em; }
    .sim-campo select, .sim-campo input {
        background: rgba(7,26,46,.6); border: 1px solid rgba(74,144,217,.25);
        border-radius: 10px; padding: .7rem 1rem; color: #fff; font-size: .92rem; outline: none;
        transition: border-color .2s, box-shadow .2s; width: 100%; box-sizing: border-box;
    }
    .sim-campo select:focus, .sim-campo input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,212,255,.15); }
    .sim-campo input::placeholder { color: rgba(122,154,184,.6); }

    .sim-resultados { display: grid; gap: .8rem; }
    .sim-fila {
        display: flex; justify-content: space-between; align-items: center;
        background: rgba(7,26,46,.5); border: 1px solid rgba(74,144,217,.15);
        border-radius: 12px; padding: .9rem 1.1rem;
    }
    .sim-fila-label { font-size: .8rem; color: var(--ice-300); }
    .sim-fila-val { font-family: 'Exo 2', sans-serif; font-weight: 700; color: #fff; font-size: 1.05rem; }
    .sim-fila-val.gain { color: #4ade80; }
    .sim-fila-val.total { color: var(--accent); }

    @media (max-width: 900px) {
        .sim-grid { grid-template-columns: 1fr; }
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

    .proyecto-preview-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
    }

    .inversion-card-header-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }

    @media (max-width: 1024px) {
        .proyectos-grid { grid-template-columns: repeat(2, 1fr); }
        .inversion-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .proyectos-grid { grid-template-columns: 1fr; }
        .proyectos-header { flex-direction: column; align-items: flex-start; }
        .inversion-grid { grid-template-columns: 1fr; }
        .invertir-cta-titulo { font-size: 1.4rem; }
        .invertir-trust { gap: 1rem; }
    }
</style>

@php
    $catLabels = [
        'automatizacion' => 'Automatización',
        'iot'            => 'IoT',
        'software'       => 'Software',
    ];
    $catCssClasses = [
        'automatizacion' => 'auto',
        'iot'            => 'iot',
        'software'       => 'web',
    ];
    $catEmojis = [
        'automatizacion' => '⚙️',
        'iot'            => '📡',
        'software'       => '💻',
    ];
    $catIconStyles = [
        'automatizacion' => 'background:rgba(37,99,199,0.3);border:1px solid rgba(37,99,199,0.4)',
        'iot'            => 'background:rgba(0,212,255,0.1);border:1px solid rgba(0,212,255,0.3)',
        'software'       => 'background:rgba(20,184,166,0.1);border:1px solid rgba(20,184,166,0.3)',
    ];
    $catIconFills = [
        'automatizacion' => 'rgba(122,184,245,0.9)',
        'iot'            => 'rgba(0,212,255,0.9)',
        'software'       => 'rgba(45,212,191,0.9)',
    ];

    $catInversionStyles = [
        'automatizacion' => [
            'header'  => 'background:linear-gradient(135deg, rgba(10,22,40,0.95) 0%, rgba(20,50,100,0.8) 100%)',
            'bg'      => 'background:radial-gradient(ellipse at 50% 120%, rgba(37,99,199,0.4) 0%, transparent 60%)',
            'icon'    => 'background:rgba(37,99,199,0.2);border:1px solid rgba(37,99,199,0.5)',
            'cat'     => 'background:rgba(37,99,199,0.2);color:var(--ice-200);border:1px solid rgba(37,99,199,0.4)',
            'fill'    => 'linear-gradient(90deg,#2563c7,#00d4ff)',
            'fillName'=> 'Automatización Industrial',
        ],
        'iot' => [
            'header'  => 'background:linear-gradient(135deg, rgba(10,22,40,0.95) 0%, rgba(0,50,40,0.8) 100%)',
            'bg'      => 'background:radial-gradient(ellipse at 50% 120%, rgba(20,184,166,0.35) 0%, transparent 60%)',
            'icon'    => 'background:rgba(20,184,166,0.15);border:1px solid rgba(20,184,166,0.4)',
            'cat'     => 'background:rgba(20,184,166,0.15);color:#2dd4bf;border:1px solid rgba(20,184,166,0.3)',
            'fill'    => 'linear-gradient(90deg,#0d9488,#2dd4bf)',
            'fillName'=> 'IoT / Agro',
        ],
        'software' => [
            'header'  => 'background:linear-gradient(135deg, rgba(10,22,40,0.95) 0%, rgba(40,20,80,0.8) 100%)',
            'bg'      => 'background:radial-gradient(ellipse at 50% 120%, rgba(99,102,241,0.35) 0%, transparent 60%)',
            'icon'    => 'background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.4)',
            'cat'     => 'background:rgba(99,102,241,0.15);color:#a5b4fc;border:1px solid rgba(99,102,241,0.3)',
            'fill'    => 'linear-gradient(90deg,#6366f1,#a5b4fc)',
            'fillName'=> 'Software / SaaS',
        ],
    ];
@endphp

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
            @foreach($categorias as $cat)
                @php
                    $label = $catLabels[$cat] ?? ucfirst($cat);
                    $cssClass = $catCssClasses[$cat] ?? 'web';
                @endphp
                <button class="filtro-btn" onclick="filtrarProyectos('{{ $cat }}', this)">{{ $label }}</button>
            @endforeach
        </div>

        <div class="proyectos-grid" id="proyectosGrid">

            @forelse($portafolio as $idx => $proyecto)
                @php
                    $cat = $proyecto->categoria;
                    $tagClass = $catCssClasses[$cat] ?? 'web';
                    $tagLabel = $catLabels[$cat] ?? ucfirst($cat);
                    $emoji = $catEmojis[$cat] ?? '📁';
                    $iconStyle = $catIconStyles[$cat] ?? 'background:rgba(74,144,217,0.1);border:1px solid rgba(74,144,217,0.3)';
                    $iconFill = $catIconFills[$cat] ?? 'rgba(122,184,245,0.9)';
                    $estadoClass = $proyecto->estado === 'completado' ? 'completado' : 'en-curso';
                    $isDestacado = $idx === 0 && $proyecto->categoria === 'automatizacion';
                    $revealClass = $idx <= 1 ? 'reveal' : ($idx === 2 ? 'reveal reveal-delay-1' : 'reveal reveal-delay-2');
                @endphp
                <a href="{{ route('proyectos.show', $proyecto->slug) }}" class="proyecto-card{{ $isDestacado ? ' destacado' : '' }} {{ $revealClass }}" data-categoria="{{ $cat }}" style="text-decoration:none;color:inherit">
                    <div class="proyecto-preview">
                        @if($proyecto->imagen_principal_url)
                            <img src="{{ $proyecto->imagen_principal_url }}" alt="{{ $proyecto->titulo }}" class="proyecto-preview-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="proyecto-preview-bg" style="display:none">{{ $emoji }}</div>
                        @else
                            <div class="proyecto-preview-bg">{{ $emoji }}</div>
                        @endif
                        <div class="proyecto-preview-icon" style="{{ $iconStyle }}">
                            <svg viewBox="0 0 24 24" fill="{{ $iconFill }}"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                        </div>
                        <div class="proyecto-tags">
                            <span class="proyecto-tag {{ $tagClass }}">{{ $tagLabel }}</span>
                        </div>
                        <div class="proyecto-status {{ $estadoClass }}">
                            <span class="proyecto-status-dot"></span> {{ ucfirst($proyecto->estado) }}
                        </div>
                    </div>
                    <div class="proyecto-body">
                        <div class="proyecto-nombre">{{ $proyecto->titulo }}</div>
                        <p class="proyecto-desc">{{ $proyecto->descripcion_corta }}</p>
                    </div>
                </a>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:4rem 1rem;color:var(--ice-300)">
                    <p style="font-size:1rem;">No hay proyectos en el portafolio por el momento.</p>
                </div>
            @endforelse

        </div>{{-- /proyectos-grid --}}


        {{-- ══════════════════════════════════
             DIVISOR
        ══════════════════════════════════ --}}
        @if($disponibles->count())
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
            <h2 class="section-title" style="text-align:center;">Proyectos para <span>invertir</span></h2>
        </div>
        <p class="invertir-subtitulo reveal">
            Invierte tu dinero y obtén un retorno estimado del
            <strong style="color:var(--accent);">{{ $retornoEjemplo }}%</strong>.
            Elige un proyecto, calcula tu ganancia con el simulador y sé parte del resultado.
        </p>

        {{-- Tarjetas de proyectos de inversión --}}
        <div class="inversion-grid">

            @foreach($disponibles as $idx => $inv)
                @php
                    $cat = $inv->categoria;
                    $invStyle = $catInversionStyles[$cat] ?? $catInversionStyles['software'];
                    $emoji = $catEmojis[$cat] ?? '📁';
                    $esDestacada = $inv->inversion_porcentaje_financiado >= 50;
                    $revealDelay = $idx === 0 ? 'reveal' : ($idx === 1 ? 'reveal reveal-delay-1' : 'reveal reveal-delay-2');
                @endphp
                <div class="inversion-card{{ $esDestacada ? ' destacada' : '' }} {{ $revealDelay }}">
                    <span class="inversion-badge-top disponible">● Disponible</span>
                    <div class="inversion-card-header" style="{{ $invStyle['header'] }}">
                        <div class="inversion-card-header-bg" style="{{ $invStyle['bg'] }}"></div>
                        @if($inv->imagen_principal_url)
                            <img src="{{ $inv->imagen_principal_url }}" alt="{{ $inv->titulo }}" class="inversion-card-header-img" onerror="this.style.display='none'">
                        @endif
                        <div class="inversion-card-icon-wrap" style="{{ $invStyle['icon'] }}">
                            <svg viewBox="0 0 24 24" fill="{{ $catIconFills[$cat] ?? 'rgba(122,184,245,0.95)' }}"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                        </div>
                        <span class="inversion-card-categoria" style="{{ $invStyle['cat'] }}">{{ $invStyle['fillName'] }}</span>
                    </div>
                    <div class="inversion-card-body">
                        <div class="inversion-card-nombre">{{ $inv->titulo }}</div>
                        <p class="inversion-card-desc">{{ $inv->descripcion_corta }}</p>
                        <div class="inversion-financiamiento">
                            <div class="inversion-financiamiento-header">
                                <span>Financiado</span>
                                <strong>{{ $inv->inversion_porcentaje_financiado }}%</strong>
                            </div>
                            <div class="inversion-barra-bg">
                                <div class="inversion-barra-fill" style="width:{{ $inv->inversion_porcentaje_financiado }}%;background:{{ $invStyle['fill'] }};"></div>
                            </div>
                        </div>
                        <div class="inversion-datos">
                            <div class="inversion-dato">
                                <div class="inversion-dato-val amarillo">S/ <span>{{ number_format($inv->inversion_monto_total, 0, ',', ',') }}</span></div>
                                <div class="inversion-dato-label">Inversión total</div>
                            </div>
                            <div class="inversion-dato">
                                <div class="inversion-dato-val">S/ <span>{{ number_format($inv->inversion_monto_disponible, 0, ',', ',') }}</span></div>
                                <div class="inversion-dato-label">Disponible aún</div>
                            </div>
                            <div class="inversion-dato">
                                <div class="inversion-dato-val">{{ (int) $inv->inversion_tiempo_estimado }}<span> meses</span></div>
                                <div class="inversion-dato-label">Tiempo de ejecución</div>
                            </div>
                            <div class="inversion-dato">
                                <div class="inversion-dato-val">{{ $inv->inversion_roi_label }}</div>
                                <div class="inversion-dato-label">Retorno estimado</div>
                            </div>
                        </div>
                    </div>
                    <div class="inversion-card-footer">
                        <a href="{{ route('inversiones.create', $inv) }}" class="btn-invertir">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            Invertir ahora
                        </a>
                        @if($inv->whatsapp_numero)
                            <a href="{{ $inv->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="btn-invertir-whatsapp">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                Coordinar por WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>{{-- /inversion-grid --}}

        {{-- SIMULADOR DE INVERSIÓN --}}
        <section class="simulador reveal">
            <div class="section-label" style="justify-content:center;">Simulador de inversión</div>
            <h3 class="sim-titulo" style="text-align:center;">Calcula tu <span>retorno</span></h3>
            <p class="sim-desc" style="text-align:center; max-width:560px; margin:0 auto 2rem;">Elige un proyecto, ingresa el monto a invertir y descubre cuánto puedes ganar.</p>

            <div class="sim-grid">
                <div class="sim-campos">
                    <div class="sim-campo">
                        <label for="simProyecto">Proyecto</label>
                        <select id="simProyecto">
                            @foreach($disponibles as $inv)
                                <option value="{{ $inv->id }}"
                                        data-roi="{{ $inv->inversion_roi_numero }}"
                                        data-meses="{{ (int) $inv->inversion_tiempo_estimado }}"
                                        data-max="{{ (float) $inv->inversion_monto_disponible }}">
                                    {{ $inv->titulo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sim-campo">
                        <label for="simMonto">Monto a invertir (S/)</label>
                        <input type="number" id="simMonto" min="50" step="50" value="1000">
                    </div>
                    <div class="sim-campo">
                        <label for="simPeriodo">Periodo de inversión</label>
                        <select id="simPeriodo"></select>
                    </div>
                </div>
                <div class="sim-resultados">
                    <div class="sim-fila">
                        <span class="sim-fila-label">Monto invertido</span>
                        <span class="sim-fila-val" id="simBase">S/ 1,000</span>
                    </div>
                    <div class="sim-fila">
                        <span class="sim-fila-label">Retorno estimado (<span id="simRoiLabel"></span>)</span>
                        <span class="sim-fila-val gain" id="simGanancia">S/ 0</span>
                    </div>
                    <div class="sim-fila">
                        <span class="sim-fila-label">Total recibido</span>
                        <span class="sim-fila-val total" id="simTotal">S/ 0</span>
                    </div>
                </div>
            </div>
        </section>
        @endif


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

        {{-- ══════════════════════════════════
             PARTE FINAL: PROYECTOS COMPLETADOS
        ══════════════════════════════════ --}}
        @if($completados->count())
        <div class="seccion-divisor reveal" style="margin-top:5rem;">
            <div class="seccion-divisor-line"></div>
            <div class="seccion-divisor-badge">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                Resultados entregados
            </div>
            <div class="seccion-divisor-line"></div>
        </div>

        <div class="invertir-header reveal">
            <div class="section-label" style="justify-content:center;">Resultados entregados</div>
            <h2 class="section-title" style="text-align:center;">Proyectos <span>completados</span></h2>
        </div>
        <p class="invertir-subtitulo reveal" style="margin-bottom:2.5rem;">
            Inversiones que ya entregamos con su retorno final. Así de transparente es el proceso con MREYZE.
        </p>

        <div class="inversion-grid">
            @foreach($completados as $idx => $inv)
                @php
                    $cat = $inv->categoria;
                    $invStyle = $catInversionStyles[$cat] ?? $catInversionStyles['software'];
                    $emoji = $catEmojis[$cat] ?? '📁';
                    $retornoFinal = $inv->inversion_retorno_final_label ?? $inv->inversion_roi_label;
                    $revealDelay = $idx === 0 ? 'reveal' : ($idx === 1 ? 'reveal reveal-delay-1' : 'reveal reveal-delay-2');
                @endphp
                <a href="{{ route('proyectos.show', $inv) }}" class="inversion-card {{ $revealDelay }}" style="text-decoration:none;color:inherit;">
                    <span class="inversion-badge-top completado">✓ Completado</span>
                    <div class="inversion-card-header" style="{{ $invStyle['header'] }}">
                        <div class="inversion-card-header-bg" style="{{ $invStyle['bg'] }}"></div>
                        @if($inv->imagen_principal_url)
                            <img src="{{ $inv->imagen_principal_url }}" alt="{{ $inv->titulo }}" class="inversion-card-header-img" onerror="this.style.display='none'">
                        @endif
                        <div class="inversion-card-icon-wrap" style="{{ $invStyle['icon'] }}">
                            <svg viewBox="0 0 24 24" fill="{{ $catIconFills[$cat] ?? 'rgba(122,184,245,0.95)' }}"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                        </div>
                        <span class="inversion-card-categoria" style="{{ $invStyle['cat'] }}">{{ $invStyle['fillName'] }}</span>
                    </div>
                    <div class="inversion-card-body">
                        <div class="inversion-card-nombre">{{ $inv->titulo }}</div>
                        <p class="inversion-card-desc">{{ $inv->descripcion_corta }}</p>
                        <div class="inversion-datos">
                            <div class="inversion-dato">
                                <div class="inversion-dato-val verde">{{ $retornoFinal }}</div>
                                <div class="inversion-dato-label">Retorno final</div>
                            </div>
                            <div class="inversion-dato">
                                <div class="inversion-dato-val">{{ $inv->fecha_fin?->format('d/m/Y') }}</div>
                                <div class="inversion-dato-label">Fecha de finalización</div>
                            </div>
                        </div>
                    </div>
                    <div class="inversion-card-footer">
                        <span class="btn-invertir">Ver proyecto</span>
                    </div>
                </a>
            @endforeach
        </div>
        @endif

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

@push('scripts')
<script>
(function () {
    var selProy = document.getElementById('simProyecto');
    var selPer  = document.getElementById('simPeriodo');
    var monto   = document.getElementById('simMonto');
    var base    = document.getElementById('simBase');
    var gan     = document.getElementById('simGanancia');
    var total   = document.getElementById('simTotal');
    var roiLbl  = document.getElementById('simRoiLabel');

    if (!selProy || !selPer) return;

    function fmt(n) {
        return 'S/ ' + n.toLocaleString('es-PE', { maximumFractionDigits: 2 });
    }

    function proyecto() {
        var opt = selProy.options[selProy.selectedIndex];
        return {
            roi: parseFloat(opt.dataset.roi) || 0,
            meses: parseInt(opt.dataset.meses, 10) || 0,
            max: parseFloat(opt.dataset.max) || 0
        };
    }

    function refreshPeriodo() {
        var p = proyecto();
        selPer.innerHTML = '<option>' + p.meses + ' meses</option>';
        roiLbl.textContent = p.roi + '%';
        monto.max = Math.max(50, p.max);
        if (parseFloat(monto.value) > p.max) {
            monto.value = Math.max(50, Math.floor(p.max));
        }
    }

    function update() {
        var p = proyecto();
        var v = parseFloat(monto.value) || 0;
        if (v < 50) v = 50;
        if (v > p.max) v = p.max;
        monto.value = v;
        base.textContent = fmt(v);
        var g = v * (p.roi / 100);
        gan.textContent = fmt(g);
        total.textContent = fmt(v + g);
    }

    selProy.addEventListener('change', function () { refreshPeriodo(); update(); });
    monto.addEventListener('input', update);
    refreshPeriodo();
    update();
})();
</script>
@endpush
