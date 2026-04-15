@extends('layouts.admin')

@section('title', 'Admin · Productos')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
/* ── Reset para aislar el panel del layout base ── */
.adm-wrapper *,
.adm-wrapper *::before,
.adm-wrapper *::after {
    box-sizing: border-box;
}

.adm-wrapper {
    all: unset;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background: #07111e;
    color: #e2f0fb;
    font-family: 'Exo 2', sans-serif;
}

/* ── Variables ── */
:root {
    --adm-bg:      #07111e;
    --adm-surface: #0d1f33;
    --adm-card:    #0f2540;
    --adm-border:  rgba(0,212,255,0.13);
    --adm-accent:  #00d4ff;
    --adm-accent2: #2dd4bf;
    --adm-danger:  #f43f5e;
    --adm-warning: #f59e0b;
    --adm-success: #10b981;
    --adm-text:    #e2f0fb;
    --adm-muted:   #7a9ab8;
    --adm-font:    'Exo 2', sans-serif;
    --adm-radius:  14px;
}

.adm-wrapper {
    width: 100%;
    min-height: calc(100vh - 60px);
    background: var(--adm-bg);
    font-family: var(--adm-font);
    color: var(--adm-text);
    display: flex;
    flex-direction: column;
}

/* ── Top bar ── */
.adm-topbar {
    background: var(--adm-surface);
    border-bottom: 1px solid var(--adm-border);
    padding: 0 2rem;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
    flex-shrink: 0;
    backdrop-filter: blur(14px);
}
.adm-topbar-brand {
    display: flex;
    align-items: center;
    gap: .65rem;
    font-size: 1rem;
    font-weight: 800;
    letter-spacing: .04em;
    color: var(--adm-accent);
}
.adm-topbar-brand svg { width: 20px; height: 20px; flex-shrink: 0; }
.adm-topbar-right {
    display: flex;
    align-items: center;
    gap: .75rem;
    font-size: .83rem;
    color: var(--adm-muted);
}
.adm-topbar-right strong { color: var(--adm-text); }
.adm-badge-admin {
    background: rgba(0,212,255,.15);
    color: var(--adm-accent);
    border: 1px solid rgba(0,212,255,.3);
    padding: .18rem .65rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

/* ── Main ── */
.adm-main {
    flex: 1;
    max-width: 1400px;
    width: 100%;
    margin: 0 auto;
    padding: 2rem 1.5rem 4rem;
}

/* ── Page header ── */
.adm-page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.adm-page-eyebrow {
    font-size: .72rem;
    color: var(--adm-muted);
    letter-spacing: .1em;
    text-transform: uppercase;
    margin-bottom: .3rem;
}
.adm-page-title {
    font-size: 1.75rem;
    font-weight: 800;
    line-height: 1.1;
}
.adm-page-title span { color: var(--adm-accent); }

/* ── Botones ── */
.adm-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .55rem 1.25rem;
    border-radius: 10px;
    font-family: var(--adm-font);
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: all .18s;
    white-space: nowrap;
    line-height: 1;
}
.adm-btn svg { width: 15px; height: 15px; flex-shrink: 0; }
.adm-btn-primary { background: var(--adm-accent); color: #07111e; }
.adm-btn-primary:hover { background: #00bce0; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,212,255,.25); }
.adm-btn-ghost { background: transparent; color: var(--adm-muted); border: 1px solid var(--adm-border); }
.adm-btn-ghost:hover { border-color: var(--adm-accent); color: var(--adm-accent); }
.adm-btn-danger { background: rgba(244,63,94,.12); color: var(--adm-danger); border: 1px solid rgba(244,63,94,.28); }
.adm-btn-danger:hover { background: rgba(244,63,94,.25); }
.adm-btn-sm { padding: .32rem .75rem; font-size: .76rem; }

/* ── Stats ── */
.adm-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}
.adm-stat-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: var(--adm-radius);
    padding: 1.1rem 1.3rem;
    display: flex;
    align-items: center;
    gap: .9rem;
    transition: border-color .2s, transform .2s;
}
.adm-stat-card:hover { border-color: rgba(0,212,255,.35); transform: translateY(-2px); }
.adm-stat-icon {
    width: 44px; height: 44px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.adm-stat-icon svg { width: 20px; height: 20px; }
.adm-stat-val { font-size: 1.55rem; font-weight: 800; line-height: 1; }
.adm-stat-label { font-size: .75rem; color: var(--adm-muted); margin-top: .25rem; }

/* ── Toolbar / Buscador ── */
.adm-toolbar {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.adm-search-wrap {
    flex: 1;
    min-width: 220px;
    position: relative;
    display: flex;
    align-items: center;
}
.adm-search-wrap svg {
    position: absolute;
    left: .85rem;
    width: 15px; height: 15px;
    color: var(--adm-muted);
    pointer-events: none;
}
.adm-search {
    width: 100%;
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 10px;
    color: var(--adm-text);
    padding: .55rem 1rem .55rem 2.4rem;
    font-family: var(--adm-font);
    font-size: .86rem;
    outline: none;
    transition: border-color .2s;
}
.adm-search:focus { border-color: rgba(0,212,255,.5); }
.adm-search::placeholder { color: var(--adm-muted); }
.adm-select {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 10px;
    color: var(--adm-text);
    padding: .55rem 1rem;
    font-family: var(--adm-font);
    font-size: .86rem;
    outline: none;
    cursor: pointer;
    appearance: auto;
}

/* Tags de filtros activos */
.adm-filter-tags {
    display: flex;
    align-items: center;
    gap: .5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}
.adm-filter-tag {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: rgba(0,212,255,.1);
    border: 1px solid rgba(0,212,255,.25);
    color: var(--adm-accent);
    border-radius: 20px;
    padding: .2rem .7rem;
    font-size: .72rem;
    font-weight: 600;
    text-decoration: none;
}
.adm-filter-tag:hover { background: rgba(0,212,255,.18); }
.adm-results-count {
    font-size: .78rem;
    color: var(--adm-muted);
    margin-left: auto;
}

/* ── Alert ── */
.adm-alert {
    display: flex;
    align-items: center;
    gap: .65rem;
    background: rgba(16,185,129,.1);
    border: 1px solid rgba(16,185,129,.3);
    color: #10b981;
    border-radius: 10px;
    padding: .8rem 1.2rem;
    margin-bottom: 1.5rem;
    font-size: .86rem;
}
.adm-alert svg { width: 17px; height: 17px; flex-shrink: 0; }

/* ── Table ── */
.adm-table-wrap {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 16px;
    overflow: hidden;
}
.adm-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

/* 9 columnas: imagen | nombre | cat | precio | stock | estado | visible | acciones */
.adm-table colgroup col:nth-child(1) { width: 70px;  } /* imagen */
.adm-table colgroup col:nth-child(2) { width: auto;  } /* nombre */
.adm-table colgroup col:nth-child(3) { width: 110px; } /* cat */
.adm-table colgroup col:nth-child(4) { width: 100px; } /* precio */
.adm-table colgroup col:nth-child(5) { width: 130px; } /* stock */
.adm-table colgroup col:nth-child(6) { width: 100px; } /* estado */
.adm-table colgroup col:nth-child(7) { width: 72px;  } /* visible */
.adm-table colgroup col:nth-child(8) { width: 160px; } /* acciones */

.adm-table thead { background: rgba(0,212,255,.06); }
.adm-table th {
    padding: .75rem 1rem;
    text-align: left;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--adm-muted);
    border-bottom: 1px solid var(--adm-border);
    white-space: nowrap;
}
.adm-table td {
    padding: .85rem 1rem;
    font-size: .85rem;
    border-bottom: 1px solid rgba(0,212,255,.06);
    vertical-align: middle;
    overflow: hidden;
}
.adm-table tr:last-child td { border-bottom: none; }
.adm-table tbody tr:hover td { background: rgba(0,212,255,.03); }

/* Celda imagen producto */
.adm-prod-img {
    width: 46px;
    height: 46px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--adm-border);
    display: block;
    background: rgba(0,212,255,.05);
}
.adm-prod-emoji-fallback {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    border: 1px solid var(--adm-border);
    background: rgba(0,212,255,.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.adm-prod-name { font-weight: 700; color: var(--adm-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.adm-prod-slug { font-size: .7rem; color: var(--adm-muted); margin-top: .1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.adm-wrapper svg { width: 18px; height: 18px; }

/* Badges */
.adm-badge {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .2rem .6rem;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    white-space: nowrap;
}
.adm-badge-iot      { background: rgba(0,212,255,.12);   color:#00d4ff; border:1px solid rgba(0,212,255,.25); }
.adm-badge-software { background: rgba(20,184,166,.12);  color:#2dd4bf; border:1px solid rgba(20,184,166,.25); }
.adm-badge-kits     { background: rgba(122,184,245,.12); color:#7ab8f5; border:1px solid rgba(122,184,245,.25); }
.adm-estado-nuevo       { background: rgba(245,158,11,.12); color:#f59e0b; border:1px solid rgba(245,158,11,.3); }
.adm-estado-disponible  { background: rgba(0,212,255,.12);  color:#00d4ff; border:1px solid rgba(0,212,255,.3); }
.adm-estado-agotado     { background: rgba(244,63,94,.12);  color:#f43f5e; border:1px solid rgba(244,63,94,.3); }

/* Toggle */
.adm-toggle { position: relative; display: inline-block; width: 40px; height: 22px; cursor: pointer; }
.adm-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
.adm-toggle-slider {
    position: absolute; inset: 0;
    background: rgba(255,255,255,.08);
    border: 1px solid var(--adm-border);
    border-radius: 22px;
    transition: .25s;
}
.adm-toggle-slider::before {
    content: '';
    position: absolute;
    width: 15px; height: 15px;
    left: 3px; top: 2px;
    background: var(--adm-muted);
    border-radius: 50%;
    transition: .25s;
}
.adm-toggle input:checked + .adm-toggle-slider { background: rgba(0,212,255,.2); border-color: rgba(0,212,255,.4); }
.adm-toggle input:checked + .adm-toggle-slider::before { transform: translateX(17px); background: var(--adm-accent); }

/* Stock */
.adm-stock-form { display: flex; align-items: center; gap: .35rem; }
.adm-stock-input {
    width: 58px;
    background: rgba(0,212,255,.07);
    border: 1px solid var(--adm-border);
    border-radius: 7px;
    color: var(--adm-text);
    padding: .3rem .45rem;
    font-family: var(--adm-font);
    font-size: .82rem;
    text-align: center;
    outline: none;
    transition: border-color .2s;
}
.adm-stock-input:focus { border-color: rgba(0,212,255,.5); }

/* Acciones */
.adm-actions { display: flex; gap: .35rem; align-items: center; flex-wrap: nowrap; }

/* Empty */
.adm-empty { text-align: center; padding: 3.5rem 2rem; color: var(--adm-muted); }
.adm-empty-icon { font-size: 2.8rem; margin-bottom: .75rem; }

/* Pagination */
.adm-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--adm-border);
    flex-wrap: wrap;
    gap: .5rem;
}
.adm-pagination-info {
    font-size: .78rem;
    color: var(--adm-muted);
}
.adm-pagination nav span,
.adm-pagination nav a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 .6rem;
    border-radius: 8px;
    font-size: .78rem;
    font-family: var(--adm-font);
    font-weight: 600;
    color: var(--adm-muted);
    border: 1px solid var(--adm-border);
    text-decoration: none;
    margin: 0 2px;
    background: transparent;
    transition: all .15s;
}
.adm-pagination nav a:hover { border-color: var(--adm-accent); color: var(--adm-accent); }
.adm-pagination nav [aria-current="page"] span,
.adm-pagination nav span[aria-current="page"] {
    background: var(--adm-accent);
    color: #07111e;
    border-color: var(--adm-accent);
}

/* ── Responsive ── */
@media (max-width: 1024px) {
    .adm-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .adm-topbar { padding: 0 1rem; }
    .adm-main { padding: 1rem 1rem 3rem; }
    .adm-stats { grid-template-columns: 1fr 1fr; }
    .adm-table th:nth-child(5),
    .adm-table td:nth-child(5),
    .adm-table th:nth-child(6),
    .adm-table td:nth-child(6) { display: none; }
}
@media (max-width: 540px) {
    .adm-stats { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="adm-wrapper">

    {{-- Top Bar --}}
    <header class="adm-topbar">
        <div class="adm-topbar-brand">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            Panel Admin · Tienda
        </div>
        <div class="adm-topbar-right">
            <span class="adm-badge-admin">Admin</span>
            <strong>{{ auth()->user()->name }}</strong>
            <a href="{{ url('/tienda') }}" class="adm-btn adm-btn-ghost adm-btn-sm">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                Ver tienda
            </a>
        </div>
    </header>

    <main class="adm-main">

        {{-- Alerta éxito --}}
        @if(session('exito'))
        <div class="adm-alert">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            {{ session('exito') }}
        </div>
        @endif

        {{-- Header --}}
        <div class="adm-page-header">
            <div>
                <div class="adm-page-eyebrow">Gestión</div>
                <h1 class="adm-page-title">Productos de la <span>Tienda</span></h1>
            </div>
            <a href="{{ route('admin.productos.create') }}" class="adm-btn adm-btn-primary">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Nuevo producto
            </a>
        </div>

        {{-- Stats --}}
        <div class="adm-stats">
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,212,255,.12)">
                    <svg viewBox="0 0 24 24" fill="rgba(0,212,255,.9)"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                </div>
                <div>
                    <div class="adm-stat-val">{{ $stats['total'] }}</div>
                    <div class="adm-stat-label">Total productos</div>
                </div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(16,185,129,.12)">
                    <svg viewBox="0 0 24 24" fill="rgba(16,185,129,.9)"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                </div>
                <div>
                    <div class="adm-stat-val">{{ $stats['activos'] }}</div>
                    <div class="adm-stat-label">Activos en tienda</div>
                </div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(244,63,94,.12)">
                    <svg viewBox="0 0 24 24" fill="rgba(244,63,94,.9)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                </div>
                <div>
                    <div class="adm-stat-val">{{ $stats['agotados'] }}</div>
                    <div class="adm-stat-label">Agotados</div>
                </div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(245,158,11,.12)">
                    <svg viewBox="0 0 24 24" fill="rgba(245,158,11,.9)"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                </div>
                <div>
                    <div class="adm-stat-val">S/ {{ number_format($stats['ingresos'], 0) }}</div>
                    <div class="adm-stat-label">Valor catálogo</div>
                </div>
            </div>
        </div>

        {{-- Toolbar / Buscador --}}
        <form method="GET" action="{{ route('admin.productos.index') }}" class="adm-toolbar" id="searchForm">
            <div class="adm-search-wrap">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
                <input type="text" name="buscar" id="buscarInput"
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por nombre o slug..."
                       class="adm-search"
                       autocomplete="off">
            </div>
            <select name="cat" class="adm-select" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                <option value="iot"      {{ request('cat')==='iot'      ? 'selected' : '' }}>IoT / Electrónica</option>
                <option value="software" {{ request('cat')==='software' ? 'selected' : '' }}>Software</option>
                <option value="kits"     {{ request('cat')==='kits'     ? 'selected' : '' }}>Kits</option>
            </select>
            <select name="estado" class="adm-select" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="disponible" {{ request('estado')==='disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="nuevo"      {{ request('estado')==='nuevo'      ? 'selected' : '' }}>Nuevo</option>
                <option value="agotado"    {{ request('estado')==='agotado'    ? 'selected' : '' }}>Agotado</option>
            </select>
            <button type="submit" class="adm-btn adm-btn-ghost">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                Buscar
            </button>
            @if(request()->hasAny(['buscar','cat','estado']))
            <a href="{{ route('admin.productos.index') }}" class="adm-btn adm-btn-ghost">✕ Limpiar</a>
            @endif
        </form>

        {{-- Tags de filtros activos + contador --}}
        @if(request()->hasAny(['buscar','cat','estado']))
        <div class="adm-filter-tags">
            @if(request('buscar'))
            <a href="{{ route('admin.productos.index', array_merge(request()->except('buscar','page'))) }}"
               class="adm-filter-tag">🔍 "{{ request('buscar') }}" ✕</a>
            @endif
            @if(request('cat'))
            <a href="{{ route('admin.productos.index', array_merge(request()->except('cat','page'))) }}"
               class="adm-filter-tag">📂 {{ ucfirst(request('cat')) }} ✕</a>
            @endif
            @if(request('estado'))
            <a href="{{ route('admin.productos.index', array_merge(request()->except('estado','page'))) }}"
               class="adm-filter-tag">🏷 {{ ucfirst(request('estado')) }} ✕</a>
            @endif
            <span class="adm-results-count">{{ $productos->total() }} resultado(s)</span>
        </div>
        @endif

        {{-- Tabla --}}
        <div class="adm-table-wrap">
            @if($productos->count())
            <table class="adm-table">
                <colgroup>
                    <col><col><col><col><col><col><col><col>
                </colgroup>
                <thead>
                    <tr>
                        <th style="text-align:center">Imagen</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th style="text-align:center">Visible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($productos as $p)
                <tr>
                    {{-- 1. Imagen (con fallback a emoji) --}}
                    <td style="text-align:center">
                        @if($p->icono_imagen)
                            <img src="{{ asset('storage/' . $p->icono_imagen) }}"
                                 class="adm-prod-img"
                                 alt="{{ $p->nombre }}"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="adm-prod-emoji-fallback" style="display:none">
                                {{ $p->icono_emoji }}
                            </div>
                        @else
                            <div class="adm-prod-emoji-fallback">
                                {{ $p->icono_emoji }}
                            </div>
                        @endif
                    </td>

                    {{-- 2. Nombre + slug --}}
                    <td>
                        <div class="adm-prod-name">{{ $p->nombre }}</div>
                        <div class="adm-prod-slug">{{ $p->slug }}</div>
                    </td>

                    {{-- 3. Categoría --}}
                    <td>
                        <span class="adm-badge adm-badge-{{ $p->categoria }}">
                            {{ ucfirst($p->categoria) }}
                        </span>
                    </td>

                    {{-- 4. Precio --}}
                    <td>
                        <div style="font-weight:700">S/ {{ number_format($p->precio,2) }}</div>
                        @if($p->precio_anterior)
                        <div style="font-size:.73rem;color:var(--adm-muted);text-decoration:line-through">
                            S/ {{ number_format($p->precio_anterior,2) }}
                        </div>
                        @endif
                    </td>

                    {{-- 5. Stock --}}
                    <td>
                        <form method="POST" action="{{ route('admin.productos.stock', $p) }}"
                              class="adm-stock-form">
                            @csrf @method('PATCH')
                            <input type="number" name="stock" value="{{ $p->stock }}"
                                   min="0" class="adm-stock-input">
                            <button type="submit" class="adm-btn adm-btn-ghost adm-btn-sm"
                                    title="Guardar">✓</button>
                        </form>
                    </td>

                    {{-- 6. Estado --}}
                    <td>
                        <span class="adm-badge adm-estado-{{ $p->estado }}">
                            {{ ucfirst($p->estado) }}
                        </span>
                    </td>

                    {{-- 7. Toggle visible --}}
                    <td style="text-align:center">
                        <form method="POST" action="{{ route('admin.productos.toggle', $p) }}">
                            @csrf @method('PATCH')
                            <label class="adm-toggle" title="{{ $p->activo ? 'Ocultar' : 'Mostrar' }}">
                                <input type="checkbox" {{ $p->activo ? 'checked' : '' }}
                                       onchange="this.form.submit()">
                                <span class="adm-toggle-slider"></span>
                            </label>
                        </form>
                    </td>

                    {{-- 8. Acciones --}}
                    <td>
                        <div class="adm-actions">
                            <a href="{{ route('admin.productos.edit', $p) }}"
                               class="adm-btn adm-btn-ghost adm-btn-sm">
                                <svg viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                </svg>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('admin.productos.destroy', $p) }}"
                                  onsubmit="return confirm('¿Eliminar «{{ $p->nombre }}»?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="adm-btn adm-btn-danger adm-btn-sm">
                                    <svg viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px">
                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            {{-- Paginación --}}
            @if($productos->hasPages())
            <div class="adm-pagination">
                <span class="adm-pagination-info">
                    Mostrando {{ $productos->firstItem() }}–{{ $productos->lastItem() }}
                    de {{ $productos->total() }} productos
                </span>
                {{ $productos->appends(request()->query())->links() }}
            </div>
            @endif

            @else
            <div class="adm-empty">
                <div class="adm-empty-icon">📦</div>
                @if(request()->hasAny(['buscar','cat','estado']))
                    <p>Sin resultados para los filtros aplicados.</p>
                    <a href="{{ route('admin.productos.index') }}" class="adm-btn adm-btn-ghost"
                       style="margin-top:1.25rem">Limpiar filtros</a>
                @else
                    <p>Aún no hay productos registrados.</p>
                    <a href="{{ route('admin.productos.create') }}" class="adm-btn adm-btn-primary"
                       style="margin-top:1.25rem">+ Agregar el primero</a>
                @endif
            </div>
            @endif
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script>
    // Enviar búsqueda al presionar Enter
    document.getElementById('buscarInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });
</script>
@endpush