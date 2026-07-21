@extends('welcome')

@section('title', 'Inicio — MREYZE')

@section('content')
<style>
:root {
    --hero-gradient: linear-gradient(135deg, #0a1628 0%, #0d2840 50%, #0a1628 100%);
}

/* ── REVEAL ── */
.reveal { opacity:0; transform:translateY(32px); transition:opacity 0.75s ease, transform 0.75s ease; }
.reveal.visible { opacity:1; transform:translateY(0); }
.reveal-delay-1 { transition-delay:0.1s; }
.reveal-delay-2 { transition-delay:0.2s; }
.reveal-delay-3 { transition-delay:0.3s; }

/* ── SECTION HEADERS ── */
.section-header {
    text-align:center;
    margin-bottom:3rem;
}
.section-label {
    display:inline-flex;align-items:center;gap:8px;
    font-size:0.75rem;font-weight:700;letter-spacing:0.2em;
    text-transform:uppercase;color:var(--accent);margin-bottom:0.75rem;
}
.section-label::before,
.section-label::after {
    content:'';width:24px;height:2px;
    background:var(--accent);border-radius:2px;
}
.section-title {
    font-family:'Exo 2',sans-serif;
    font-size:clamp(1.6rem,3vw,2.4rem);font-weight:700;
    color:#fff;line-height:1.2;letter-spacing:-0.02em;
    margin-bottom:0.75rem;
}
.section-title span {
    background:linear-gradient(135deg,var(--ice-200),var(--accent));
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.section-desc {
    font-size:1rem;color:var(--ice-200);line-height:1.7;
    max-width:580px;margin:0 auto;
}

/* ════════════════════════════════════════════════════
   SECCIÓN 1 — HERO
   ════════════════════════════════════════════════════ */
.home-hero {
    position:relative;
    min-height:calc(100vh - 70px);
    display:flex;align-items:center;
    background:var(--hero-gradient);
    overflow:hidden;
}
.home-hero::before {
    content:'';
    position:absolute;inset:0;
    background:
        radial-gradient(ellipse 80% 60% at 20% 80%, rgba(0,212,255,0.06) 0%, transparent 60%),
        radial-gradient(ellipse 60% 80% at 80% 20%, rgba(30,77,153,0.08) 0%, transparent 60%);
    pointer-events:none;
}

/* Grid dots */
.home-hero::after {
    content:'';
    position:absolute;inset:0;
    background-image:radial-gradient(rgba(74,144,217,0.08) 1px, transparent 1px);
    background-size:40px 40px;
    pointer-events:none;
}

.hero-inner {
    position:relative;z-index:2;
    max-width:1280px;margin:0 auto;
    padding:5rem 2rem;
    width:100%;
    display:grid;
    grid-template-columns:1fr 1.2fr;
    gap:3rem;align-items:center;
}

.hero-left { animation:fadeUp 0.8s ease both; }

.hero-badge {
    display:inline-flex;align-items:center;gap:8px;
    padding:0.35rem 1rem;
    background:rgba(0,212,255,0.08);
    border:1px solid rgba(0,212,255,0.2);
    border-radius:30px;
    font-family:'Exo 2',sans-serif;
    font-size:0.72rem;font-weight:600;
    color:var(--accent);letter-spacing:0.05em;
    margin-bottom:1.5rem;
}
.hero-badge-dot {
    width:6px;height:6px;border-radius:50%;
    background:var(--accent);
    box-shadow:0 0 8px var(--accent);
    animation:pulse 2s infinite;
}
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.4)} }

.hero-title {
    font-family:'Exo 2',sans-serif;
    font-size:clamp(2rem,4vw,3.5rem);
    font-weight:700;line-height:1.1;
    color:#fff;letter-spacing:-0.02em;
    margin-bottom:1.2rem;
}
.hero-title .highlight {
    background:linear-gradient(135deg,var(--ice-200),var(--accent));
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.hero-subtitle {
    font-size:1.05rem;line-height:1.7;
    color:var(--ice-200);max-width:480px;
    margin-bottom:2rem;
}

.hero-actions {
    display:flex;gap:1rem;flex-wrap:wrap;
    margin-bottom:2.5rem;
}
.hero-btn {
    display:inline-flex;align-items:center;gap:8px;
    padding:0.85rem 2rem;border-radius:10px;
    font-family:'Exo 2',sans-serif;
    font-size:0.9rem;font-weight:600;
    text-decoration:none;letter-spacing:0.03em;
    transition:all 0.3s;cursor:pointer;
    border:none;
}
.hero-btn svg { width:18px;height:18px;flex-shrink:0; }
.hero-btn-primary {
    color:#fff;
    background:linear-gradient(135deg,var(--ice-500),var(--ice-400));
    border:1px solid rgba(74,144,217,0.4);
    box-shadow:0 4px 20px rgba(30,77,153,0.5);
}
.hero-btn-primary:hover {
    background:linear-gradient(135deg,var(--ice-400),var(--glow));
    transform:translateY(-2px);
    box-shadow:0 8px 32px rgba(59,130,246,0.6);
    color:#fff;
}
.hero-btn-whatsapp {
    color:#fff;
    background:linear-gradient(135deg,#25D366,#128C7E);
    border:1px solid rgba(37,211,102,0.3);
    box-shadow:0 4px 20px rgba(37,211,102,0.35);
}
.hero-btn-whatsapp:hover {
    transform:translateY(-2px);
    box-shadow:0 8px 32px rgba(37,211,102,0.5);
    color:#fff;
}

/* Hero right side */
.hero-right {
    display:flex;align-items:center;justify-content:center;
    animation:fadeUp 0.8s 0.15s ease both;
}
.hero-showcase {
    position:relative;
    width:100%;
    aspect-ratio:4/3;
    border-radius:18px;
    overflow:hidden;
    background:rgba(13,31,60,0.6);
    border:1px solid rgba(74,144,217,0.15);
    box-shadow:0 24px 64px rgba(0,20,70,0.4);
}
.hero-showcase img {
    width:100%;height:100%;object-fit:cover;
    transition:transform 0.6s ease;
}
.hero-showcase:hover img { transform:scale(1.05); }
.hero-showcase-overlay {
    position:absolute;inset:0;
    background:linear-gradient(to top,rgba(10,22,40,0.7) 0%,transparent 50%);
}
.hero-showcase-tag {
    position:absolute;bottom:1.2rem;left:1.2rem;
    display:inline-flex;align-items:center;gap:6px;
    padding:0.4rem 1rem;border-radius:30px;
    background:rgba(0,0,0,0.6);backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,0.1);
    font-size:0.75rem;font-weight:600;color:#fff;
}

/* Stats row */
.hero-stats {
    display:flex;gap:2.5rem;
    padding-top:1.5rem;
    border-top:1px solid rgba(74,144,217,0.15);
    animation:fadeUp 0.8s 0.3s ease both;
    flex-wrap:wrap;
}
.stat-num {
    font-family:'Exo 2',sans-serif;
    font-size:1.6rem;font-weight:700;color:#fff;line-height:1;
}
.stat-num em { color:var(--accent);font-style:normal; }
.stat-label {
    font-size:0.7rem;color:var(--ice-300);
    letter-spacing:0.05em;margin-top:4px;
    text-transform:uppercase;
}

@keyframes fadeUp {
    from{opacity:0;transform:translateY(28px)}
    to{opacity:1;transform:translateY(0)}
}

/* ════════════════════════════════════════════════════
   SECCIÓN 2 — CARRUSEL INFINITO
   ════════════════════════════════════════════════════ */
.carousel-section {
    padding:3rem 0;
    overflow:hidden;
    background:rgba(13,31,60,0.3);
    border-top:1px solid rgba(74,144,217,0.08);
    border-bottom:1px solid rgba(74,144,217,0.08);
}

.carousel-track-wrapper {
    position:relative;
    overflow:hidden;
    mask-image:linear-gradient(to right,transparent 0%,black 4%,black 96%,transparent 100%);
    -webkit-mask-image:linear-gradient(to right,transparent 0%,black 4%,black 96%,transparent 100%);
}

.carousel-track {
    display:flex;
    gap:1.2rem;
    width:max-content;
    animation:scroll 40s linear infinite;
}

.carousel-section:hover .carousel-track {
    animation-play-state:paused;
}

@keyframes scroll {
    0%   { transform:translateX(0); }
    100% { transform:translateX(-50%); }
}

.carousel-card {
    flex-shrink:0;
    width:220px;
    background:rgba(13,31,60,0.6);
    border:1px solid rgba(74,144,217,0.12);
    border-radius:14px;
    overflow:hidden;
    text-decoration:none;
    color:inherit;
    transition:all 0.3s ease;
    display:block;
}
.carousel-card:hover {
    border-color:rgba(0,212,255,0.3);
    transform:translateY(-4px);
    box-shadow:0 12px 36px rgba(0,30,90,0.4);
}

.carousel-card-img {
    height:140px;
    background:rgba(10,22,40,0.8);
    display:flex;align-items:center;justify-content:center;
    overflow:hidden;
}
.carousel-card-img img {
    width:100%;height:100%;object-fit:cover;
    transition:transform 0.4s ease;
}
.carousel-card:hover .carousel-card-img img {
    transform:scale(1.08);
}
.carousel-card-img .no-img {
    display:flex;align-items:center;justify-content:center;
    width:100%;height:100%;
}

.carousel-card-body {
    padding:0.8rem 0.9rem 0.9rem;
}
.carousel-card-name {
    font-family:'Exo 2',sans-serif;
    font-size:0.82rem;font-weight:600;color:#fff;
    margin-bottom:0.3rem;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.carousel-card-price {
    font-size:0.92rem;font-weight:700;color:var(--accent);
}

/* ════════════════════════════════════════════════════
   SECCIÓN 3 — PRODUCTOS DESTACADOS
   ════════════════════════════════════════════════════ */
.destacados {
    padding:5rem 2rem;
    max-width:1280px;margin:0 auto;
}

.destacados-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:1.5rem;
}

.destacado-card {
    background:rgba(13,31,60,0.6);
    border:1px solid rgba(74,144,217,0.12);
    border-radius:16px;
    overflow:hidden;
    text-decoration:none;color:inherit;
    transition:all 0.35s ease;
    display:block;
}
.destacado-card:hover {
    border-color:rgba(0,212,255,0.3);
    transform:translateY(-5px);
    box-shadow:0 16px 40px rgba(0,30,90,0.4);
}

.destacado-card-img {
    height:180px;
    background:rgba(10,22,40,0.8);
    display:flex;align-items:center;justify-content:center;
    overflow:hidden;position:relative;
}
.destacado-card-img img {
    width:100%;height:100%;object-fit:cover;
    transition:transform 0.5s ease;
}
.destacado-card:hover .destacado-card-img img {
    transform:scale(1.08);
}
.destacado-card-img .no-img {
    display:flex;align-items:center;justify-content:center;
    width:100%;height:100%;
}
.destacado-badge {
    position:absolute;top:10px;left:10px;
    font-size:0.62rem;font-weight:700;
    padding:3px 10px;border-radius:20px;
    letter-spacing:0.05em;text-transform:uppercase;
}
.destacado-badge.disponible { background:rgba(0,212,170,0.15);color:#00d4aa; }
.destacado-badge.nuevo { background:rgba(0,212,255,0.15);color:var(--accent); }
.destacado-badge.agotado { background:rgba(255,107,107,0.15);color:#ff6b6b; }

.destacado-body {
    padding:1rem 1.1rem 1.1rem;
}
.destacado-cat {
    font-size:0.62rem;font-weight:700;
    letter-spacing:0.1em;text-transform:uppercase;
    color:var(--ice-300);margin-bottom:0.3rem;
}
.destacado-nombre {
    font-family:'Exo 2',sans-serif;
    font-size:0.9rem;font-weight:600;color:#fff;
    margin-bottom:0.3rem;
}
.destacado-precio {
    font-size:1.15rem;font-weight:700;color:var(--accent);
    margin-bottom:0.8rem;
}
.destacado-precio-anterior {
    font-size:0.78rem;font-weight:400;color:var(--ice-300);
    text-decoration:line-through;margin-left:6px;
}
.destacado-btn {
    display:inline-flex;align-items:center;gap:6px;
    padding:0.5rem 1.2rem;border-radius:8px;
    font-size:0.78rem;font-weight:600;
    background:linear-gradient(135deg,var(--ice-500),var(--ice-400));
    border:1px solid rgba(74,144,217,0.3);
    color:#fff;text-decoration:none;
    transition:all 0.25s;
}
.destacado-btn:hover {
    background:linear-gradient(135deg,var(--ice-400),var(--glow));
    box-shadow:0 4px 16px rgba(59,130,246,0.4);
    color:#fff;
}

/* ════════════════════════════════════════════════════
   SECCIÓN 4 — POR QUÉ ELEGIRNOS
   ════════════════════════════════════════════════════ */
.porque-elegirnos {
    padding:5rem 2rem;
    background:rgba(13,31,60,0.4);
    border-top:1px solid rgba(74,144,217,0.08);
    border-bottom:1px solid rgba(74,144,217,0.08);
}

.porque-inner {
    max-width:1280px;margin:0 auto;
}

.porque-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:1.5rem;
}

.porque-card {
    background:rgba(13,31,60,0.6);
    border:1px solid rgba(74,144,217,0.12);
    border-radius:16px;
    padding:2rem 1.5rem;
    text-align:center;
    transition:all 0.35s ease;
}
.porque-card:hover {
    border-color:rgba(0,212,255,0.25);
    transform:translateY(-4px);
    box-shadow:0 12px 32px rgba(0,30,90,0.3);
}

.porque-icon {
    width:56px;height:56px;
    margin:0 auto 1.2rem;
    border-radius:14px;
    display:flex;align-items:center;justify-content:center;
    background:rgba(0,212,255,0.08);
    border:1px solid rgba(0,212,255,0.15);
}
.porque-icon svg { width:26px;height:26px;color:var(--accent); }

.porque-card h3 {
    font-family:'Exo 2',sans-serif;
    font-size:0.95rem;font-weight:600;color:#fff;
    margin:0 0 0.5rem;
}
.porque-card p {
    font-size:0.82rem;color:var(--ice-200);line-height:1.6;
    margin:0;
}

/* ════════════════════════════════════════════════════
   SECCIÓN 5 — CTA
   ════════════════════════════════════════════════════ */
.cta-section {
    padding:5rem 2rem;
    text-align:center;
    position:relative;
    overflow:hidden;
}
.cta-section::before {
    content:'';
    position:absolute;inset:0;
    background:
        radial-gradient(ellipse 60% 50% at 50% 50%, rgba(0,212,255,0.04) 0%, transparent 60%);
    pointer-events:none;
}

.cta-inner {
    position:relative;z-index:2;
    max-width:700px;margin:0 auto;
}

.cta-title {
    font-family:'Exo 2',sans-serif;
    font-size:clamp(1.5rem,3vw,2.2rem);
    font-weight:700;color:#fff;
    line-height:1.2;letter-spacing:-0.02em;
    margin-bottom:1rem;
}
.cta-title span {
    background:linear-gradient(135deg,var(--ice-200),var(--accent));
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.cta-desc {
    font-size:1rem;color:var(--ice-200);line-height:1.7;
    margin-bottom:2rem;
}
.cta-actions {
    display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;
}

/* ════════════════════════════════════════════════════
   RESPONSIVE
   ════════════════════════════════════════════════════ */
@media (max-width:1024px) {
    .destacados-grid { grid-template-columns:repeat(3,1fr); }
    .porque-grid { grid-template-columns:repeat(2,1fr); }
}

@media (max-width:768px) {
    .hero-inner { grid-template-columns:1fr;gap:2rem;text-align:center; padding:3rem 1.5rem; }
    .hero-subtitle { margin:0 auto 2rem; }
    .hero-actions { justify-content:center; }
    .hero-stats { justify-content:center; }
    .hero-right { display:flex; justify-content:center; }
    .hero-showcase { max-width:420px; }
    .hero-badge { padding:0.3rem 0.85rem; gap:6px; font-size:0.68rem; }

    .destacados-grid { grid-template-columns:repeat(2,1fr); }
    .porque-grid { grid-template-columns:1fr; }
    .carousel-card { width:180px; }
}

@media (max-width:480px) {
    .hero-inner { padding:2.5rem 1rem; }
    .hero-badge { padding:0.25rem 0.65rem; gap:5px; font-size:0.62rem; white-space:normal; text-align:left; }
    .destacados-grid { grid-template-columns:1fr; }
    .hero-actions { flex-direction:column;align-items:center; }
    .carousel-card { width:160px; }
    .carousel-card-img { height:110px; }
    .destacados { padding:3rem 1rem; }
    .porque-elegirnos { padding:3rem 1rem; }
    .cta-section { padding:3rem 1rem; }
}
</style>

{{-- ════════════════════════════════════════
     SECCIÓN 1 — HERO PRINCIPAL
     ════════════════════════════════════════ --}}
<section class="home-hero">
    <div class="hero-inner">
        <div class="hero-left">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Tienda oficial — Productos originales
            </div>

            <h1 class="hero-title">
                Soluciones tecnológicas<br>
                para tu <span class="highlight">hogar y empresa</span>
            </h1>

            <p class="hero-subtitle">
                Descubre nuestra tienda con productos de automatización, IoT, electrónica
                y desarrollo. Calidad garantizada y envíos a todo el Perú.
            </p>

            <div class="hero-actions">
                <a href="{{ route('tienda.index') }}" class="hero-btn hero-btn-primary">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    Ver tienda
                </a>
                <a href="{{ config('empresa.whatsapp_url') }}" target="_blank" rel="noopener noreferrer" class="hero-btn hero-btn-whatsapp">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-1.102-.984-1.847-2.2-2.063-2.572-.216-.373-.023-.575.163-.763.166-.167.373-.434.56-.651.187-.217.249-.373.373-.622.124-.248.062-.467-.031-.65-.093-.183-.67-1.618-.92-2.217-.242-.579-.487-.48-.67-.49-.173-.01-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.903.521 3.684 1.427 5.226L2 22l4.806-1.414A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18c-1.653 0-3.198-.539-4.446-1.444l-.318-.213-3.052.898.898-3.052-.213-.318A7.94 7.94 0 014 12c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z"/></svg>
                    Contactar por WhatsApp
                </a>
            </div>

            <div class="hero-stats">
                <div>
                    <div class="stat-num">+{{ number_format($productos->count()) }}</div>
                    <div class="stat-label">Productos disponibles</div>
                </div>
                <div>
                    <div class="stat-num">Envíos <em>a todo Perú</em></div>
                    <div class="stat-label">Cobertura nacional</div>
                </div>
                <div>
                    <div class="stat-num">100<em>%</em></div>
                    <div class="stat-label">Compras seguras</div>
                </div>
            </div>
        </div>

        <div class="hero-right">
            <div class="hero-showcase">
                <img src="{{ asset('img/principal.png') }}" alt="MREYZE Tienda" loading="eager">
                <div class="hero-showcase-overlay"></div>
                <span class="hero-showcase-tag">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
                    Tienda oficial
                </span>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════
     SECCIÓN 2 — CARRUSEL INFINITO
     ════════════════════════════════════════ --}}
<section class="carousel-section">
    <div class="carousel-track-wrapper">
        <div class="carousel-track" id="productTrack">
            {{-- Render 2 copias para efecto infinito sin saltos --}}
            @foreach($productos as $p)
            <a href="{{ route('tienda.show', $p->slug) }}" class="carousel-card">
                <div class="carousel-card-img">
                    @if($p->imagen_principal_url)
                    <img src="{{ $p->imagen_principal_url }}" alt="{{ $p->nombre }}" loading="lazy">
                    @elseif($p->icono_imagen)
                    <img src="{{ asset('storage/' . $p->icono_imagen) }}" alt="{{ $p->nombre }}" loading="lazy">
                    @else
                    <span class="no-img">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:30px;height:30px;opacity:0.2"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    </span>
                    @endif
                </div>
                <div class="carousel-card-body">
                    <div class="carousel-card-name">{{ $p->nombre }}</div>
                    <div class="carousel-card-price">{{ $p->precio_formateado }}</div>
                </div>
            </a>
            @endforeach
            @foreach($productos as $p)
            <a href="{{ route('tienda.show', $p->slug) }}" class="carousel-card" aria-hidden="true">
                <div class="carousel-card-img">
                    @if($p->imagen_principal_url)
                    <img src="{{ $p->imagen_principal_url }}" alt="{{ $p->nombre }}" loading="lazy">
                    @elseif($p->icono_imagen)
                    <img src="{{ asset('storage/' . $p->icono_imagen) }}" alt="{{ $p->nombre }}" loading="lazy">
                    @else
                    <span class="no-img">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:30px;height:30px;opacity:0.2"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    </span>
                    @endif
                </div>
                <div class="carousel-card-body">
                    <div class="carousel-card-name">{{ $p->nombre }}</div>
                    <div class="carousel-card-price">{{ $p->precio_formateado }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════
     SECCIÓN 3 — PRODUCTOS DESTACADOS
     ════════════════════════════════════════ --}}
<section class="destacados">
    <div class="section-header reveal">
        <div class="section-label">Productos</div>
        <h2 class="section-title">Productos <span>destacados</span></h2>
        <p class="section-desc">Los productos más populares de nuestra tienda, seleccionados para ti.</p>
    </div>

    <div class="destacados-grid">
        @forelse($destacados as $p)
        <a href="{{ route('tienda.show', $p->slug) }}" class="destacado-card reveal reveal-delay-{{ min($loop->index, 3) }}">
            <div class="destacado-card-img">
                @if($p->imagen_principal_url)
                <img src="{{ $p->imagen_principal_url }}" alt="{{ $p->nombre }}" loading="lazy">
                @elseif($p->icono_imagen)
                <img src="{{ asset('storage/' . $p->icono_imagen) }}" alt="{{ $p->nombre }}" loading="lazy">
                @else
                <span class="no-img">
                    <svg viewBox="0 0 24 24" fill="currentColor" style="width:38px;height:38px;opacity:0.15"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                </span>
                @endif
                <span class="destacado-badge {{ $p->estado }}">{{ ucfirst($p->estado) }}</span>
            </div>
            <div class="destacado-body">
                <div class="destacado-cat">{{ $p->categoria ?? 'General' }}</div>
                <div class="destacado-nombre">{{ $p->nombre }}</div>
                <div class="destacado-precio">
                    {{ $p->precio_formateado }}
                    @if($p->tieneDescuento())
                    <span class="destacado-precio-anterior">{{ $p->precio_anterior ? 'S/ '.number_format($p->precio_anterior, 2) : '' }}</span>
                    @endif
                </div>
                <span class="destacado-btn">Ver producto</span>
            </div>
        </a>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--ice-300)">
            Próximamente estaremos agregando productos destacados.
        </div>
        @endforelse
    </div>
</section>

{{-- ════════════════════════════════════════
     SECCIÓN 4 — ¿POR QUÉ ELEGIRNOS?
     ════════════════════════════════════════ --}}
<section class="porque-elegirnos">
    <div class="porque-inner">
        <div class="section-header reveal">
            <div class="section-label">Por qué nosotros</div>
            <h2 class="section-title">¿Por qué <span>elegirnos?</span></h2>
            <p class="section-desc">Más de 10 años de experiencia respaldan cada producto que ofrecemos.</p>
        </div>

        <div class="porque-grid">
            <div class="porque-card reveal reveal-delay-1">
                <div class="porque-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
                <h3>Calidad Garantizada</h3>
                <p>Todos nuestros productos son originales y pasan por rigurosos controles de calidad antes de llegar a tus manos.</p>
            </div>

            <div class="porque-card reveal reveal-delay-2">
                <div class="porque-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>
                <h3>Atención Personalizada</h3>
                <p>Te asesoramos en cada paso para encontrar la solución tecnológica que realmente necesitas.</p>
            </div>

            <div class="porque-card reveal reveal-delay-3">
                <div class="porque-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                </div>
                <h3>Compra Segura</h3>
                <p>Tus datos y pagos están protegidos. Ofrecemos múltiples métodos de pago para tu tranquilidad.</p>
            </div>

            <div class="porque-card reveal">
                <div class="porque-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                </div>
                <h3>Entrega Rápida</h3>
                <p>Procesamos tu pedido en tiempo récord y coordinamos la entrega en la menor brevedad posible.</p>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════
     SECCIÓN 5 — CTA
     ════════════════════════════════════════ --}}
<section class="cta-section">
    <div class="cta-inner reveal">
        <h2 class="cta-title">¿Listo para llevar tu tecnología al <span>siguiente nivel?</span></h2>
        <p class="cta-desc">Explora nuestra tienda y encuentra el producto perfecto para tu hogar o empresa. ¡Te esperamos!</p>
        <div class="cta-actions">
            <a href="{{ route('tienda.index') }}" class="hero-btn hero-btn-primary">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                Ir a la tienda
            </a>
            <a href="{{ config('empresa.whatsapp_url') }}" target="_blank" rel="noopener noreferrer" class="hero-btn hero-btn-whatsapp">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-1.102-.984-1.847-2.2-2.063-2.572-.216-.373-.023-.575.163-.763.166-.167.373-.434.56-.651.187-.217.249-.373.373-.622.124-.248.062-.467-.031-.65-.093-.183-.67-1.618-.92-2.217-.242-.579-.487-.48-.67-.49-.173-.01-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.903.521 3.684 1.427 5.226L2 22l4.806-1.414A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18c-1.653 0-3.198-.539-4.446-1.444l-.318-.213-3.052.898.898-3.052-.213-.318A7.94 7.94 0 014 12c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z"/></svg>
                Contactar por WhatsApp
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── SCROLL REVEAL ──
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // ── CARRUSEL — clon dinámico para que nunca haya saltos ──
    const track = document.getElementById('productTrack');
    if (track) {
        let cloned = false;
        const checkScroll = () => {
            if (cloned) return;
            const items = track.children;
            if (items.length > 0) {
                const itemWidth = items[0].offsetWidth + 19.2;
                const totalWidth = itemWidth * items.length;
                if (track.scrollWidth >= totalWidth * 2) {
                    cloned = true;
                    return;
                }
            }
        };
        checkScroll();
    }
});
</script>

@endsection
