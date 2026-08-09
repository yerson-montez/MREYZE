@extends('welcome')

@section('title', 'Inicio — MREYZE')

@section('content')
{{-- resources/views/sections/inicio.blade.php --}}
{{-- Uso: @include('sections.inicio') --}}

<style>
    /* ═══════════════════════════════════════
       TECH HERO SECTION — Ingeniería 4.0
    ═══════════════════════════════════════ */

    @import url('https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700&family=Share+Tech+Mono&display=swap');

    :root {
        --bg:      #050d1a;
        --bg2:     #0a1628;
        --blue:    #1e4d99;
        --cyan:    #00d4ff;
        --glow:    #3b82f6;
        --ice1:    #e0eeff;
        --ice2:    #b3cff5;
        --ice3:    #7aabec;
    }

    /* ── CANVAS BG ── */
    #bgCanvas {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
    }

    /* ── HERO ── */
    .hero {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: var(--bg);
        overflow: hidden;
        z-index: 1;
    }

    .hero-grid {
        position: relative;
        z-index: 2;
        max-width: 1280px;
        margin: 0 auto;
        padding: 6rem 2rem 4rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        width: 100%;
    }

    /* ── LEFT ── */
    .hero-left { animation: fadeUp .8s ease both; }

    .badge-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: .3rem .9rem;
        background: rgba(0,212,255,.07);
        border: 1px solid rgba(0,212,255,.25);
        border-radius: 30px;
        font-family: 'Share Tech Mono', monospace;
        font-size: .72rem;
        color: var(--cyan);
        letter-spacing: .1em;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
    }
    .badge-dot {
        width: 6px; height: 6px;
        background: var(--cyan);
        border-radius: 50%;
        box-shadow: 0 0 8px var(--cyan);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%,100% { opacity:1; transform:scale(1); }
        50%      { opacity:.4; transform:scale(1.4); }
    }

    .hero-title {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(2.2rem, 4.5vw, 3.8rem);
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 1.4rem;
        letter-spacing: -.02em;
        color: #fff;
    }
    .title-line   { display: block; }
    .title-accent {
        display: block;
        background: linear-gradient(120deg, #7bc8ff, var(--cyan));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Terminal typewriter */
    .terminal-line {
        font-family: 'Share Tech Mono', monospace;
        font-size: .78rem;
        color: #4ade80;
        background: rgba(0,20,8,.5);
        border: 1px solid rgba(74,222,128,.15);
        border-radius: 6px;
        padding: .4rem .9rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .term-prompt { color: var(--cyan); }
    .cursor {
        display: inline-block;
        width: 7px; height: 14px;
        background: #4ade80;
        animation: blink .7s infinite;
        vertical-align: middle;
    }
    @keyframes blink { 0%,49%{opacity:1} 50%,100%{opacity:0} }

    .hero-desc {
        font-family: 'Exo 2', sans-serif;
        font-size: 1rem;
        line-height: 1.75;
        color: var(--ice2);
        max-width: 460px;
        margin-bottom: 2.2rem;
    }

    .hero-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: .8rem 1.8rem;
        border-radius: 10px;
        font-family: 'Exo 2', sans-serif;
        font-size: .9rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, #1e4d99, #2563c7);
        border: 1px solid rgba(74,144,217,.5);
        text-decoration: none;
        letter-spacing: .03em;
        transition: all .25s;
        box-shadow: 0 4px 20px rgba(30,77,153,.5);
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #2563c7, #00b8d9);
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(59,130,246,.6);
        color: #fff;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: .8rem 1.8rem;
        border-radius: 10px;
        font-family: 'Exo 2', sans-serif;
        font-size: .9rem;
        font-weight: 500;
        color: var(--ice1);
        background: transparent;
        border: 1px solid rgba(74,144,217,.3);
        text-decoration: none;
        transition: all .25s;
    }
    .btn-secondary:hover {
        background: rgba(37,99,199,.15);
        border-color: rgba(74,144,217,.6);
        transform: translateY(-2px);
        color: #fff;
    }

    /* Stats */
    .stats-row {
        display: flex;
        gap: 2.5rem;
        padding-top: 1.8rem;
        border-top: 1px solid rgba(74,144,217,.15);
        animation: fadeUp .8s .3s ease both;
    }
    .stat-num {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }
    .stat-num em { color: var(--cyan); font-style: normal; }
    .stat-label {
        font-family: 'Share Tech Mono', monospace;
        font-size: .7rem;
        color: var(--ice3);
        letter-spacing: .05em;
        margin-top: 4px;
    }

    /* ── RIGHT — TECH PANEL ── */
    .hero-right {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeUp .8s .15s ease both;
    }

    .tech-panel {
        width: 100%;
        max-width: 430px;
        background: rgba(8,20,45,.75);
        border: 1px solid rgba(74,144,217,.2);
        border-radius: 18px;
        padding: 1.5rem;
        backdrop-filter: blur(18px);
        animation: float 6s ease-in-out infinite;
        position: relative;
        box-shadow: 0 24px 64px rgba(0,20,70,.5);
    }
    .tech-panel::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(0,212,255,.3), transparent 40%, rgba(30,77,153,.4) 80%, transparent);
        z-index: -1;
        pointer-events: none;
    }

    @keyframes float {
        0%,100% { transform: translateY(0); }
        50%      { transform: translateY(-10px); }
    }

    /* Window bar */
    .panel-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 1.2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(74,144,217,.12);
    }
    .w-dot { width:10px; height:10px; border-radius:50%; }
    .w-dot.r { background:#ff5f57; }
    .w-dot.y { background:#febc2e; }
    .w-dot.g { background:#28c840; }
    .panel-title {
        font-family: 'Share Tech Mono', monospace;
        font-size: .75rem;
        color: var(--ice3);
        margin-left: 8px;
        flex: 1;
    }
    .panel-status {
        font-family: 'Share Tech Mono', monospace;
        font-size: .65rem;
        color: #4ade80;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .status-dot {
        width:5px; height:5px;
        border-radius: 50%;
        background: #4ade80;
        box-shadow: 0 0 6px #4ade80;
        animation: pulse 2s infinite;
    }

    /* Oscilloscope canvas */
    #oscCanvas {
        width: 100%;
        height: 70px;
        border-radius: 8px;
        background: rgba(0,15,8,.6);
        border: 1px solid rgba(74,222,128,.12);
        margin-bottom: 1.2rem;
        display: block;
    }

    /* Metrics grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1.2rem;
    }
    .metric-card {
        background: rgba(14,28,56,.7);
        border: 1px solid rgba(74,144,217,.15);
        border-radius: 10px;
        padding: .75rem;
    }
    .metric-icon {
        font-family: 'Share Tech Mono', monospace;
        font-size: .75rem;
        color: var(--cyan);
        margin-bottom: 4px;
    }
    .metric-val {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }
    .metric-val span { color: var(--cyan); font-size: .85rem; }
    .metric-name {
        font-family: 'Share Tech Mono', monospace;
        font-size: .6rem;
        color: var(--ice3);
        margin-top: 4px;
        letter-spacing: .04em;
    }

    /* Progress bars */
    .prog-list { display: flex; flex-direction: column; gap: .7rem; }
    .prog-head  { display: flex; justify-content: space-between; margin-bottom: 5px; }
    .prog-name  { font-family: 'Share Tech Mono', monospace; font-size: .68rem; color: var(--ice2); }
    .prog-pct   { font-family: 'Share Tech Mono', monospace; font-size: .68rem; color: var(--cyan); }
    .prog-track { height: 5px; background: rgba(74,144,217,.12); border-radius: 10px; overflow: hidden; }
    .prog-fill  {
        height: 100%;
        border-radius: 10px;
        background: linear-gradient(90deg, #1e4d99, var(--cyan));
        box-shadow: 0 0 8px rgba(0,212,255,.35);
        width: 0;
        transition: width 1.8s cubic-bezier(.4,0,.2,1);
    }
    .prog-fill.go { width: var(--w); }

    /* Floating chips */
    .chip-float {
        position: absolute;
        background: rgba(8,20,45,.92);
        border: 1px solid rgba(74,144,217,.25);
        border-radius: 10px;
        padding: .55rem .9rem;
        display: flex;
        align-items: center;
        gap: 7px;
        font-family: 'Share Tech Mono', monospace;
        font-size: .72rem;
        color: #fff;
        white-space: nowrap;
        backdrop-filter: blur(10px);
        box-shadow: 0 6px 20px rgba(0,15,50,.4);
    }
    .chip-ico  { color: var(--cyan); }
    .chip-top  { top:-22px; right:-18px; animation: float 5s 1s ease-in-out infinite; }
    .chip-bot  { bottom:-22px; left:-18px; animation: float 5s 2s ease-in-out infinite; }

    /* Scroll indicator */
    .hero-scroll {
        position: absolute;
        bottom: 2rem; left: 50%;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        z-index: 2;
    }
    .scroll-line {
        width: 1px; height: 38px;
        background: linear-gradient(to bottom, var(--cyan), transparent);
        animation: scrl 2s ease-in-out infinite;
    }
    @keyframes scrl {
        0%   { transform:scaleY(0); transform-origin:top; }
        50%  { transform:scaleY(1); transform-origin:top; }
        51%  { transform:scaleY(1); transform-origin:bottom; }
        100% { transform:scaleY(0); transform-origin:bottom; }
    }
    .scroll-txt {
        font-family: 'Share Tech Mono', monospace;
        font-size: .62rem;
        color: var(--ice3);
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(28px); }
        to   { opacity:1; transform:translateY(0); }
    }

    /* Responsive */
    @media (max-width: 900px) {
        .hero-grid    { grid-template-columns:1fr; text-align:center; padding-top:5rem; }
        .hero-desc    { margin: 0 auto 2.2rem; }
        .hero-actions, .stats-row { justify-content:center; }
        .hero-right   { display:none; }
    }
</style>

<!-- Canvas de fondo (circuitos + binario) -->
<canvas id="bgCanvas"></canvas>

<section class="hero" id="inicio">
    <div class="hero-grid">

        {{-- ── Contenido izquierdo ── --}}
        <div class="hero-left">

            <div class="badge-chip">
                <span class="badge-dot"></span>
                SYS_STATUS: ONLINE &nbsp;·&nbsp; INDUSTRIA 4.0
            </div>

            <h1 class="hero-title">
                <span class="title-line">Automatización,</span>
                <span class="title-line">IoT &amp; Software</span>
                <span class="title-accent">a tu medida</span>
            </h1>

            {{-- Terminal typewriter --}}
            <div class="terminal-line">
                <span class="term-prompt">~/dev $</span>
                <span id="typeText"></span>
                <span class="cursor"></span>
            </div>

            <p class="hero-desc">
                Transformamos procesos industriales mediante automatización inteligente,
                electrónica embebida y desarrollo de software a medida.
                Tu visión → nuestra ingeniería.
            </p>

            <div class="hero-actions">
                <a href="{{ url('/contacto') }}" class="btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    Solicitar Cotización
                </a>
                <a href="{{ url('/servicios') }}" class="btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    Ver Servicios
                </a>
            </div>

            <div class="stats-row">
                <div>
                    <div class="stat-num">+50<em>%</em></div>
                    <div class="stat-label">COST_REDUCTION</div>
                </div>
                <div>
                    <div class="stat-num">24<em>/7</em></div>
                    <div class="stat-label">UPTIME_MONITOR</div>
                </div>
                <div>
                    <div class="stat-num">100<em>%</em></div>
                    <div class="stat-label">CUSTOM_BUILT</div>
                </div>
            </div>

        </div>

        {{-- ── Panel tecnológico derecho ── --}}
        <div class="hero-right">

            <div class="chip-float chip-top">
                <span class="chip-ico">⬡</span> NODE_COUNT: 14 activos
            </div>

            <div class="tech-panel">
                {{-- Window bar estilo IDE --}}
                <div class="panel-bar">
                    <span class="w-dot r"></span>
                    <span class="w-dot y"></span>
                    <span class="w-dot g"></span>
                    <span class="panel-title">industrial_ctrl_v2.4.1 — main.c</span>
                    <span class="panel-status">
                        <span class="status-dot"></span> RUNNING
                    </span>
                </div>

                {{-- Osciloscopio animado --}}
                <canvas id="oscCanvas"></canvas>

                {{-- Métricas en grid --}}
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon">⬡ PLC</div>
                        <div class="metric-val">92<span>%</span></div>
                        <div class="metric-name">AUTOMATION_RATE</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">⬡ IoT</div>
                        <div class="metric-val">4<span>ms</span></div>
                        <div class="metric-name">AVG_LATENCY</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">⬡ MCU</div>
                        <div class="metric-val">3.3<span>V</span></div>
                        <div class="metric-name">VCC_STABLE</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">⬡ NET</div>
                        <div class="metric-val">99<span>%</span></div>
                        <div class="metric-name">UPTIME_7D</div>
                    </div>
                </div>

                {{-- Barras de progreso --}}
                <div class="prog-list">
                    <div class="prog-item">
                        <div class="prog-head">
                            <span class="prog-name">PLC / SCADA Automation</span>
                            <span class="prog-pct">92%</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill" style="--w:92%"></div>
                        </div>
                    </div>
                    <div class="prog-item">
                        <div class="prog-head">
                            <span class="prog-name">IoT Firmware (ESP/STM32)</span>
                            <span class="prog-pct">87%</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill" style="--w:87%"></div>
                        </div>
                    </div>
                    <div class="prog-item">
                        <div class="prog-head">
                            <span class="prog-name">Embedded C / RTOS</span>
                            <span class="prog-pct">95%</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill" style="--w:95%"></div>
                        </div>
                    </div>
                    <div class="prog-item">
                        <div class="prog-head">
                            <span class="prog-name">Full-Stack Dev (PHP/React)</span>
                            <span class="prog-pct">78%</span>
                        </div>
                        <div class="prog-track">
                            <div class="prog-fill" style="--w:78%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="chip-float chip-bot">
                <span class="chip-ico">⬡</span> SYS_TEMP: 41°C — estable
            </div>

        </div>
    </div>

    <div class="hero-scroll">
        <div class="scroll-line"></div>
        <span class="scroll-txt">scroll</span>
    </div>
</section>

<script>
    // ── CANVAS: binario cayendo + red de nodos ──
    const bgC   = document.getElementById('bgCanvas');
    const bgCtx = bgC.getContext('2d');

    function resizeBg() {
        bgC.width  = window.innerWidth;
        bgC.height = window.innerHeight;
    }
    resizeBg();
    window.addEventListener('resize', resizeBg);

    // Nodos de red
    const nodes = Array.from({length: 55}, () => ({
        x: Math.random() * bgC.width,
        y: Math.random() * bgC.height,
        vx: (Math.random() - .5) * .3,
        vy: (Math.random() - .5) * .3,
        r: Math.random() * 2 + 1,
    }));

    // Columnas binarias
    const COLS = 40;
    const cols = Array.from({length: COLS}, (_, i) => ({
        x: (i / COLS) * 1920,
        y: Math.random() * -500,
        speed: Math.random() * 1.5 + .5,
        chars: Array.from({length: 20}, () => Math.random() < .5 ? '1' : '0'),
        opacity: Math.random() * .5 + .1,
    }));

    function drawBg() {
        bgCtx.clearRect(0, 0, bgC.width, bgC.height);

        // Binario cayendo
        cols.forEach(col => {
            col.y += col.speed;
            if (col.y > bgC.height + 200) col.y = -300;
            col.chars.forEach((c, i) => {
                const alpha = Math.max(0, col.opacity - i * .025);
                bgCtx.globalAlpha = alpha;
                bgCtx.fillStyle = '#00d4ff';
                bgCtx.font = '11px "Share Tech Mono", monospace';
                bgCtx.fillText(c, col.x % bgC.width, col.y - i * 14);
            });
        });

        // Nodos + aristas
        bgCtx.globalAlpha = 1;
        nodes.forEach(n => {
            n.x += n.vx; n.y += n.vy;
            if (n.x < 0 || n.x > bgC.width)  n.vx *= -1;
            if (n.y < 0 || n.y > bgC.height)  n.vy *= -1;
        });

        for (let i = 0; i < nodes.length; i++) {
            for (let j = i + 1; j < nodes.length; j++) {
                const dx = nodes[i].x - nodes[j].x;
                const dy = nodes[i].y - nodes[j].y;
                const d  = Math.sqrt(dx*dx + dy*dy);
                if (d < 140) {
                    bgCtx.globalAlpha = (1 - d/140) * .12;
                    bgCtx.strokeStyle = '#4a90d9';
                    bgCtx.lineWidth   = .6;
                    bgCtx.beginPath();
                    bgCtx.moveTo(nodes[i].x, nodes[i].y);
                    bgCtx.lineTo(nodes[j].x, nodes[j].y);
                    bgCtx.stroke();
                }
            }
        }

        nodes.forEach(n => {
            bgCtx.globalAlpha = .35;
            bgCtx.fillStyle   = '#00d4ff';
            bgCtx.beginPath();
            bgCtx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
            bgCtx.fill();
        });

        bgCtx.globalAlpha = 1;
        requestAnimationFrame(drawBg);
    }
    drawBg();

    // ── OSCILOSCOPIO ──
    const osc  = document.getElementById('oscCanvas');
    osc.width  = osc.offsetWidth * (window.devicePixelRatio || 1) || 380;
    osc.height = 70 * (window.devicePixelRatio || 1);
    const octx = osc.getContext('2d');
    let t = 0;

    function drawOsc() {
        const W = osc.width, H = osc.height;
        octx.fillStyle = 'rgba(0,10,5,.25)';
        octx.fillRect(0, 0, W, H);
        octx.strokeStyle = '#00d4ff';
        octx.lineWidth   = 1.5;
        octx.shadowColor = '#00d4ff';
        octx.shadowBlur  = 6;
        octx.beginPath();
        for (let x = 0; x < W; x++) {
            const y = H/2
                + Math.sin((x + t) * .04)  * H * .22
                + Math.sin((x + t * 1.3) * .08) * H * .1;
            x === 0 ? octx.moveTo(x, y) : octx.lineTo(x, y);
        }
        octx.stroke();
        octx.shadowBlur = 0;
        t += .8;
        requestAnimationFrame(drawOsc);
    }
    drawOsc();

    // ── TYPEWRITER ──
    const lines = [
        'init_system --env=production',
        'connect iot_nodes[14] --proto=MQTT',
        'deploy firmware v2.4.1 → ESP32',
        'monitor.start(interval=100ms)',
        'status: ALL_SYSTEMS_NOMINAL ✓',
    ];
    let li = 0, ci = 0, erasing = false;
    const el = document.getElementById('typeText');

    function type() {
        if (!erasing) {
            if (ci <= lines[li].length) {
                el.textContent = lines[li].slice(0, ci++);
                setTimeout(type, ci > lines[li].length ? 1800 : 45);
            } else { erasing = true; setTimeout(type, 200); }
        } else {
            if (ci > 0) {
                el.textContent = lines[li].slice(0, --ci);
                setTimeout(type, 22);
            } else { erasing = false; li = (li + 1) % lines.length; setTimeout(type, 300); }
        }
    }
    type();

    /    // ── PROGRESS BARS ──
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('.prog-fill').forEach(b => b.classList.add('go'));
        }, 700);
    });
</script>

{{-- @include('components.tienda-section') --}}

@endsection