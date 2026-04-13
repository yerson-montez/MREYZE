{{-- resources/views/sections/inicio.blade.php --}}
{{-- Uso: @include('sections.inicio') --}}

<style>
    /* ═══════════════════════════════════════
       HERO SECTION
    ═══════════════════════════════════════ */
    .hero {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        overflow: hidden;
        background: var(--ice-900);
    }

    /* Fondo con malla de puntos */
    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 20% 50%, rgba(30, 77, 153, 0.18) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(0, 212, 255, 0.08) 0%, transparent 40%),
            radial-gradient(circle at 60% 80%, rgba(37, 99, 199, 0.12) 0%, transparent 40%);
        pointer-events: none;
    }

    /* Grid punteado de fondo */
    .hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(74, 144, 217, 0.15) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
        mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%);
    }

    .hero-container {
        position: relative;
        z-index: 2;
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        width: 100%;
    }

    /* Lado izquierdo */
    .hero-content {
        animation: heroFadeUp 0.9s ease both;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.35rem 1rem;
        border-radius: 30px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.25);
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--accent);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
    }

    .hero-badge-dot {
        width: 6px;
        height: 6px;
        background: var(--accent);
        border-radius: 50%;
        box-shadow: 0 0 8px var(--accent);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }

    .hero-title {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(2.4rem, 5vw, 4rem);
        font-weight: 700;
        line-height: 1.1;
        color: #fff;
        margin-bottom: 1.5rem;
        letter-spacing: -0.02em;
    }

    .hero-title-line {
        display: block;
    }

    .hero-title-accent {
        display: block;
        background: linear-gradient(135deg, var(--ice-200), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-description {
        font-size: 1.05rem;
        line-height: 1.7;
        color: var(--ice-200);
        margin-bottom: 2.5rem;
        max-width: 480px;
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .hero-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 0.85rem 2rem;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--ice-500), var(--ice-400));
        border: 1px solid rgba(74, 144, 217, 0.4);
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 6px 24px rgba(30, 77, 153, 0.5);
        letter-spacing: 0.02em;
    }

    .hero-btn-primary:hover {
        background: linear-gradient(135deg, var(--ice-400), var(--glow));
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.6), 0 0 0 1px rgba(0,212,255,0.3);
        transform: translateY(-2px);
        color: #fff;
    }

    .hero-btn-primary svg { width: 18px; height: 18px; }

    .hero-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 0.85rem 2rem;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--ice-100);
        background: transparent;
        border: 1px solid rgba(74, 144, 217, 0.3);
        text-decoration: none;
        transition: all 0.3s;
    }

    .hero-btn-secondary:hover {
        background: rgba(37, 99, 199, 0.15);
        border-color: rgba(74, 144, 217, 0.6);
        color: #fff;
        transform: translateY(-2px);
    }

    .hero-btn-secondary svg { width: 18px; height: 18px; }

    /* Stats */
    .hero-stats {
        display: flex;
        gap: 2.5rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(74, 144, 217, 0.15);
        animation: heroFadeUp 0.9s 0.3s ease both;
    }

    .hero-stat-number {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    .hero-stat-number span {
        color: var(--accent);
    }

    .hero-stat-label {
        font-size: 0.78rem;
        color: var(--ice-300);
        letter-spacing: 0.05em;
        margin-top: 4px;
    }

    /* Lado derecho — tarjeta flotante animada */
    .hero-visual {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: heroFadeUp 0.9s 0.15s ease both;
    }

    .hero-card-main {
        position: relative;
        width: 100%;
        max-width: 420px;
        background: rgba(13, 31, 60, 0.7);
        border: 1px solid rgba(74, 144, 217, 0.2);
        border-radius: 20px;
        padding: 2rem;
        backdrop-filter: blur(20px);
        box-shadow: 0 24px 64px rgba(0, 30, 90, 0.5), 0 0 0 1px rgba(0,212,255,0.05);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }

    .hero-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(74, 144, 217, 0.12);
    }

    .hero-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--ice-500), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(0,212,255,0.3);
    }

    .hero-card-icon svg { width: 22px; height: 22px; fill: white; }

    .hero-card-title {
        font-family: 'Exo 2', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        color: #fff;
    }

    .hero-card-subtitle {
        font-size: 0.75rem;
        color: var(--ice-300);
        margin-top: 2px;
    }

    /* Barras de progreso animadas */
    .hero-progress-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .hero-progress-item {}

    .hero-progress-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .hero-progress-name {
        font-size: 0.8rem;
        color: var(--ice-100);
        font-weight: 500;
    }

    .hero-progress-pct {
        font-size: 0.8rem;
        color: var(--accent);
        font-weight: 600;
        font-family: 'Exo 2', sans-serif;
    }

    .hero-progress-bar {
        height: 6px;
        background: rgba(74, 144, 217, 0.15);
        border-radius: 10px;
        overflow: hidden;
    }

    .hero-progress-fill {
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(90deg, var(--ice-500), var(--accent));
        box-shadow: 0 0 8px rgba(0,212,255,0.4);
        width: 0;
        transition: width 1.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hero-progress-fill.animate { width: var(--target-width); }

    /* Tarjetas flotantes pequeñas */
    .hero-badge-float {
        position: absolute;
        background: rgba(13, 31, 60, 0.9);
        border: 1px solid rgba(74, 144, 217, 0.25);
        border-radius: 12px;
        padding: 0.65rem 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        color: #fff;
        font-weight: 500;
        backdrop-filter: blur(12px);
        box-shadow: 0 8px 24px rgba(0, 20, 60, 0.4);
        white-space: nowrap;
    }

    .hero-badge-float svg { width: 16px; height: 16px; color: var(--accent); flex-shrink: 0; }

    .hero-badge-float-1 {
        top: -20px;
        right: -20px;
        animation: float 5s 1s ease-in-out infinite;
    }

    .hero-badge-float-2 {
        bottom: -20px;
        left: -20px;
        animation: float 5s 2s ease-in-out infinite;
    }

    /* Línea brillante en el borde */
    .hero-card-main::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(0,212,255,0.3), transparent, rgba(37,99,199,0.3));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    @keyframes heroFadeUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Scroll indicator */
    .hero-scroll {
        position: absolute;
        bottom: 2.5rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        color: var(--ice-300);
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        animation: heroFadeUp 1s 0.6s ease both;
        z-index: 2;
    }

    .hero-scroll-line {
        width: 1px;
        height: 40px;
        background: linear-gradient(to bottom, var(--accent), transparent);
        animation: scrollLine 2s ease-in-out infinite;
    }

    @keyframes scrollLine {
        0% { transform: scaleY(0); transform-origin: top; }
        50% { transform: scaleY(1); transform-origin: top; }
        51% { transform: scaleY(1); transform-origin: bottom; }
        100% { transform: scaleY(0); transform-origin: bottom; }
    }

    /* Responsive */
    @media (max-width: 900px) {
        .hero-container {
            grid-template-columns: 1fr;
            text-align: center;
            padding-top: 4rem;
            padding-bottom: 4rem;
        }
        .hero-description { margin: 0 auto 2.5rem; }
        .hero-actions { justify-content: center; }
        .hero-stats { justify-content: center; }
        .hero-visual { display: none; }
    }
</style>

<section class="hero" id="inicio">

    <div class="hero-container">

        {{-- ── Contenido izquierdo ── --}}
        <div class="hero-content">

            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Industria 4.0 · Soluciones Tecnológicas
            </div>

            <h1 class="hero-title">
                <span class="hero-title-line">Automatización,</span>
                <span class="hero-title-line">IoT y Software</span>
                <span class="hero-title-accent">a tu medida</span>
            </h1>

            <p class="hero-description">
                Transformamos procesos industriales y empresas mediante automatización inteligente,
                electrónica embebida y desarrollo de software a medida. Tu visión, nuestra ingeniería.
            </p>

            <div class="hero-actions">
                <a href="{{ url('/contacto') }}" class="hero-btn-primary">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    Solicitar Cotización
                </a>
                <a href="{{ url('/servicios') }}" class="hero-btn-secondary">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    Ver Servicios
                </a>
            </div>

            <div class="hero-stats">
                <div>
                    <div class="hero-stat-number">+50<span>%</span></div>
                    <div class="hero-stat-label">Reducción de costos</div>
                </div>
                <div>
                    <div class="hero-stat-number">24<span>/7</span></div>
                    <div class="hero-stat-label">Monitoreo en tiempo real</div>
                </div>
                <div>
                    <div class="hero-stat-number">100<span>%</span></div>
                    <div class="hero-stat-label">Soluciones a medida</div>
                </div>
            </div>

        </div>

        {{-- ── Visual derecho ── --}}
        <div class="hero-visual">

            {{-- Badge flotante superior --}}
            <div class="hero-badge-float hero-badge-float-1">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z"/></svg>
                Conectado en tiempo real
            </div>

            {{-- Tarjeta principal --}}
            <div class="hero-card-main">
                <div class="hero-card-header">
                    <div class="hero-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                    </div>
                    <div>
                        <div class="hero-card-title">Panel de Control Industrial</div>
                        <div class="hero-card-subtitle">Sistema activo · 4 sensores conectados</div>
                    </div>
                </div>

                <div class="hero-progress-list">
                    <div class="hero-progress-item">
                        <div class="hero-progress-info">
                            <span class="hero-progress-name">Automatización PLC</span>
                            <span class="hero-progress-pct">92%</span>
                        </div>
                        <div class="hero-progress-bar">
                            <div class="hero-progress-fill" style="--target-width: 92%"></div>
                        </div>
                    </div>
                    <div class="hero-progress-item">
                        <div class="hero-progress-info">
                            <span class="hero-progress-name">Monitoreo IoT</span>
                            <span class="hero-progress-pct">87%</span>
                        </div>
                        <div class="hero-progress-bar">
                            <div class="hero-progress-fill" style="--target-width: 87%"></div>
                        </div>
                    </div>
                    <div class="hero-progress-item">
                        <div class="hero-progress-info">
                            <span class="hero-progress-name">Eficiencia del Sistema</span>
                            <span class="hero-progress-pct">95%</span>
                        </div>
                        <div class="hero-progress-bar">
                            <div class="hero-progress-fill" style="--target-width: 95%"></div>
                        </div>
                    </div>
                    <div class="hero-progress-item">
                        <div class="hero-progress-info">
                            <span class="hero-progress-name">Desarrollo de Software</span>
                            <span class="hero-progress-pct">78%</span>
                        </div>
                        <div class="hero-progress-bar">
                            <div class="hero-progress-fill" style="--target-width: 78%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Badge flotante inferior --}}
            <div class="hero-badge-float hero-badge-float-2">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                Sistema seguro y estable
            </div>

        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="hero-scroll">
        <div class="hero-scroll-line"></div>
        scroll
    </div>

</section>

<script>
    // Animar barras de progreso cuando el hero es visible
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('.hero-progress-fill').forEach(bar => {
                bar.classList.add('animate');
            });
        }, 600);
    });
</script>