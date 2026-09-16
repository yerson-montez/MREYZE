@extends('welcome')

@section('title', 'Blog — MREYZE')

@section('content')

{{-- resources/views/sections/blog.blade.php --}}
{{-- Uso: @include('sections.blog') --}}

<style>
    /* ═══════════════════════════════════════
       BLOG SECTION
    ═══════════════════════════════════════ */
    .blog {
        padding: 7rem 2rem;
        background: var(--ice-900);
        position: relative;
        overflow: hidden;
    }

    .blog::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .blog-bg {
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 30% 60%, rgba(30,77,153,0.08) 0%, transparent 55%);
        pointer-events: none;
    }

    .blog-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .blog-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .blog-ver-mas {
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
    }
    .blog-ver-mas svg { width: 15px; height: 15px; transition: transform 0.2s; }
    .blog-ver-mas:hover { background: rgba(0,212,255,0.1); border-color: rgba(0,212,255,0.5); }
    .blog-ver-mas:hover svg { transform: translateX(4px); }

    /* Grid */
    .blog-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr 1fr;
        gap: 1.5rem;
    }

    /* Artículo destacado */
    .blog-card.featured {
        grid-row: span 2;
    }

    /* Tarjeta de artículo */
    .blog-card {
        background: rgba(13,31,60,0.6);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.35s;
        display: flex;
        flex-direction: column;
        text-decoration: none;
    }

    .blog-card:hover {
        border-color: rgba(0,212,255,0.3);
        transform: translateY(-5px);
        box-shadow: 0 18px 48px rgba(0,30,90,0.45), 0 0 0 1px rgba(0,212,255,0.07);
    }

    /* Preview del artículo */
    .blog-preview {
        height: 180px;
        background: rgba(10,22,40,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .blog-card.featured .blog-preview { height: 260px; }

    .blog-preview-bg {
        position: absolute;
        inset: 0;
        font-size: 5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.05;
        transition: all 0.4s;
    }

    .blog-card:hover .blog-preview-bg { opacity: 0.1; transform: scale(1.05); }

    .blog-preview-icon {
        position: relative;
        z-index: 2;
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .blog-preview-icon svg { width: 26px; height: 26px; }

    /* Categoría tag */
    .blog-cat-tag {
        position: absolute;
        bottom: 10px;
        left: 10px;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .blog-cat-tag.automatizacion { background: rgba(37,99,199,0.25); border: 1px solid rgba(37,99,199,0.4); color: var(--ice-200); }
    .blog-cat-tag.iot            { background: rgba(0,212,255,0.15); border: 1px solid rgba(0,212,255,0.35); color: var(--accent); }
    .blog-cat-tag.software       { background: rgba(20,184,166,0.15); border: 1px solid rgba(20,184,166,0.3); color: #2dd4bf; }
    .blog-cat-tag.industria      { background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; }

    /* Tiempo de lectura */
    .blog-read-time {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        background: rgba(10,22,40,0.7);
        border: 1px solid rgba(74,144,217,0.2);
        color: var(--ice-300);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .blog-read-time svg { width: 10px; height: 10px; }

    /* Cuerpo */
    .blog-body {
        padding: 1.3rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .blog-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.7rem;
    }

    .blog-fecha {
        font-size: 0.72rem;
        color: var(--ice-300);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .blog-fecha svg { width: 11px; height: 11px; }

    .blog-autor {
        font-size: 0.72rem;
        color: var(--accent);
        font-weight: 600;
    }

    .blog-titulo {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.35;
        margin-bottom: 0.6rem;
        transition: color 0.2s;
    }

    .blog-card.featured .blog-titulo { font-size: 1.15rem; }
    .blog-card:hover .blog-titulo { color: var(--ice-100); }

    .blog-extracto {
        font-size: 0.8rem;
        color: var(--ice-200);
        line-height: 1.65;
        flex: 1;
    }

    .blog-card.featured .blog-extracto { font-size: 0.875rem; }

    /* Footer del artículo */
    .blog-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.2rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(74,144,217,0.1);
    }

    .blog-tags {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .blog-tag {
        font-size: 0.65rem;
        padding: 2px 7px;
        border-radius: 4px;
        background: rgba(37,99,199,0.15);
        color: var(--ice-300);
        border: 1px solid rgba(74,144,217,0.15);
    }

    .blog-leer {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--accent);
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
        opacity: 0;
        transform: translateX(-6px);
        transition: all 0.25s;
    }
    .blog-leer svg { width: 13px; height: 13px; }
    .blog-card:hover .blog-leer { opacity: 1; transform: translateX(0); }

    /* Newsletter strip */
    .blog-newsletter {
        margin-top: 3rem;
        padding: 2.5rem;
        background: rgba(13,31,60,0.5);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .blog-newsletter-text {}
    .blog-newsletter-title {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.3rem;
    }
    .blog-newsletter-desc { font-size: 0.83rem; color: var(--ice-200); }

    .blog-newsletter-form {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .blog-newsletter-input {
        padding: 0.65rem 1.2rem;
        border-radius: 8px;
        background: rgba(10,22,40,0.6);
        border: 1px solid rgba(74,144,217,0.25);
        color: #fff;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.22s;
        min-width: 240px;
        font-family: 'Inter', sans-serif;
    }
    .blog-newsletter-input::placeholder { color: var(--ice-300); }
    .blog-newsletter-input:focus { border-color: rgba(0,212,255,0.5); }

    .blog-newsletter-btn {
        padding: 0.65rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--ice-500), var(--ice-400));
        border: none;
        cursor: pointer;
        transition: all 0.25s;
        font-family: 'Inter', sans-serif;
    }
    .blog-newsletter-btn:hover {
        background: linear-gradient(135deg, var(--ice-400), var(--glow));
        box-shadow: 0 4px 16px rgba(59,130,246,0.4);
    }

    @media (max-width: 1024px) { .blog-grid { grid-template-columns: 1fr 1fr; } .blog-card.featured { grid-row: span 1; } }
    @media (max-width: 640px)  { .blog-grid { grid-template-columns: 1fr; } }
</style>

<style>
    /* Editorial layer: hierarchy for the existing article collection. */
    .blog {
        padding: clamp(4rem, 8vw, 7rem) clamp(1rem, 4vw, 2rem);
        background:
            radial-gradient(circle at 15% 0%, rgba(20, 216, 255, .08), transparent 28%),
            linear-gradient(180deg, #071522 0%, #091d30 100%);
    }
    .blog-container { width: min(100%, 78rem); }
    .blog-header { margin-bottom: 2.25rem; }
    .blog-ver-mas { min-height: 44px; }
    .blog-grid { grid-template-columns: minmax(0, 1.45fr) repeat(2, minmax(0, 1fr)); gap: 1rem; }
    .blog-card { border-radius: 1rem; background: rgba(8, 25, 41, .82); border-color: rgba(130, 190, 240, .14); }
    .blog-card:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(0, 0, 0, .2); }
    .blog-preview { aspect-ratio: 16 / 9; height: auto; }
    .blog-card.featured .blog-preview { aspect-ratio: 16 / 10; height: auto; }
    .blog-body { padding: clamp(1rem, 2vw, 1.4rem); }
    .blog-titulo { font-size: 1.05rem; }
    .blog-card.featured .blog-titulo { font-size: clamp(1.25rem, 2.2vw, 1.8rem); line-height: 1.15; }
    .blog-extracto { color: #afc4d7; }
    .blog-leer { opacity: 1; transform: none; }
    .blog-newsletter { padding: clamp(1.25rem, 4vw, 2.5rem); border-radius: 1rem; background: rgba(8, 25, 41, .78); }
    .blog-newsletter-input, .blog-newsletter-btn { min-height: 44px; }

    @media (max-width: 1024px) {
        .blog-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .blog-card.featured { grid-column: 1 / -1; }
    }
    @media (max-width: 640px) {
        .blog-header { align-items: stretch; }
        .blog-ver-mas { justify-content: center; width: 100%; }
        .blog-grid { grid-template-columns: 1fr; }
        .blog-card.featured { grid-column: auto; }
        .blog-newsletter-form, .blog-newsletter-input, .blog-newsletter-btn { width: 100%; }
    }
</style>

<section class="blog" id="blog">
    <div class="blog-bg"></div>
    <div class="blog-container">

        {{-- Header --}}
        <div class="blog-header reveal">
            <div>
                <div class="section-label">Conocimiento</div>
                <h2 class="section-title">Blog <span>Técnico</span></h2>
            </div>
            <a href="{{ url('/blog') }}" class="blog-ver-mas">
                Ver todos los artículos
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
            </a>
        </div>

        {{-- Grid de artículos --}}
        <div class="blog-grid">

            {{-- Artículo destacado --}}
            <a href="{{ url('/blog/industria-4-0-peru') }}" class="blog-card featured reveal">
                <div class="blog-preview">
                    <div class="blog-preview-bg">🏭</div>
                    <div class="blog-preview-icon" style="background:rgba(30,77,153,0.3);border:1px solid rgba(37,99,199,0.4)">
                        <svg viewBox="0 0 24 24" fill="rgba(122,184,245,0.9)"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                    </div>
                    <span class="blog-cat-tag industria">Industria 4.0</span>
                    <span class="blog-read-time"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.5 5v5.25l4.5 2.67-.75 1.23L11 13V7h1.5z"/></svg>8 min</span>
                </div>
                <div class="blog-body">
                    <div class="blog-meta">
                        <span class="blog-fecha"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/></svg>Feb 2026</span>
                        <span class="blog-autor">Ing. Electrónico</span>
                    </div>
                    <div class="blog-titulo">Industria 4.0 en Perú: Cómo la Automatización está Transformando las Empresas Peruanas</div>
                    <p class="blog-extracto">La cuarta revolución industrial ya llegó al Perú. Empresas manufactureras, agroindustriales y de servicios están adoptando tecnologías de automatización, IoT y análisis de datos para mejorar su competitividad. En este artículo exploramos casos reales y oportunidades para el mercado peruano.</p>
                    <div class="blog-card-footer">
                        <div class="blog-tags">
                            <span class="blog-tag">Automatización</span>
                            <span class="blog-tag">Perú</span>
                            <span class="blog-tag">Industria</span>
                        </div>
                        <span class="blog-leer">Leer<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></span>
                    </div>
                </div>
            </a>

            {{-- Artículo 2 --}}
            <a href="{{ url('/blog/esp32-monitor-temperatura') }}" class="blog-card reveal reveal-delay-1">
                <div class="blog-preview">
                    <div class="blog-preview-bg">📡</div>
                    <div class="blog-preview-icon" style="background:rgba(0,212,255,0.1);border:1px solid rgba(0,212,255,0.3)">
                        <svg viewBox="0 0 24 24" fill="rgba(0,212,255,0.9)"><path d="M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z"/></svg>
                    </div>
                    <span class="blog-cat-tag iot">IoT</span>
                    <span class="blog-read-time"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.5 5v5.25l4.5 2.67-.75 1.23L11 13V7h1.5z"/></svg>5 min</span>
                </div>
                <div class="blog-body">
                    <div class="blog-meta">
                        <span class="blog-fecha"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/></svg>Ene 2026</span>
                        <span class="blog-autor">Ing. Electrónico</span>
                    </div>
                    <div class="blog-titulo">Monitor de Temperatura con ESP32 y Dashboard Web en Tiempo Real</div>
                    <p class="blog-extracto">Tutorial completo para crear un sistema de monitoreo de temperatura usando ESP32, sensor DHT22 y una aplicación web con Laravel y Chart.js.</p>
                    <div class="blog-card-footer">
                        <div class="blog-tags"><span class="blog-tag">ESP32</span><span class="blog-tag">Tutorial</span></div>
                        <span class="blog-leer">Leer<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></span>
                    </div>
                </div>
            </a>

            {{-- Artículo 3 --}}
            <a href="{{ url('/blog/plc-vs-arduino') }}" class="blog-card reveal reveal-delay-2">
                <div class="blog-preview">
                    <div class="blog-preview-bg">⚡</div>
                    <div class="blog-preview-icon" style="background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.25)">
                        <svg viewBox="0 0 24 24" fill="rgba(251,191,36,0.9)"><path d="M13 3L4 14h7l-1 7 9-11h-7l1-7z"/></svg>
                    </div>
                    <span class="blog-cat-tag automatizacion">Automatización</span>
                    <span class="blog-read-time"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.5 5v5.25l4.5 2.67-.75 1.23L11 13V7h1.5z"/></svg>6 min</span>
                </div>
                <div class="blog-body">
                    <div class="blog-meta">
                        <span class="blog-fecha"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/></svg>Ene 2026</span>
                        <span class="blog-autor">Ing. Sistemas</span>
                    </div>
                    <div class="blog-titulo">PLC vs Arduino vs ESP32: ¿Cuál elegir para tu proyecto industrial?</div>
                    <p class="blog-extracto">Comparativa técnica y práctica de las tres plataformas más populares para automatización. Cuándo usar cada una y por qué.</p>
                    <div class="blog-card-footer">
                        <div class="blog-tags"><span class="blog-tag">PLC</span><span class="blog-tag">Arduino</span></div>
                        <span class="blog-leer">Leer<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></span>
                    </div>
                </div>
            </a>

        </div>

        {{-- Newsletter --}}
        <div class="blog-newsletter reveal">
            <div class="blog-newsletter-text">
                <div class="blog-newsletter-title">📬 Suscríbete al blog técnico</div>
                <p class="blog-newsletter-desc">Artículos de automatización, IoT y software directo a tu correo. Sin spam.</p>
            </div>
            <div class="blog-newsletter-form">
                <input type="email" class="blog-newsletter-input" placeholder="tu@correo.com">
                <button class="blog-newsletter-btn">Suscribirme</button>
            </div>
        </div>

    </div>
</section>
@endsection