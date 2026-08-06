@extends('welcome')

@section('title', $proyecto->titulo . ' — MREYZE')

@push('styles')
<style>
/* ── Proyecto Show Detail Page ── */
.proyecto-show {
    background: var(--ice-800);
    min-height: calc(100vh - 70px);
    padding: 2.5rem 1rem 4rem;
    position: relative;
}

.proyecto-show::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
}

.proyecto-show-container {
    max-width: 1100px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

/* ── Gallery ── */
.proyecto-gallery {
    position: sticky;
    top: 6rem;
}

.proyecto-gallery-main {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    aspect-ratio: 16/10;
    background: rgba(13,31,60,0.8);
    border: 1px solid rgba(74,144,217,0.15);
    cursor: crosshair;
    transition: border-color 0.3s;
}

.proyecto-gallery-main:hover {
    border-color: rgba(0,212,255,0.3);
}

.proyecto-gallery-main img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    transition: transform 0.3s;
}

.proyecto-gallery-main:hover img {
    transform: scale(1.02);
}

.proyecto-gallery-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 6rem;
    color: var(--ice-300);
    opacity: 0.15;
}

.proyecto-gallery-zoom {
    position: absolute;
    bottom: 12px;
    right: 12px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 3;
    border: none;
    cursor: pointer;
}

.proyecto-gallery-main:hover .proyecto-gallery-zoom {
    opacity: 1;
}

.proyecto-gallery-zoom svg {
    width: 18px;
    height: 18px;
    color: #fff;
}

.proyecto-gallery-badges {
    position: absolute;
    top: 14px;
    left: 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    z-index: 3;
}

/* Thumbnails */
.proyecto-thumbnails {
    display: flex;
    gap: 10px;
    margin-top: 12px;
    overflow-x: auto;
    padding-bottom: 4px;
}

.proyecto-thumb {
    width: 68px;
    height: 68px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    opacity: 0.5;
    padding: 0;
    background: rgba(13,31,60,0.8);
}

.proyecto-thumb:hover {
    opacity: 0.75;
}

.proyecto-thumb.active {
    border-color: #00d4ff;
    opacity: 1;
    box-shadow: 0 0 12px rgba(0,212,255,0.25);
}

.proyecto-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.proyecto-gallery-counter {
    text-align: center;
    font-size: 0.75rem;
    color: var(--ice-300);
    margin-top: 8px;
}

/* ── Info Section ── */
.proyecto-info {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}

.proyecto-info-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.proyecto-badge {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
}

.proyecto-badge.categoria { background: rgba(0,212,255,0.12); border: 1px solid rgba(0,212,255,0.3); color: var(--accent); }
.proyecto-badge.completado { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
.proyecto-badge.en-curso   { background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3); color: #fbbf24; }
.proyecto-badge.abierto    { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
.proyecto-badge.pronto     { background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3); color: #fbbf24; }
.proyecto-badge.disponible   { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
.proyecto-badge.en_ejecucion { background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3); color: #fbbf24; }

.proyecto-info-title {
    font-family: 'Exo 2', sans-serif;
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    letter-spacing: -0.02em;
}

.proyecto-info-desc {
    font-size: 0.95rem;
    color: var(--ice-200);
    line-height: 1.75;
}

.proyecto-info-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    padding: 1rem 0;
    border-top: 1px solid rgba(74,144,217,0.12);
    border-bottom: 1px solid rgba(74,144,217,0.12);
}

.proyecto-meta-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.proyecto-meta-label {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ice-300);
}

.proyecto-meta-val {
    font-family: 'Exo 2', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    color: #fff;
}

/* Tecnologías */
.proyecto-techs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.proyecto-tech {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 5px 14px;
    border-radius: 20px;
    background: rgba(74,144,217,0.08);
    border: 1px solid rgba(74,144,217,0.15);
    color: var(--ice-200);
    letter-spacing: 0.02em;
}

/* ── Investment Detail Section ── */
.proyecto-inversion-section {
    margin-top: 0.5rem;
    padding: 1.4rem;
    border-radius: 16px;
    background: rgba(13,31,60,0.65);
    border: 1px solid rgba(251,191,36,0.2);
}

.proyecto-inversion-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 1rem;
    font-family: 'Exo 2', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: #fbbf24;
}

.proyecto-inversion-header svg {
    width: 20px;
    height: 20px;
}

.proyecto-inversion-bar-wrap {
    margin-bottom: 1.2rem;
}

.proyecto-inversion-bar-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    font-size: 0.78rem;
    color: var(--ice-300);
}

.proyecto-inversion-bar-label strong {
    font-family: 'Exo 2', sans-serif;
    font-size: 0.95rem;
    color: #fff;
    font-weight: 700;
}

.proyecto-inversion-bar-bg {
    height: 8px;
    border-radius: 10px;
    background: rgba(74,144,217,0.15);
    overflow: hidden;
}

.proyecto-inversion-bar-fill {
    height: 100%;
    border-radius: 10px;
    transition: width 1.2s ease;
}

.proyecto-inversion-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.proyecto-inversion-dato {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.proyecto-inversion-dato-val {
    font-family: 'Exo 2', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
}

.proyecto-inversion-dato-val span { color: var(--accent); }
.proyecto-inversion-dato-val.amarillo span { color: #fbbf24; }

.proyecto-inversion-dato-label {
    font-size: 0.72rem;
    color: var(--ice-300);
}

/* ── WhatsApp Button ── */
.btn-whatsapp-proyecto {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 0.75rem 1.8rem;
    border-radius: 10px;
    font-size: 0.88rem;
    font-weight: 700;
    text-decoration: none;
    background: rgba(37,211,102,0.15);
    border: 1px solid rgba(37,211,102,0.35);
    color: #25D366;
    transition: all 0.25s;
}

.btn-whatsapp-proyecto:hover {
    background: rgba(37,211,102,0.25);
    box-shadow: 0 6px 20px rgba(37,211,102,0.2);
    transform: translateY(-2px);
}

.btn-whatsapp-proyecto svg {
    width: 20px;
    height: 20px;
}

.btn-invertir-proyecto {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 0.85rem 2.2rem;
    border-radius: 10px;
    font-size: 0.92rem;
    font-weight: 700;
    text-decoration: none;
    background: linear-gradient(135deg, #0088cc, #00a8e8);
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all 0.25s;
    box-shadow: 0 6px 20px rgba(0,136,204,0.35);
    margin-right: 10px;
    margin-bottom: 10px;
}

.btn-invertir-proyecto:hover {
    background: linear-gradient(135deg, #0099dd, #00bbff);
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(0,136,204,0.5);
}

.btn-invertir-proyecto svg {
    width: 20px;
    height: 20px;
}

/* ── Lightbox ── */
.proyecto-lightbox {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(0,0,0,0.92);
    backdrop-filter: blur(12px);
}

.proyecto-lightbox-close,
.proyecto-lightbox-nav {
    position: absolute;
    z-index: 50;
    border: none;
    cursor: pointer;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.proyecto-lightbox-close:hover,
.proyecto-lightbox-nav:hover {
    background: rgba(255,255,255,0.2);
}

.proyecto-lightbox-close {
    top: 1rem;
    right: 1rem;
    width: 40px;
    height: 40px;
}

.proyecto-lightbox-nav {
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
}

.proyecto-lightbox-prev { left: 0.5rem; }
.proyecto-lightbox-next { right: 0.5rem; }

.proyecto-lightbox-img {
    max-width: 100%;
    max-height: 85vh;
    object-fit: contain;
    border-radius: 8px;
    user-select: none;
}

.proyecto-lightbox-dots {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 20px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(8px);
}

.proyecto-lightbox-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
    background: rgba(255,255,255,0.4);
}

.proyecto-lightbox-dot.active {
    background: #00d4ff;
    box-shadow: 0 0 8px rgba(0,212,255,0.5);
    transform: scale(1.2);
}

/* ── Related Projects ── */
.proyecto-relacionados {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 1px solid rgba(74,144,217,0.12);
}

@media (max-width: 1024px) {
    .proyecto-gallery {
        position: static;
    }
}

@media (max-width: 640px) {
    .proyecto-show { padding: 1.5rem 0.75rem 3rem; }
    .proyecto-inversion-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

@php
    $catLabels = [
        'automatizacion' => 'Automatización',
        'iot'            => 'IoT',
        'software'       => 'Software',
    ];
    $catEmojis = [
        'automatizacion' => '⚙️',
        'iot'            => '📡',
        'software'       => '💻',
    ];
    $tagClassMap = [
        'automatizacion' => 'auto',
        'iot'            => 'iot',
        'software'       => 'web',
    ];
    $emoji = $catEmojis[$proyecto->categoria] ?? '📁';
    $tagLabel = $catLabels[$proyecto->categoria] ?? ucfirst($proyecto->categoria);
    $estadoClass = $proyecto->estado === 'completado' ? 'completado' : 'en-curso';
    $imgs = $proyecto->imagenes->sortBy('orden');
    $imgPrincipal = $imgs->firstWhere('es_principal') ?? $imgs->first();
    $displaySrc = $imgPrincipal ? asset('storage/' . $imgPrincipal->ruta) : null;
    $allImgUrls = $imgs->map(fn($img) => asset('storage/' . $img->ruta))->values()->all();
@endphp

<section class="proyecto-show">
    <div class="proyecto-show-container">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm mb-8 flex-wrap" style="color:var(--ice-300)">
            <a href="{{ url('/') }}" class="hover:text-cyan-400 transition-colors" style="color:inherit;text-decoration:none">Inicio</a>
            <span class="opacity-40">/</span>
            <a href="{{ url('/proyectos') }}" class="hover:text-cyan-400 transition-colors" style="color:inherit;text-decoration:none">Proyectos</a>
            <span class="opacity-40">/</span>
            <span class="font-medium" style="color:#fff">{{ $proyecto->titulo }}</span>
        </nav>

        {{-- Grid: Gallery + Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-10 items-start">

            {{-- GALERIA (3/5) --}}
            <div class="lg:col-span-3 proyecto-gallery" x-data="proyectoGaleria()">

                {{-- Main image --}}
                <div class="proyecto-gallery-main" @click="abrirLightbox()">
                    @if($displaySrc)
                        <img :src="imagenActual"
                             alt="{{ addslashes($proyecto->titulo) }}"
                             loading="eager"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="proyecto-gallery-fallback" style="display:none">{{ $emoji }}</div>
                    @else
                        <div class="proyecto-gallery-fallback">{{ $emoji }}</div>
                    @endif

                    <div class="proyecto-gallery-badges">
                        <span class="proyecto-badge categoria">{{ $tagLabel }}</span>
                        <span class="proyecto-badge {{ $estadoClass }}">{{ ucfirst($proyecto->estado) }}</span>
                    </div>

                    @if($displaySrc)
                    <button type="button" class="proyecto-gallery-zoom">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    </button>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($imgs->count() > 1)
                <div class="proyecto-thumbnails">
                    @foreach($imgs as $idx => $img)
                    <button type="button"
                            class="proyecto-thumb"
                            :class="indiceActual === {{ $idx }} ? 'active' : ''"
                            @click="seleccionar({{ $idx }}, '{{ asset('storage/' . $img->ruta) }}')">
                        <img src="{{ asset('storage/' . $img->ruta) }}" alt="Imagen {{ $idx + 1 }}" loading="lazy">
                    </button>
                    @endforeach
                </div>
                <div class="proyecto-gallery-counter">
                    <span x-text="indiceActual + 1">1</span> / {{ $imgs->count() }} imágenes
                </div>
                @endif
            </div>

            {{-- INFORMACION (2/5) --}}
            <div class="lg:col-span-2 proyecto-info">

                <div class="proyecto-info-badges">
                    <span class="proyecto-badge categoria">{{ $tagLabel }}</span>
                    <span class="proyecto-badge {{ $estadoClass }}">{{ ucfirst($proyecto->estado) }}</span>
                </div>

                <h1 class="proyecto-info-title">{{ $proyecto->titulo }}</h1>

                <p class="proyecto-info-desc">
                    {{ $proyecto->descripcion_completa ?: $proyecto->descripcion_corta }}
                </p>

                {{-- Meta info --}}
                <div class="proyecto-info-meta">
                    @if($proyecto->fecha_inicio)
                    <div class="proyecto-meta-item">
                        <span class="proyecto-meta-label">Inicio</span>
                        <span class="proyecto-meta-val">{{ $proyecto->fecha_inicio->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    @if($proyecto->fecha_fin)
                    <div class="proyecto-meta-item">
                        <span class="proyecto-meta-label">Fin</span>
                        <span class="proyecto-meta-val">{{ $proyecto->fecha_fin->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    @if($proyecto->cliente)
                    <div class="proyecto-meta-item">
                        <span class="proyecto-meta-label">Cliente</span>
                        <span class="proyecto-meta-val">{{ $proyecto->cliente }}</span>
                    </div>
                    @endif
                </div>

                {{-- Tecnologías --}}
                @if($proyecto->tecnologias)
                <div>
                    <div class="text-xs font-bold tracking-widest uppercase mb-2" style="color:var(--accent)">Tecnologías</div>
                    <div class="proyecto-techs">
                        @foreach(explode(',', $proyecto->tecnologias) as $tech)
                            <span class="proyecto-tech">{{ trim($tech) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Investment Section --}}
                @if($proyecto->tipo === 'inversion')
                @php
                    $badgeClass = $proyecto->inversion_estado ?: 'pronto';
                    $badgeLabel = $proyecto->inversion_estado_label ?? ($proyecto->inversion_etiqueta ?: '◷ Próximamente');
                    $fillGrad = $tagClassMap[$proyecto->categoria] === 'auto'
                        ? 'linear-gradient(90deg,#2563c7,#00d4ff)'
                        : ($tagClassMap[$proyecto->categoria] === 'iot'
                            ? 'linear-gradient(90deg,#0d9488,#2dd4bf)'
                            : 'linear-gradient(90deg,#6366f1,#a5b4fc)');
                @endphp
                <div class="proyecto-inversion-section">
                    <div class="proyecto-inversion-header">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                        Detalle de Inversión
                        <span class="proyecto-badge {{ $badgeClass }}" style="margin-left:auto">{{ $badgeLabel }}</span>
                    </div>

                    <div class="proyecto-inversion-bar-wrap">
                        <div class="proyecto-inversion-bar-label">
                            <span>Financiamiento</span>
                            <strong>{{ $proyecto->inversion_porcentaje_financiado }}%</strong>
                        </div>
                        <div class="proyecto-inversion-bar-bg">
                            <div class="proyecto-inversion-bar-fill" style="width:{{ $proyecto->inversion_porcentaje_financiado }}%;background:{{ $fillGrad }};"></div>
                        </div>
                    </div>

                    <div class="proyecto-inversion-grid">
                        <div class="proyecto-inversion-dato">
                            <div class="proyecto-inversion-dato-val amarillo">S/ <span>{{ number_format($proyecto->inversion_monto_total, 0, ',', ',') }}</span></div>
                            <div class="proyecto-inversion-dato-label">Inversión total</div>
                        </div>
                        <div class="proyecto-inversion-dato">
                            <div class="proyecto-inversion-dato-val">S/ <span>{{ number_format($proyecto->inversion_monto_disponible, 0, ',', ',') }}</span></div>
                            <div class="proyecto-inversion-dato-label">Disponible aún</div>
                        </div>
                        <div class="proyecto-inversion-dato">
                            <div class="proyecto-inversion-dato-val">{{ $proyecto->inversion_tiempo_estimado }}<span> meses</span></div>
                            <div class="proyecto-inversion-dato-label">Tiempo estimado</div>
                        </div>
                        <div class="proyecto-inversion-dato">
                            <div class="proyecto-inversion-dato-val">ROI <span>{{ $proyecto->inversion_roi_label }}</span></div>
                            <div class="proyecto-inversion-dato-label">Retorno estimado</div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- WhatsApp Button --}}
                @if($proyecto->tipo === 'inversion' && $proyecto->inversion_estado === 'disponible')
                <a href="{{ route('inversiones.create', $proyecto) }}" class="btn-invertir-proyecto">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    Invertir ahora
                </a>
                @endif
                @if($proyecto->whatsapp_numero)
                <a href="{{ $proyecto->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp-proyecto">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    @if($proyecto->inversion_estado === 'disponible' && $proyecto->tipo === 'inversion')
                        Quiero invertir en este proyecto
                    @else
                        Contactar por WhatsApp
                    @endif
                </a>
                @endif

            </div>
        </div>

        {{-- Relacionados --}}
        @if($relacionados->count())
        <div class="proyecto-relacionados">
            <div class="section-label">Te puede interesar</div>
            <h2 class="section-title" style="font-size:clamp(1.3rem,2.5vw,1.8rem)">Proyectos <span>relacionados</span></h2>

            @php
                $relTagClass = [
                    'automatizacion' => 'auto',
                    'iot'            => 'iot',
                    'software'       => 'web',
                ];
                $relCatLabel = [
                    'automatizacion' => 'Automatización',
                    'iot'            => 'IoT',
                    'software'       => 'Software',
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
                @foreach($relacionados as $rel)
                    @php
                        $relTag = $relTagClass[$rel->categoria] ?? 'web';
                        $relCatText = $relCatLabel[$rel->categoria] ?? ucfirst($rel->categoria);
                        $relEmoji = $catEmojis[$rel->categoria] ?? '📁';
                        $relEstado = $rel->estado === 'completado' ? 'completado' : 'en-curso';
                    @endphp
                    <a href="{{ route('proyectos.show', $rel->slug) }}" style="text-decoration:none;color:inherit">
                        <div class="proyecto-card" style="cursor:pointer">
                            <div class="proyecto-preview">
                                @if($rel->imagen_principal_url)
                                    <img src="{{ $rel->imagen_principal_url }}" alt="{{ $rel->titulo }}" class="proyecto-preview-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <div class="proyecto-preview-bg" style="display:none">{{ $relEmoji }}</div>
                                @else
                                    <div class="proyecto-preview-bg">{{ $relEmoji }}</div>
                                @endif
                                <div class="proyecto-tags">
                                    <span class="proyecto-tag {{ $relTag }}">{{ $relCatText }}</span>
                                </div>
                                <div class="proyecto-status {{ $relEstado }}">
                                    <span class="proyecto-status-dot"></span> {{ ucfirst($rel->estado) }}
                                </div>
                            </div>
                            <div class="proyecto-body">
                                <div class="proyecto-nombre">{{ $rel->titulo }}</div>
                                <p class="proyecto-desc">{{ $rel->descripcion_corta }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

{{-- LIGHTBOX --}}
<div x-show="lightboxAbierto"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="proyecto-lightbox"
     @click.self="cerrarLightbox()"
     @keydown.escape.window="cerrarLightbox()"
     @keydown.left.window="anterior()"
     @keydown.right.window="siguiente()"
     x-cloak>

    <button type="button" @click="cerrarLightbox()" class="proyecto-lightbox-close">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
    </button>

    @if(count($allImgUrls) > 1)
    <button type="button" @click="anterior()" class="proyecto-lightbox-nav proyecto-lightbox-prev">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
    </button>
    <button type="button" @click="siguiente()" class="proyecto-lightbox-nav proyecto-lightbox-next">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
    </button>
    @endif

    <img :src="imagenActual"
         :alt="@js($proyecto->titulo)"
         class="proyecto-lightbox-img"
         @click.stop>

    @if(count($allImgUrls) > 1)
    <div class="proyecto-lightbox-dots">
        @foreach($allImgUrls as $idx => $url)
        <button type="button"
                class="proyecto-lightbox-dot"
                :class="indiceActual === {{ $idx }} ? 'active' : ''"
                @click="seleccionar({{ $idx }}, '{{ $url }}')">
        </button>
        @endforeach
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function proyectoGaleria() {
    return {
        imagenActual: @js($displaySrc),
        indiceActual: 0,
        lightboxAbierto: false,
        todasLasImagenes: @js($allImgUrls),

        seleccionar(idx, src) {
            this.imagenActual = src;
            this.indiceActual = idx;
        },

        abrirLightbox() {
            if (!this.imagenActual) return;
            this.lightboxAbierto = true;
            document.body.style.overflow = 'hidden';
        },

        cerrarLightbox() {
            this.lightboxAbierto = false;
            document.body.style.overflow = '';
        },

        anterior() {
            if (this.todasLasImagenes.length <= 1) return;
            this.indiceActual = this.indiceActual > 0
                ? this.indiceActual - 1
                : this.todasLasImagenes.length - 1;
            this.imagenActual = this.todasLasImagenes[this.indiceActual];
        },

        siguiente() {
            if (this.todasLasImagenes.length <= 1) return;
            this.indiceActual = this.indiceActual < this.todasLasImagenes.length - 1
                ? this.indiceActual + 1
                : 0;
            this.imagenActual = this.todasLasImagenes[this.indiceActual];
        }
    };
}
</script>
@endpush
