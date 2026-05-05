@extends('welcome')

@section('title', 'Nosotros — MREYZE')

@section('content')



{{-- resources/views/sections/nosotros.blade.php --}}
{{-- Uso: @include('sections.nosotros') --}}

<style>
    /* ═══════════════════════════════════════
       NOSOTROS SECTION
    ═══════════════════════════════════════ */
    .nosotros {
        padding: 7rem 2rem;
        background: var(--ice-900);
        position: relative;
        overflow: hidden;
    }

    .nosotros::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    /* Fondo sutil */
    .nosotros-bg {
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 80% 50%, rgba(30,77,153,0.1) 0%, transparent 60%);
        pointer-events: none;
    }

    .nosotros-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* Encabezado de sección */
    .section-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 1rem;
    }

    .section-label::before {
        content: '';
        width: 24px;
        height: 2px;
        background: var(--accent);
        border-radius: 2px;
    }

    .section-title {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        font-weight: 700;
        color: #fff;
        line-height: 1.15;
        letter-spacing: -0.02em;
        margin-bottom: 1rem;
    }

    .section-title span {
        background: linear-gradient(135deg, var(--ice-200), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .section-desc {
        font-size: 1rem;
        color: var(--ice-200);
        line-height: 1.7;
        max-width: 520px;
    }

    /* Layout */
    .nosotros-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5rem;
        align-items: center;
        margin-top: 4rem;
    }

    /* Columna izquierda — texto */
    .nosotros-text {}

    .nosotros-paragraph {
        font-size: 1rem;
        color: var(--ice-200);
        line-height: 1.8;
        margin-bottom: 1.2rem;
    }

    /* Valores */
    .nosotros-values {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 2rem;
    }

    .nosotros-value-card {
        padding: 1.2rem;
        background: rgba(13,31,60,0.5);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 12px;
        transition: all 0.3s;
        cursor: default;
    }

    .nosotros-value-card:hover {
        background: rgba(30,77,153,0.2);
        border-color: rgba(0,212,255,0.3);
        transform: translateY(-3px);
    }

    .nosotros-value-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(0,212,255,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.7rem;
    }

    .nosotros-value-icon svg { width: 18px; height: 18px; color: var(--accent); }

    .nosotros-value-name {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 4px;
    }

    .nosotros-value-desc {
        font-size: 0.78rem;
        color: var(--ice-300);
        line-height: 1.5;
    }

    /* Columna derecha — equipo + misión/visión */
    .nosotros-right {}

    /* Tarjetas del equipo */
    .nosotros-team {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .team-card {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        padding: 1.2rem 1.5rem;
        background: rgba(13,31,60,0.6);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 14px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .team-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, var(--ice-500), var(--accent));
        border-radius: 3px;
    }

    .team-card:hover {
        background: rgba(30,77,153,0.2);
        border-color: rgba(74,144,217,0.35);
        transform: translateX(6px);
    }

    .team-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--ice-600), var(--ice-500));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Exo 2', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        border: 2px solid rgba(0,212,255,0.25);
    }

    .team-info {}

    .team-name {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 3px;
    }

    .team-role {
        font-size: 0.8rem;
        color: var(--accent);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .team-skills {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .team-skill-tag {
        font-size: 0.68rem;
        padding: 2px 8px;
        border-radius: 20px;
        background: rgba(37,99,199,0.2);
        border: 1px solid rgba(74,144,217,0.25);
        color: var(--ice-200);
    }

    /* Misión/Visión tabs */
    .mv-tabs {
        display: flex;
        gap: 0;
        background: rgba(13,31,60,0.5);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 12px;
        overflow: hidden;
    }

    .mv-tab {
        flex: 1;
        padding: 0.75rem 1rem;
        text-align: center;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--ice-300);
        cursor: pointer;
        transition: all 0.25s;
        border: none;
        background: transparent;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-family: 'Exo 2', sans-serif;
    }

    .mv-tab.active {
        background: rgba(30,77,153,0.4);
        color: var(--accent);
        border-bottom: 2px solid var(--accent);
    }

    .mv-content {
        padding: 1.5rem;
        background: rgba(13,31,60,0.3);
        border: 1px solid rgba(74,144,217,0.12);
        border-top: none;
        border-radius: 0 0 12px 12px;
        font-size: 0.9rem;
        color: var(--ice-200);
        line-height: 1.7;
        min-height: 80px;
    }

    .mv-panel { display: none; }
    .mv-panel.active { display: block; animation: fadeIn 0.3s ease; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Reveal on scroll */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.7s ease, transform 0.7s ease;
    }
    .reveal.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .reveal-delay-1 { transition-delay: 0.1s; }
    .reveal-delay-2 { transition-delay: 0.2s; }
    .reveal-delay-3 { transition-delay: 0.3s; }

    @media (max-width: 900px) {
        .nosotros-grid { grid-template-columns: 1fr; gap: 3rem; }
        .nosotros-values { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 500px) {
        .nosotros-values { grid-template-columns: 1fr; }
    }
</style>

<section class="nosotros" id="nosotros">
    <div class="nosotros-bg"></div>
    <div class="nosotros-container">

        {{-- Header --}}
        <div class="reveal">
            <div class="section-label">Quiénes somos</div>
            <h2 class="section-title">Ingeniería que <span>transforma</span><br>industrias</h2>
        </div>

        <div class="nosotros-grid">

            {{-- Columna izquierda --}}
            <div class="nosotros-text reveal reveal-delay-1">
                <p class="nosotros-paragraph">
                    Somos una empresa peruana de tecnología especializada en automatización industrial,
                    IoT, electrónica embebida y desarrollo de software a medida. Nacimos de la unión
                    de dos ingenieros con una visión clara: llevar la Industria 4.0 a empresas de todos los tamaños.
                </p>
                <p class="nosotros-paragraph">
                    Combinamos experiencia en electrónica de control con ingeniería de sistemas modernos,
                    ofreciendo soluciones integrales que van desde el sensor hasta la nube.
                </p>

                <div class="nosotros-values">
                    <div class="nosotros-value-card">
                        <div class="nosotros-value-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <div class="nosotros-value-name">Calidad</div>
                        <div class="nosotros-value-desc">Soluciones robustas y probadas en campo real.</div>
                    </div>
                    <div class="nosotros-value-card">
                        <div class="nosotros-value-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 3L4 14h7l-1 7 9-11h-7l1-7z"/></svg>
                        </div>
                        <div class="nosotros-value-name">Innovación</div>
                        <div class="nosotros-value-desc">Tecnología de punta aplicada a tu negocio.</div>
                    </div>
                    <div class="nosotros-value-card">
                        <div class="nosotros-value-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        </div>
                        <div class="nosotros-value-name">Compromiso</div>
                        <div class="nosotros-value-desc">Acompañamos tu proyecto de inicio a fin.</div>
                    </div>
                    <div class="nosotros-value-card">
                        <div class="nosotros-value-icon">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                        </div>
                        <div class="nosotros-value-name">Confianza</div>
                        <div class="nosotros-value-desc">Transparencia y soporte continuo siempre.</div>
                    </div>
                </div>
            </div>

            {{-- Columna derecha --}}
            <div class="nosotros-right reveal reveal-delay-2">

                {{-- Equipo --}}
                <div class="nosotros-team">
                    <div class="team-card">
                        <div class="team-avatar">IE</div>
                        <div class="team-info">
                            <div class="team-name">Ing. Electrónico</div>
                            <div class="team-role">Hardware & Control Industrial</div>
                            <div class="team-skills">
                                <span class="team-skill-tag">PLC</span>
                                <span class="team-skill-tag">SCADA</span>
                                <span class="team-skill-tag">IoT</span>
                                <span class="team-skill-tag">ESP32</span>
                            </div>
                        </div>
                    </div>
                    <div class="team-card">
                        <div class="team-avatar">IS</div>
                        <div class="team-info">
                            <div class="team-name">Ing. de Sistemas</div>
                            <div class="team-role">Software & Arquitectura Web</div>
                            <div class="team-skills">
                                <span class="team-skill-tag">Laravel</span>
                                <span class="team-skill-tag">React</span>
                                <span class="team-skill-tag">APIs</span>
                                <span class="team-skill-tag">BD</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Misión / Visión --}}
                <div class="mv-tabs">
                    <button class="mv-tab active" onclick="mvSwitch('mision', this)">Misión</button>
                    <button class="mv-tab" onclick="mvSwitch('vision', this)">Visión</button>
                    <button class="mv-tab" onclick="mvSwitch('objetivo', this)">Objetivo</button>
                </div>
                <div class="mv-content">
                    <div class="mv-panel active" id="mv-mision">
                        Brindar soluciones tecnológicas integrales de automatización, IoT y software que optimicen
                        los procesos industriales y empresariales de nuestros clientes, con un enfoque en calidad,
                        innovación y soporte continuo.
                    </div>
                    <div class="mv-panel" id="mv-vision">
                        Ser la empresa tecnológica de referencia en Perú para soluciones de Industria 4.0,
                        reconocida por la excelencia técnica de nuestros ingenieros y el impacto real
                        que generamos en la transformación digital de las empresas.
                    </div>
                    <div class="mv-panel" id="mv-objetivo">
                        Conectar el mundo físico con el digital a través de sistemas inteligentes,
                        haciendo que la automatización y el software de alto nivel sean accesibles
                        para empresas de todos los tamaños en el país.
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    function mvSwitch(panel, btn) {
        document.querySelectorAll('.mv-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.mv-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('mv-' + panel).classList.add('active');
        btn.classList.add('active');
    }
</script>
@endsection