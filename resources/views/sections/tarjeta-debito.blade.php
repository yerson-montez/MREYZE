@extends('welcome')

@section('title', 'Tarjeta Débito — MREYZE')
@section('meta_description', 'Solicita tu Tarjeta Débito MReyze y descubre nuevas oportunidades para crecer junto a nuestra comunidad.')

@section('content')

<style>
    /* ════════════════════════════════════════════════════
       SECCIÓN TARJETA DÉBITO — estilos propios (prefijo td-)
       No afecta otras secciones del sitio.
    ════════════════════════════════════════════════════ */
    .td-page { padding-bottom: 4rem; }

    /* ── Hero ── */
    .td-hero {
        position: relative;
        padding: 4.5rem 0 3rem;
        overflow: hidden;
    }
    .td-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            radial-gradient(60% 50% at 85% 10%, rgba(0,212,255,0.10), transparent 60%),
            radial-gradient(50% 45% at 10% 90%, rgba(37,99,199,0.18), transparent 60%);
        pointer-events: none;
    }
    .td-hero-grid {
        position: relative;
        display: grid;
        grid-template-columns: 1.05fr 0.95fr;
        gap: 3.5rem;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    .td-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 1.1rem;
    }
    .td-kicker::before {
        content: "";
        width: 26px;
        height: 2px;
        background: linear-gradient(90deg, var(--accent), transparent);
        border-radius: 2px;
    }
    .td-hero-title {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(2rem, 4.2vw, 3.2rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.12;
        letter-spacing: -0.02em;
        margin: 0 0 1.1rem;
    }
    .td-hero-title span {
        background: linear-gradient(90deg, #00d4ff, #7ab8f5);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .td-hero-sub {
        font-size: 1.05rem;
        color: var(--ice-200);
        line-height: 1.7;
        max-width: 520px;
        margin: 0 0 1.8rem;
    }
    .td-hero-cta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.9rem;
        margin-bottom: 1.4rem;
    }
    .td-btn {
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
    }
    .td-btn-primary {
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        color: #fff;
        box-shadow: 0 8px 24px rgba(0,136,204,0.35);
    }
    .td-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(0,136,204,0.5);
        background: linear-gradient(135deg, #0099dd, #00bbff);
    }
    .td-btn-ghost {
        background: rgba(74,144,217,0.06);
        border-color: rgba(74,144,217,0.3);
        color: var(--ice-100);
    }
    .td-btn-ghost:hover {
        background: rgba(74,144,217,0.14);
        border-color: rgba(0,212,255,0.5);
        color: #fff;
    }
    .td-disclaimer {
        font-size: 0.72rem;
        color: var(--ice-300);
        line-height: 1.55;
        max-width: 480px;
        margin: 0;
    }

    /* ── Tarjeta visual (representación promocional) ── */
    .td-hero-card {
        display: flex;
        justify-content: center;
        perspective: 1200px;
    }
    .td-card-img {
        width: min(430px, 100%);
        height: auto;
        border-radius: 22px;
        box-shadow: 0 30px 70px rgba(0,0,0,0.5), 0 0 110px rgba(0,212,255,0.14);
        animation: tdCardFloat 6s ease-in-out infinite;
    }
    .td-card {
        position: relative;
        width: min(430px, 100%);
        aspect-ratio: 1.586;
        border-radius: 22px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        background:
            radial-gradient(130% 150% at 100% 0%, rgba(0,212,255,0.28), transparent 55%),
            radial-gradient(130% 150% at 0% 100%, rgba(37,99,199,0.5), transparent 60%),
            linear-gradient(135deg, #0c2f52 0%, #0d1f3c 45%, #143464 100%);
        border: 1px solid rgba(122,184,245,0.25);
        box-shadow:
            0 30px 70px rgba(0,0,0,0.5),
            0 0 0 1px rgba(0,212,255,0.05),
            0 0 110px rgba(0,212,255,0.14);
        transform-style: preserve-3d;
        animation: tdCardFloat 6s ease-in-out infinite;
    }
    .td-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(122,184,245,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(122,184,245,0.05) 1px, transparent 1px);
        background-size: 30px 30px;
        -webkit-mask-image: radial-gradient(95% 85% at 70% 25%, #000, transparent);
        mask-image: radial-gradient(95% 85% at 70% 25%, #000, transparent);
        pointer-events: none;
    }
    .td-card::after {
        content: "";
        position: absolute;
        top: -40%;
        right: -25%;
        width: 70%;
        height: 70%;
        background: radial-gradient(circle, rgba(0,212,255,0.18), transparent 65%);
        filter: blur(10px);
        pointer-events: none;
    }
    @keyframes tdCardFloat {
        0%, 100% { transform: rotateY(0deg) rotateX(2deg) translateY(0); }
        50%      { transform: rotateY(-6deg) rotateX(3deg) translateY(-8px); }
    }
    .td-card-top {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }
    .td-card-brand {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .td-card-logo {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: 0.14em;
        color: #fff;
        text-shadow: 0 0 18px rgba(0,212,255,0.35);
    }
    .td-card-tag {
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--ice-200);
    }
    .td-card-chip {
        width: 46px;
        height: 34px;
        border-radius: 8px;
        background: linear-gradient(135deg, #e7c987, #f4e3b2 50%, #d9b86a);
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.15), 0 2px 6px rgba(0,0,0,0.3);
        position: relative;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3px;
        padding: 5px 6px;
    }
    .td-card-chip span {
        background: rgba(140,110,40,0.35);
        border-radius: 2px;
    }
    .td-card-mid {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 4px;
    }
    .td-card-contactless {
        width: 20px;
        height: 20px;
        opacity: 0.8;
    }
    .td-card-placeholder {
        display: flex;
        gap: 7px;
        align-items: center;
    }
    .td-card-placeholder span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(232,244,255,0.75);
        box-shadow: 0 0 8px rgba(0,212,255,0.4);
    }
    .td-card-bottom {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 4px;
    }
    .td-card-holder {
        font-size: 0.66rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--ice-200);
        opacity: 0.85;
    }
    .td-card-visa {
        font-family: 'Exo 2', sans-serif;
        font-style: italic;
        font-weight: 800;
        font-size: 1.7rem;
        letter-spacing: 0.02em;
        color: #fff;
        text-shadow: 0 0 24px rgba(0,212,255,0.5);
        line-height: 1;
    }
    .td-card-note {
        margin-top: 1.2rem;
        font-size: 0.7rem;
        color: var(--ice-300);
        text-align: center;
    }

    /* ── Beneficios ── */
    .td-seccion {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3.5rem 1.5rem;
    }
    .td-seccion-header {
        text-align: center;
        margin-bottom: 2.6rem;
    }
    .td-seccion-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.7rem;
    }
    .td-seccion-titulo {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(1.5rem, 3vw, 2.1rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin: 0;
    }
    .td-seccion-titulo span {
        background: linear-gradient(90deg, #00d4ff, #7ab8f5);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .td-beneficios-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.3rem;
    }
    .td-beneficio {
        position: relative;
        padding: 1.7rem 1.5rem;
        border-radius: 18px;
        background: rgba(13,31,60,0.65);
        border: 1px solid rgba(74,144,217,0.16);
        transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
    }
    .td-beneficio:hover {
        transform: translateY(-6px);
        border-color: rgba(0,212,255,0.45);
        box-shadow: 0 18px 44px rgba(0,0,0,0.4), 0 0 60px rgba(0,212,255,0.08);
    }
    .td-beneficio-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.1rem;
        background: linear-gradient(135deg, rgba(0,212,255,0.16), rgba(37,99,199,0.2));
        border: 1px solid rgba(0,212,255,0.25);
        color: var(--accent);
    }
    .td-beneficio-icon svg { width: 24px; height: 24px; }
    .td-beneficio-titulo {
        font-family: 'Exo 2', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.55rem;
        letter-spacing: 0.02em;
    }
    .td-beneficio-desc {
        font-size: 0.83rem;
        color: var(--ice-200);
        line-height: 1.65;
        margin: 0;
    }

    /* ── Creciendo juntos ── */
    .td-creciendo {
        position: relative;
        background:
            radial-gradient(80% 120% at 50% 0%, rgba(0,212,255,0.10), transparent 60%),
            linear-gradient(180deg, rgba(13,31,60,0.35), rgba(10,40,38,0.2));
        border-top: 1px solid rgba(74,144,217,0.12);
        border-bottom: 1px solid rgba(74,144,217,0.12);
    }
    .td-creciendo-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 1.5rem;
        text-align: center;
    }
    .td-creciendo-text {
        font-size: 1.05rem;
        color: var(--ice-200);
        line-height: 1.75;
        max-width: 680px;
        margin: 0 auto 2.2rem;
    }
    .td-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.3rem;
        max-width: 820px;
        margin: 0 auto 2.4rem;
    }
    .td-stat {
        padding: 1.5rem 1rem;
        border-radius: 16px;
        background: rgba(7,26,46,0.5);
        border: 1px solid rgba(74,144,217,0.16);
    }
    .td-stat-num {
        font-family: 'Exo 2', sans-serif;
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--accent);
        line-height: 1.1;
    }
    .td-stat-label {
        font-size: 0.75rem;
        color: var(--ice-300);
        margin-top: 0.4rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .td-creciendo-cta { display: flex; justify-content: center; }

    /* ── Oportunidades ── */
    .td-oportunidades-inner {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2rem;
        align-items: center;
        padding: 3rem 1.5rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .td-oportunidades-titulo {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(1.5rem, 3vw, 2.1rem);
        font-weight: 800;
        color: #fff;
        margin: 0 0 0.7rem;
    }
    .td-oportunidades-titulo span {
        background: linear-gradient(90deg, #00d4ff, #7ab8f5);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .td-oportunidades-texto {
        font-size: 0.98rem;
        color: var(--ice-200);
        line-height: 1.7;
        margin: 0;
        max-width: 640px;
    }

    /* ── Responsive ── */
    @media (max-width: 1024px) {
        .td-beneficios-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 900px) {
        .td-hero { padding: 3rem 0 2rem; }
        .td-hero-grid { grid-template-columns: 1fr; gap: 2.6rem; }
        .td-hero-card { order: 2; }
        .td-oportunidades-inner { grid-template-columns: 1fr; text-align: center; justify-items: center; }
    }
    @media (max-width: 560px) {
        .td-hero-cta { flex-direction: column; }
        .td-btn { width: 100%; }
        .td-beneficios-grid { grid-template-columns: 1fr; }
        .td-stats { grid-template-columns: 1fr; }
        .td-card { width: 100%; }
    }
</style>

<section class="td-page">

    {{-- ══════ HERO ══════ --}}
    <div class="td-hero">
        <div class="td-hero-grid">
            <div class="td-hero-text">
                <span class="td-kicker reveal">Gracias por visitarnos</span>
                <h1 class="td-hero-title reveal reveal-delay-1">
                    MREYZE
                    <span>Invierte. Crece. Sé parte.</span>
                </h1>
                <p class="td-hero-sub reveal reveal-delay-1">
                    Compra acciones.<br>
                    Construye el futuro.
                </p>
                <div class="td-hero-cta reveal reveal-delay-2">
                    <a href="{{ route('tarjeta.debito.solicitar') }}" class="td-btn td-btn-primary">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 2v2H4V6h16zm0 12H4v-6h16v6zM7 16h6v2H7v-2z"/></svg>
                        Solicita tu Tarjeta Débito
                    </a>
                    <a href="{{ route('proyectos') }}" class="td-btn td-btn-ghost">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        Descubre proyectos
                    </a>
                </div>
                <p class="td-disclaimer reveal reveal-delay-2">
                    <strong>MREYZE</strong><br>
                    <strong>NO SOLO MIRES EL CRECIMIENTO.</strong><br>
                    <strong>SÉ PARTE DE ÉL.</strong><br><br>
                    Construye tu futuro.<br>
                    <strong>Compra acciones.</strong><br>
                    <strong>CRECE CON MREYZE.</strong><br>
                    <em>El futuro se construye juntos.</em>
                </p>
            </div>

            <div class="td-hero-card reveal reveal-delay-1">
                <img class="td-card-img" src="{{ asset('img/tarjeta-mreyze.png') }}" alt="Tarjeta Débito MReyze">
            </div>
        </div>
    </div>

    {{-- ══════ BENEFICIOS ══════ --}}
    <div class="td-seccion">
        <div class="td-seccion-header reveal">
            <div class="td-seccion-label">Beneficios exclusivos</div>
            <h2 class="td-seccion-titulo">Accede a <span>beneficios y promociones</span> especiales</h2>
            <p class="section-desc" style="margin-top:0.8rem;">Todo lo que la Tarjeta Débito MReyze conectará con nuestra comunidad.</p>
        </div>
        <div class="td-beneficios-grid">
            <div class="td-beneficio reveal">
                <div class="td-beneficio-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                </div>
                <div class="td-beneficio-titulo">Beneficios exclusivos</div>
                <p class="td-beneficio-desc">Accede a beneficios y promociones especiales para nuestra comunidad.</p>
            </div>
            <div class="td-beneficio reveal reveal-delay-1">
                <div class="td-beneficio-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 6h-2.18c.07-.44.18-.87.18-1.33C18 2.54 16.46 1 14.67 1c-1.06 0-1.96.54-2.67 1.4L12 2.5l-.33-.4C10.96 1.54 10 1 9.33 1 7.54 1 6 2.54 6 4.33c0 .43.1.86.18 1.3H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/></svg>
                </div>
                <div class="td-beneficio-titulo">Oportunidades</div>
                <p class="td-beneficio-desc">Descubre proyectos y oportunidades disponibles dentro de MReyze.</p>
            </div>
            <div class="td-beneficio reveal reveal-delay-2">
                <div class="td-beneficio-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm7 10c0 4.52-2.98 8.69-7 9.93-4.02-1.24-7-5.41-7-9.93V6.3l7-3.11 7 3.11V11zm-8 1.17V8h2v4.17l2.59 2.59-1.41 1.41z"/></svg>
                </div>
                <div class="td-beneficio-titulo">Seguridad y confianza</div>
                <p class="td-beneficio-desc">Información clara, procesos transparentes y tecnología orientada a proteger tu experiencia.</p>
            </div>
            <div class="td-beneficio reveal reveal-delay-3">
                <div class="td-beneficio-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <div class="td-beneficio-titulo">Creciendo juntos</div>
                <p class="td-beneficio-desc">Construimos oportunidades para crecer junto a nuestra comunidad.</p>
            </div>
        </div>
    </div>

</section>
@endsection
