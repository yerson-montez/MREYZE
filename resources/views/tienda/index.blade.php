
@extends('welcome')

@section('title', 'Tienda — MREYZE')

@section('content')

{{-- resources/views/sections/tienda.blade.php --}}
{{-- Uso: @include('sections.tienda') --}}

<style>
    /* ═══════════════════════════════════════
       TIENDA SECTION
    ═══════════════════════════════════════ */
    .tienda {
        padding: 7rem 2rem;
        background: var(--ice-800);
        position: relative;
        overflow: hidden;
    }

    .tienda::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .tienda-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 90% 10%, rgba(0,212,255,0.07) 0%, transparent 50%),
            radial-gradient(ellipse at 10% 90%, rgba(30,77,153,0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .tienda-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .tienda-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .tienda-ver-mas {
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
    .tienda-ver-mas svg { width: 15px; height: 15px; transition: transform 0.2s; }
    .tienda-ver-mas:hover { background: rgba(0,212,255,0.1); border-color: rgba(0,212,255,0.5); }
    .tienda-ver-mas:hover svg { transform: translateX(4px); }

    /* Categorías */
    .tienda-cats {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .cat-btn {
        min-height: 44px;
        padding: 0.4rem 1.1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid rgba(74,144,217,0.2);
        background: transparent;
        color: var(--ice-300);
        transition: all 0.22s;
    }
    .cat-btn:hover { border-color: rgba(74,144,217,0.5); color: var(--ice-100); }
    .cat-btn.active { background: rgba(37,99,199,0.3); border-color: rgba(0,212,255,0.5); color: var(--accent); }

    /* Grid de productos */
    .tienda-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
    }

    /* Tarjeta de producto */
    .producto-card {
        background: rgba(10,22,40,0.7);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.35s;
        position: relative;
    }

    .producto-card:hover {
        border-color: rgba(0,212,255,0.35);
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(0,30,90,0.5), 0 0 0 1px rgba(0,212,255,0.08);
    }

    /* Imagen del producto */
    .producto-img {
        height: 160px;
        background: rgba(13,31,60,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .producto-img-bg {
         width: 100%;
         height: 100%;
         position: relative; 
         opacity: 1;      
    }

    .producto-card:hover .producto-img-bg { opacity: 0.1; transform: scale(1.1); }

    .producto-img-icon {
        position: relative;
        z-index: 2;
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
    }

    .producto-img-icon svg { width: 28px; height: 28px; }
    .producto-card:hover .producto-img-icon { transform: scale(1.1) rotate(-5deg); }

    /* Badge de stock */
    .producto-stock {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .producto-stock.disponible { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
    .producto-stock.agotado   { background: rgba(239,68,68,0.15);  border: 1px solid rgba(239,68,68,0.3);  color: #f87171; }
    .producto-stock.nuevo     { background: rgba(0,212,255,0.15);   border: 1px solid rgba(0,212,255,0.3);   color: var(--accent); }

    /* Badge descuento */
    .producto-descuento {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        background: rgba(251,191,36,0.2);
        border: 1px solid rgba(251,191,36,0.4);
        color: #fbbf24;
    }

    /* Cuerpo */
    .producto-body {
        padding: 1.1rem;
    }

    .producto-cat {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.4rem;
    }

    .producto-nombre {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.9rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.4rem;
        line-height: 1.3;
    }

    .producto-desc {
        font-size: 0.75rem;
        color: var(--ice-300);
        line-height: 1.55;
        margin-bottom: 1rem;
    }

    /* Precio y botón */
    .producto-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .producto-precio {
        display: flex;
        flex-direction: column;
    }

    .producto-precio-actual {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
    }

    .producto-precio-actual span { color: var(--accent); font-size: 0.75rem; }

    .producto-precio-anterior {
        font-size: 0.72rem;
        color: var(--ice-300);
        text-decoration: line-through;
    }

    .producto-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 0.5rem 0.9rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--ice-500), var(--ice-400));
        border: none;
        cursor: pointer;
        transition: all 0.25s;
        text-decoration: none;
        white-space: nowrap;
    }
    .producto-btn svg { width: 13px; height: 13px; }
    .producto-btn:hover {
        background: linear-gradient(135deg, var(--ice-400), var(--glow));
        box-shadow: 0 4px 14px rgba(59,130,246,0.45);
        transform: translateY(-1px);
        color: #fff;
    }
    .producto-btn:disabled, .producto-btn.agotado-btn {
        background: rgba(74,144,217,0.15);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
        color: var(--ice-300);
    }

    .producto-img-full {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
   }

    /* Banner promo */
    .tienda-banner {
        margin-top: 3rem;
        padding: 2rem 2.5rem;
        background: linear-gradient(135deg, rgba(30,77,153,0.3), rgba(0,212,255,0.08));
        border: 1px solid rgba(0,212,255,0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
        position: relative;
        overflow: hidden;
    }

    .tienda-banner::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(0,212,255,0.05);
        border: 1px solid rgba(0,212,255,0.1);
    }

    .tienda-banner-text {}
    .tienda-banner-title {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.3rem;
    }
    .tienda-banner-desc { font-size: 0.85rem; color: var(--ice-200); }
    .tienda-banner-desc strong { color: var(--accent); }

    @media (max-width: 1100px) { .tienda-grid { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); } }
    @media (max-width: 768px)  { .tienda-grid { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); } }
    @media (max-width: 480px)  { .tienda-grid { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); } }

    /* ── Celulares: catálogo compacto estilo AliExpress (4 productos por fila) ── */
    @media (max-width: 520px) {
        .tienda { padding: 4rem 0.5rem; }
        .tienda-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; margin-bottom: 1.5rem; }
        .tienda-header > div { flex-wrap: wrap; }
        .tienda-header .section-title { font-size: 1.35rem; }
        .tienda-ver-mas { padding: 0.45rem 0.9rem; font-size: 0.72rem; }
        .tienda-cats { gap: 0.35rem; margin-bottom: 1.5rem; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 4px; }
        .cat-btn { min-height: 44px; padding: 0.55rem 0.8rem; font-size: 0.72rem; flex-shrink: 0; }
        .search-input { padding: 0.55rem 2.4rem 0.55rem 2.2rem; font-size: 0.82rem; }
        .tienda-banner { padding: 1.4rem 1.2rem; gap: 1rem; }

        .tienda-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.65rem; }

        .producto-card { border-radius: 10px; }
        .producto-card:hover { transform: none; box-shadow: 0 8px 20px rgba(0,30,90,0.4); }
        .producto-img { height: auto; aspect-ratio: 4 / 3; }
        .producto-img-icon { width: 38px; height: 38px; border-radius: 10px; }
        .producto-img-icon svg { width: 18px; height: 18px; }
        .producto-stock { top: 5px; left: 5px; font-size: 0.52rem; padding: 2px 5px; }
        .producto-descuento { top: 5px; right: 5px; font-size: 0.55rem; padding: 2px 5px; border-radius: 4px; }
        .producto-body { padding: 0.5rem; }
        .producto-cat { font-size: 0.52rem; margin-bottom: 0.2rem; }
        .producto-nombre {
            font-size: 0.68rem;
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .producto-desc { display: none; }
        .producto-footer { flex-direction: column; align-items: stretch; gap: 0.4rem; }
        .producto-precio-actual { font-size: 0.82rem; }
        .producto-precio-actual span { font-size: 0.6rem; }
        .producto-precio-anterior { font-size: 0.6rem; }
        .producto-btn { min-height: 44px; padding: 0.45rem 0.4rem; font-size: 0.62rem; justify-content: center; gap: 3px; }
        .producto-btn svg { width: 11px; height: 11px; }
        .producto-thumbs { display: none; }
    }
    @media (max-width: 360px) {
        .tienda-grid { grid-template-columns: 1fr; gap: 0.75rem; }
        .producto-img { height: auto; aspect-ratio: 16 / 10; }
        .producto-nombre { font-size: 0.62rem; }
        .producto-precio-actual { font-size: 0.76rem; }
    }

    /* Buscador */
    .tienda-search { margin-bottom: 1.5rem; }
    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .search-icon {
        position: absolute;
        left: 14px;
        width: 18px;
        height: 18px;
        color: var(--ice-300);
        pointer-events: none;
        flex-shrink: 0;
    }
    .search-input {
        width: 100%;
        padding: 0.7rem 2.8rem 0.7rem 2.6rem;
        border-radius: 12px;
        font-size: 0.9rem;
        background: rgba(10, 22, 40, 0.7);
        border: 1px solid rgba(74, 144, 217, 0.2);
        color: #fff;
        outline: none;
        transition: border-color 0.22s, box-shadow 0.22s;
    }
    .search-input::placeholder { color: var(--ice-300); }
    .search-input:focus {
        border-color: rgba(0, 212, 255, 0.45);
        box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.08);
    }
    .search-spinner {
        position: absolute;
        right: 12px;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(74, 144, 217, 0.2);
        border-top-color: var(--accent);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.2s;
        pointer-events: none;
    }
    .search-spinner.active { opacity: 1; animation: spin 0.6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .search-clear {
        position: absolute;
        right: 12px;
        width: 20px;
        height: 20px;
        border: none;
        background: none;
        color: var(--ice-300);
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 1.1rem;
        line-height: 1;
        transition: color 0.2s;
    }
    .search-clear:hover { color: #fff; }
    .search-clear.visible { display: flex; }
    .search-has-text .search-spinner { display: none; }
    .tienda-grid.searching { opacity: 0.4; transition: opacity 0.2s; }
    .tienda-empty { grid-column: 1 / -1; }

    /* ═══════════════════════════════════════
       TIENDA — ESTADO BÚSQUEDA (lupa)
       Tarjetas compactas SOLO en resultados de búsqueda.
       El catálogo normal de /tienda mantiene su diseño actual.
    ═══════════════════════════════════════ */
    .tienda-grid.has-search {
        grid-template-columns: repeat(auto-fill, minmax(158px, 1fr));
        gap: 0.85rem;
    }

    .tienda-grid.has-search .producto-card {
        border-radius: 12px;
    }
    .tienda-grid.has-search .producto-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0,30,90,0.45), 0 0 0 1px rgba(0,212,255,0.06);
    }

    .tienda-grid.has-search .producto-img { height: 110px; }
    .tienda-grid.has-search .producto-img-icon { width: 44px; height: 44px; border-radius: 12px; }
    .tienda-grid.has-search .producto-img-icon svg { width: 22px; height: 22px; }

    .tienda-grid.has-search .producto-stock { top: 6px; left: 6px; font-size: 0.58rem; padding: 2px 7px; }
    .tienda-grid.has-search .producto-descuento { top: 6px; right: 6px; font-size: 0.62rem; padding: 2px 6px; }

    .tienda-grid.has-search .producto-body { padding: 0.7rem 0.75rem 0.8rem; }
    .tienda-grid.has-search .producto-cat { font-size: 0.6rem; margin-bottom: 0.25rem; letter-spacing: 0.08em; }
    .tienda-grid.has-search .producto-nombre {
        font-size: 0.78rem;
        margin-bottom: 0.45rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .tienda-grid.has-search .producto-desc { display: none; }

    .tienda-grid.has-search .producto-footer { gap: 0.4rem; }
    .tienda-grid.has-search .producto-precio-actual { font-size: 0.9rem; }
    .tienda-grid.has-search .producto-precio-actual span { font-size: 0.62rem; }
    .tienda-grid.has-search .producto-precio-anterior { font-size: 0.62rem; }

    .tienda-grid.has-search .producto-btn {
        min-height: 44px;
        padding: 0.42rem 0.6rem;
        font-size: 0.7rem;
        border-radius: 7px;
    }
    .tienda-grid.has-search .producto-btn svg { width: 12px; height: 12px; }

    .tienda-grid.has-search .producto-thumbs { display: none; }

    /* Búsqueda — tablet pequeña */
    @media (max-width: 768px) {
        .tienda-grid.has-search { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.6rem; }
    }

    /* Búsqueda — móvil */
    @media (max-width: 520px) {
        .tienda-grid.has-search { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.65rem; }
        .tienda-grid.has-search .producto-card { border-radius: 9px; }
        .tienda-grid.has-search .producto-card:hover { transform: none; }
        .tienda-grid.has-search .producto-img { height: auto; aspect-ratio: 4 / 3; }
        .tienda-grid.has-search .producto-img-icon { width: 34px; height: 34px; border-radius: 9px; }
        .tienda-grid.has-search .producto-img-icon svg { width: 17px; height: 17px; }
        .tienda-grid.has-search .producto-stock { top: 4px; left: 4px; font-size: 0.48rem; padding: 1px 5px; }
        .tienda-grid.has-search .producto-descuento { top: 4px; right: 4px; font-size: 0.5rem; padding: 1px 4px; border-radius: 3px; }
        .tienda-grid.has-search .producto-body { padding: 0.4rem; }
        .tienda-grid.has-search .producto-cat { font-size: 0.48rem; margin-bottom: 0.15rem; }
        .tienda-grid.has-search .producto-nombre { font-size: 0.62rem; margin-bottom: 0.3rem; }
        .tienda-grid.has-search .producto-footer { flex-direction: column; align-items: stretch; gap: 0.3rem; }
        .tienda-grid.has-search .producto-precio-actual { font-size: 0.76rem; }
        .tienda-grid.has-search .producto-precio-actual span { font-size: 0.55rem; }
        .tienda-grid.has-search .producto-precio-anterior { font-size: 0.56rem; }
        .tienda-grid.has-search .producto-btn { min-height: 44px; padding: 0.32rem 0.3rem; font-size: 0.62rem; justify-content: center; gap: 3px; }
        .tienda-grid.has-search .producto-btn svg { width: 10px; height: 10px; }
    }

    /* Búsqueda — móvil muy pequeño */
    @media (max-width: 360px) {
        .tienda-grid.has-search { gap: 0.28rem; }
        .tienda-grid.has-search .producto-img { height: 60px; }
        .tienda-grid.has-search .producto-nombre { font-size: 0.56rem; }
        .tienda-grid.has-search .producto-precio-actual { font-size: 0.7rem; }
    }
</style>

<style>
    /* Ecommerce layer: keeps the existing product and cart markup intact. */
    .tienda {
        padding: clamp(4rem, 8vw, 7rem) clamp(1rem, 4vw, 2rem);
        background: linear-gradient(180deg, #071522 0%, #091d30 100%);
    }
    .tienda-container { width: min(100%, 78rem); }
    .tienda-header {
        position: relative;
        isolation: isolate;
        padding: clamp(1.5rem, 4vw, 2.5rem);
        border: 1px solid rgba(130, 190, 240, .12);
        border-radius: 1rem;
        overflow: hidden;
        background-image: linear-gradient(90deg, rgba(7, 21, 34, .94), rgba(7, 21, 34, .72)), url('/img/fondo3.png');
        background-size: cover;
        background-position: 62% bottom;
    }
    .tienda-header { margin-bottom: 2rem; }
    .tienda-ver-mas, .cat-btn, .producto-btn { min-height: 44px; }
    .tienda-cats { gap: .55rem; margin-bottom: 2rem; }
    .tienda-grid { grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap: 1rem; }
    .producto-card { border-radius: 1rem; background: rgba(8, 25, 41, .84); border-color: rgba(130, 190, 240, .14); }
    .producto-card:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(0, 0, 0, .2); }
    .producto-img { height: auto; aspect-ratio: 4 / 3; }
    .producto-img-real { object-fit: cover; }
    .producto-body { display: flex; flex-direction: column; min-height: 10rem; }
    .producto-nombre { font-size: 1rem; letter-spacing: -.02em; }
    .producto-desc { color: #a9bfd3; }
    .producto-footer { margin-top: auto; align-items: flex-end; }
    .producto-btn { justify-content: center; padding: .55rem .8rem; }

    @media (max-width: 680px) {
        .tienda-header {
            position: relative;
            isolation: isolate;
            padding: 1.5rem 1rem;
            border: 1px solid rgba(130, 190, 240, .12);
            border-radius: 1rem;
            overflow: hidden;
            background-image: linear-gradient(180deg, rgba(7, 21, 34, .9), rgba(7, 21, 34, .78)), url('/img/fondo3.png');
            background-size: cover;
            background-position: 62% bottom;
        }
        .tienda-header { align-items: stretch; }
        .tienda-ver-mas { justify-content: center; width: 100%; }
        .tienda-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .7rem; }
        .producto-body { padding: .8rem; min-height: 9rem; }
        .producto-desc { display: none; }
        .producto-footer { flex-direction: column; align-items: stretch; }
        .producto-btn { width: 100%; }
    }

    @media (max-width: 390px) {
        .tienda-grid { grid-template-columns: 1fr; }
        .producto-img { aspect-ratio: 16 / 10; }
    }
</style>

{{--
    tiendaCliente.blade.php  ·  sección pública
    Los productos ahora vienen del controlador: compact('productos')
--}}

<section class="tienda" id="tienda">
    <div class="tienda-bg"></div>
    <div class="tienda-container">

        {{-- Header --}}
        <div class="tienda-header reveal">
            <div>
                <div class="section-label">Productos</div>
                <h2 class="section-title">Nuestra <span>Tienda</span></h2>
            </div>
            <div style="display:flex;align-items:center;gap:1rem">

                {{-- Botón admin (solo para admins autenticados) --}}
                @auth
                    @if(auth()->user()->esAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       style="display:inline-flex;align-items:center;gap:.4rem;
                              padding:.45rem 1rem;border-radius:8px;font-size:.8rem;font-weight:600;
                              background:rgba(0,212,255,.1);color:#00d4ff;
                              border:1px solid rgba(0,212,255,.3);text-decoration:none;
                              transition:all .18s"
                       onmouseover="this.style.background='rgba(0,212,255,.2)'"
                       onmouseout="this.style.background='rgba(0,212,255,.1)'">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        Panel admin
                    </a>
                    @endif
                    @endauth

                    @auth
                    <a href="{{ route('pedidos.mis') }}"
                       title="Mis pedidos"
                       style="display:inline-flex;align-items:center;gap:.4rem;
                              padding:.45rem 1rem;border-radius:8px;font-size:.8rem;font-weight:600;
                              background:rgba(0,212,255,.1);color:#00d4ff;
                              border:1px solid rgba(0,212,255,.3);text-decoration:none;
                              transition:all .18s"
                       onmouseover="this.style.background='rgba(0,212,255,.2)'"
                       onmouseout="this.style.background='rgba(0,212,255,.1)'">
                        📦 Mis pedidos
                    </a>
                    @endauth

                <a href="{{ url('/tienda') }}" class="tienda-ver-mas">
                    Ver catálogo completo
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
                </a>
            </div>
        </div>

        {{-- Buscador --}}
        <div class="tienda-search reveal">
            <div class="search-wrapper">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" id="tiendaSearch" class="search-input" placeholder="Buscar productos…" autocomplete="off">
                <div class="search-spinner" id="searchSpinner"></div>
                <button class="search-clear" id="searchClear" aria-label="Limpiar búsqueda">&times;</button>
            </div>
        </div>

        {{-- Categorías --}}
        <div class="tienda-cats reveal">
            <button class="cat-btn active" onclick="filtrarCat('todos', this)">Todos</button>
            <button class="cat-btn" onclick="filtrarCat('iot', this)">IoT / Electrónica</button>
            <button class="cat-btn" onclick="filtrarCat('software', this)">Software</button>
            <button class="cat-btn" onclick="filtrarCat('kits', this)">Kits</button>
        </div>

        {{-- Productos dinámicos desde DB --}}
        <div class="tienda-grid {{ request('search') ? 'has-search' : '' }}" id="tiendaGrid">

            @php($emptyMessage = 'No hay productos disponibles en este momento.')
            @include('tienda._productos')

        </div>

        {{-- Banner promo --}}
        <div class="tienda-banner reveal">
            <div class="tienda-banner-text">
                <div class="tienda-banner-title">🎯 ¿Necesitas algo específico?</div>
                <p class="tienda-banner-desc">Fabricamos dispositivos electrónicos y desarrollamos software <strong>completamente a medida</strong>. Cuéntanos tu necesidad.</p>
            </div>
            <a href="{{ url('/contacto') }}" class="hero-btn-primary" style="display:inline-flex;flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:17px;height:17px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                Pedir cotización
            </a>
        </div>

    </div>
</section>

<script>
// ── Filtro por categoría ──
let currentCat = 'todos';
function filtrarCat(cat, btn) {
    currentCat = cat;
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.producto-card').forEach(card => {
        card.style.display = (cat === 'todos' || card.dataset.cat === cat) ? '' : 'none';
    });
}

// ── Buscador en tiempo real ──
(function() {
    const input  = document.getElementById('tiendaSearch');
    const grid   = document.getElementById('tiendaGrid');
    const spin   = document.getElementById('searchSpinner');
    const clear  = document.getElementById('searchClear');
    let debounceTimer;

    if (!input) return;

    function doSearch(term) {
        term = term.trim();

        clear.classList.toggle('visible', term.length > 0);
        grid.classList.toggle('has-search', term.length > 0);

        const params = new URLSearchParams({ ajax: '1', cat: currentCat });
        if (term) params.set('search', term);

        spin.classList.add('active');
        grid.classList.add('searching');

        fetch('/tienda?' + params.toString())
            .then(r => r.json())
            .then(data => {
                grid.innerHTML = data.html;
                grid.classList.remove('searching');
                spin.classList.remove('active');
                // Re-observar elementos reveal
                document.querySelectorAll('.reveal').forEach(el => {
                    if (typeof observerReveal === 'function') observerReveal(el);
                });
            })
            .catch(() => {
                grid.classList.remove('searching');
                spin.classList.remove('active');
            });
    }

    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const term = this.value;
        if (term === '') {
            // Al borrar todo, recargar sin search
            clearTimeout(debounceTimer);
            doSearch('');
            return;
        }
        debounceTimer = setTimeout(() => doSearch(term), 300);
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(debounceTimer);
            doSearch(this.value);
        }
    });

    clear.addEventListener('click', function() {
        input.value = '';
        clear.classList.remove('visible');
        clearTimeout(debounceTimer);
        doSearch('');
        input.focus();
    });
})();

// Re-inicializar reveal en elementos cargados vía AJAX
function observerReveal(el) {
    if (el.classList.contains('observed')) return;
    el.classList.add('observed');
    new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) e.target.classList.add('visible');
        });
    }, { threshold: 0.12 }).observe(el);
}
</script>
@endsection 