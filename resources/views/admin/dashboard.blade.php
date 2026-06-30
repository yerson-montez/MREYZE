@extends('layouts.admin')

@section('title', 'Admin · Dashboard')

@section('breadcrumb')
    <span class="adm-breadcrumb-current">Inicio</span>
@endsection

@section('content')

<style>
/* ═══════════════════════════════════════
   DASHBOARD STYLES
═══════════════════════════════════════ */

:root {
    --dash-bg:       #060f1e;
    --dash-card:     rgba(8, 20, 40, 0.85);
    --dash-border:   rgba(74, 144, 217, 0.15);
    --dash-accent:   #00d4ff;
    --dash-blue:     #1e4d99;
    --dash-text:     #e2eaf4;
    --dash-muted:    #6b8aaa;
    --dash-green:    #22c55e;
    --dash-yellow:   #fbbf24;
    --dash-red:      #ef4444;
    --dash-glow:     rgba(0, 212, 255, 0.12);
}

/* ── Encabezado ── */
.dash-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}
.dash-greeting {
    font-size: .78rem;
    font-weight: 600;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--dash-accent);
    margin-bottom: .35rem;
}
.dash-title {
    font-family: 'Exo 2', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    color: var(--dash-text);
    line-height: 1.15;
}
.dash-title span { color: var(--dash-accent); }
.dash-date {
    font-size: .8rem;
    color: var(--dash-muted);
    margin-top: .4rem;
}

/* ── KPI Cards ── */
.dash-kpis {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 2rem;
}
@media (max-width: 1100px) { .dash-kpis { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px)  { .dash-kpis { grid-template-columns: 1fr; } }

.kpi-card {
    background: var(--dash-card);
    border: 1px solid var(--dash-border);
    border-radius: 16px;
    padding: 1.5rem 1.6rem;
    position: relative;
    overflow: hidden;
    transition: transform .25s, box-shadow .25s, border-color .25s;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(0, 20, 60, .5);
    border-color: rgba(0, 212, 255, .3);
}
.kpi-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--kpi-glow, transparent);
    pointer-events: none;
}
.kpi-card.blue   { --kpi-glow: radial-gradient(ellipse at top left, rgba(0,212,255,.07)   0%, transparent 65%); }
.kpi-card.green  { --kpi-glow: radial-gradient(ellipse at top left, rgba(34,197,94,.07)   0%, transparent 65%); }
.kpi-card.yellow { --kpi-glow: radial-gradient(ellipse at top left, rgba(251,191,36,.07)  0%, transparent 65%); }
.kpi-card.red    { --kpi-glow: radial-gradient(ellipse at top left, rgba(239,68,68,.07)   0%, transparent 65%); }

.kpi-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.1rem;
}
.kpi-icon.blue   { background: rgba(0,212,255,.12);  border: 1px solid rgba(0,212,255,.25); }
.kpi-icon.green  { background: rgba(34,197,94,.12);  border: 1px solid rgba(34,197,94,.25); }
.kpi-icon.yellow { background: rgba(251,191,36,.12); border: 1px solid rgba(251,191,36,.25); }
.kpi-icon.red    { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.25); }
.kpi-icon svg { width: 20px; height: 20px; }

.kpi-label {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--dash-muted);
    margin-bottom: .4rem;
}
.kpi-value {
    font-family: 'Exo 2', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    color: var(--dash-text);
    line-height: 1;
}
.kpi-value.blue   { color: var(--dash-accent); }
.kpi-value.green  { color: #4ade80; }
.kpi-value.yellow { color: var(--dash-yellow); }
.kpi-value.red    { color: #f87171; }
.kpi-sub { font-size: .75rem; color: var(--dash-muted); margin-top: .45rem; }

/* ── Sección de 2 columnas ── */
.dash-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
@media (max-width: 860px) { .dash-row { grid-template-columns: 1fr; } }

/* ── Paneles genéricos ── */
.dash-panel {
    background: var(--dash-card);
    border: 1px solid var(--dash-border);
    border-radius: 16px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
}
.dash-panel-title {
    font-family: 'Exo 2', sans-serif;
    font-size: .95rem;
    font-weight: 700;
    color: var(--dash-text);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: .6rem;
}
.dash-panel-title .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dot-blue   { background: var(--dash-accent); box-shadow: 0 0 8px var(--dash-accent); }
.dot-green  { background: #4ade80;            box-shadow: 0 0 8px rgba(74,222,128,.6); }
.dot-yellow { background: var(--dash-yellow); box-shadow: 0 0 8px rgba(251,191,36,.6); }

/* ── Tabla reciente ── */
.dash-mini-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.dash-mini-table th {
    text-align: left;
    font-size: .67rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--dash-muted);
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--dash-border);
}
.dash-mini-table td {
    padding: .75rem 0;
    border-bottom: 1px solid rgba(74, 144, 217, .07);
    color: var(--dash-text);
    vertical-align: middle;
}
.dash-mini-table tr:last-child td { border-bottom: none; }

/* ── Pills ── */
.mini-pill {
    display: inline-block;
    padding: .2rem .65rem;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .04em;
}
.pill-pending    { background: rgba(251,191,36,.12); border: 1px solid rgba(251,191,36,.3); color: #fbbf24; }
.pill-approved   { background: rgba(34,197,94,.12);  border: 1px solid rgba(34,197,94,.3);  color: #4ade80; }
.pill-rejected   { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #f87171; }
.pill-nuevo      { background: rgba(0,212,255,.12);  border: 1px solid rgba(0,212,255,.3);  color: #00d4ff; }
.pill-disponible { background: rgba(34,197,94,.12);  border: 1px solid rgba(34,197,94,.3);  color: #4ade80; }
.pill-agotado    { background: rgba(239,68,68,.12);  border: 1px solid rgba(239,68,68,.3);  color: #f87171; }

/* ── Accesos rápidos ── */
.dash-shortcuts {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .85rem;
}
@media (max-width: 480px) { .dash-shortcuts { grid-template-columns: 1fr 1fr; } }

.shortcut-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .6rem;
    padding: 1.1rem .75rem;
    border-radius: 12px;
    background: rgba(30, 77, 153, .12);
    border: 1px solid rgba(74, 144, 217, .18);
    text-decoration: none;
    transition: all .22s;
    cursor: pointer;
}
.shortcut-btn:hover {
    background: rgba(0, 212, 255, .1);
    border-color: rgba(0, 212, 255, .35);
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0, 20, 70, .4);
}
.shortcut-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
}
.shortcut-label {
    font-size: .74rem;
    font-weight: 700;
    color: var(--dash-text);
    text-align: center;
    line-height: 1.3;
}

/* ── Línea decorativa top de panel ── */
.dash-panel::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: var(--panel-line, transparent);
    border-radius: 16px 16px 0 0;
}
.dash-panel.accent-blue::after   { --panel-line: linear-gradient(90deg, transparent, rgba(0,212,255,.5), transparent); }
.dash-panel.accent-green::after  { --panel-line: linear-gradient(90deg, transparent, rgba(74,222,128,.5), transparent); }
.dash-panel.accent-yellow::after { --panel-line: linear-gradient(90deg, transparent, rgba(251,191,36,.5), transparent); }

/* ── Barras de actividad ── */
.activity-bar { display: flex; align-items: center; gap: .85rem; margin-bottom: .85rem; }
.activity-bar:last-child { margin-bottom: 0; }
.activity-bar-label { font-size: .78rem; color: var(--dash-muted); width: 80px; flex-shrink: 0; }
.activity-bar-track { flex: 1; height: 6px; background: rgba(74, 144, 217, .12); border-radius: 99px; overflow: hidden; }
.activity-bar-fill { height: 100%; border-radius: 99px; background: var(--bar-color, var(--dash-accent)); transition: width .6s cubic-bezier(.4,0,.2,1); }
.activity-bar-val { font-size: .78rem; font-weight: 700; color: var(--dash-text); width: 32px; text-align: right; flex-shrink: 0; }

/* ── Footer info ── */
.dash-info-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--dash-border);
    flex-wrap: wrap;
    gap: .75rem;
}
.dash-info-tag { font-size: .72rem; color: var(--dash-muted); display: flex; align-items: center; gap: .4rem; }
.dash-info-tag .dot-live {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #4ade80;
    box-shadow: 0 0 6px #4ade80;
    animation: pulse-live 2s infinite;
}
@keyframes pulse-live {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(.8); }
}
</style>

{{-- ── ENCABEZADO ─────────────────────────────── --}}
<div class="dash-header">
    <div>
        <div class="dash-greeting">👋 Bienvenido de vuelta</div>
        <h1 class="dash-title">Panel <span>Admin</span></h1>
        <div class="dash-date" id="dash-date">Cargando fecha…</div>
    </div>
    <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
        <a href="{{ route('admin.productos.create') }}" class="adm-btn adm-btn-ghost" style="font-size:.82rem;">
            + Nuevo producto
        </a>
        <a href="{{ url('/tienda') }}" class="adm-btn" style="font-size:.82rem;display:inline-flex;align-items:center;gap:.4rem;background:var(--dash-accent);color:#07111e;">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
            Ver tienda
        </a>
    </div>
</div>

{{-- ── KPIs ─────────────────────────────────── --}}
<div class="dash-kpis">

    <div class="kpi-card blue">
        <div class="kpi-icon blue">
            <svg viewBox="0 0 24 24" fill="rgba(0,212,255,0.9)"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.34C18 2.01 15.99 0 13.66 0c-1.2 0-2.28.53-3.05 1.37L10 2.09 9.39 1.37C8.62.53 7.54 0 6.34 0 4.01 0 2 2.01 2 4.66c0 .46.09.9.18 1.34H0v2h20v-2zM4 4.66C4 3.11 5.11 2 6.34 2c.6 0 1.16.24 1.57.64L10 4.74l2.09-2.1C12.5 2.24 13.06 2 13.66 2 14.89 2 16 3.11 16 4.66c0 0 0 0 0 .01L4 4.66zM3 9v11c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V9H3zm4.5 9H5v-7h2.5v7zm3.5 0H8.5v-7H11v7zm3.5 0H12v-7h2.5v7zm3.5 0H16v-7h2v7z"/></svg>
        </div>
        <div class="kpi-label">Total Productos</div>
        <div class="kpi-value blue">{{ $totalProductos ?? 4 }}</div>
        <div class="kpi-sub">en el catálogo</div>
    </div>

    <div class="kpi-card green">
        <div class="kpi-icon green">
            <svg viewBox="0 0 24 24" fill="rgba(74,222,128,0.9)"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
        </div>
        <div class="kpi-label">Activos en Tienda</div>
        <div class="kpi-value green">{{ $productosActivos ?? 4 }}</div>
        <div class="kpi-sub">disponibles al público</div>
    </div>

    <div class="kpi-card red">
        <div class="kpi-icon red">
            <svg viewBox="0 0 24 24" fill="rgba(248,113,113,0.9)"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        </div>
        <div class="kpi-label">Agotados</div>
        <div class="kpi-value red">{{ $productosAgotados ?? 1 }}</div>
        <div class="kpi-sub">sin stock disponible</div>
    </div>

    <div class="kpi-card yellow">
        <div class="kpi-icon yellow">
            <svg viewBox="0 0 24 24" fill="rgba(251,191,36,0.9)"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
        </div>
        <div class="kpi-label">Valor Catálogo</div>
        <div class="kpi-value yellow" style="font-size:1.5rem;">S/ {{ number_format($valorCatalogo ?? 854, 0) }}</div>
        <div class="kpi-sub">precio total de productos</div>
    </div>

    <div class="kpi-card blue">
        <div class="kpi-icon blue">
            <svg viewBox="0 0 24 24" fill="rgba(0,212,255,0.9)">
                <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 
                2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 
                2S15.9 22 17 22s2-.9 
                2-2-.9-2-2-2zM7.17 14h9.45c.75 
                0 1.41-.41 1.75-1.03L21.7 
                6.3a1 1 0 0 0-.87-1.5H5.21L4.27 
                2H1v2h2l3.6 7.59-1.35 
                2.45C4.52 15.37 5.48 17 
                7 17h12v-2H7.42c-.14 
                0-.25-.11-.25-.25l.03-.12L7.17 14z"/>
            </svg>
        </div>
        <div class="kpi-label">Pedidos</div>
        <div class="kpi-value blue">{{ $totalPedidos ?? 0 }}</div>
        <div class="kpi-sub">órdenes registradas</div>
    </div>

    <div class="kpi-card yellow">
        <div class="kpi-icon yellow" style="--kpi-icon-bg:rgba(251,191,36,.12);">
            <svg viewBox="0 0 24 24" fill="rgba(251,191,36,0.9)">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
            </svg>
        </div>
        <div class="kpi-label">Pagos Pendientes</div>
        <div class="kpi-value yellow">{{ $pagosPendientes ?? 0 }}</div>
        <div class="kpi-sub">esperando revisión</div>
    </div>
</div>

{{-- ── FILA PRINCIPAL ──────────────────────── --}}
<div class="dash-row">

    {{-- Últimos productos --}}
    <div class="dash-panel accent-blue">
        <div class="dash-panel-title">
            <span class="dot dot-blue"></span>
            Últimos Productos
        </div>
        <table class="dash-mini-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cat.</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimosProductos ?? [] as $p)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.82rem;color:var(--dash-text);">{{ $p->nombre }}</div>
                        <div style="font-size:.7rem;color:var(--dash-muted);">{{ $p->slug }}</div>
                    </td>
                    <td>
                        <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;
                              background:rgba(0,212,255,.1);color:#00d4ff;
                              border:1px solid rgba(0,212,255,.2);
                              padding:.15rem .55rem;border-radius:4px;">
                            {{ $p->categoria }}
                        </span>
                    </td>
                    <td style="font-weight:700;color:var(--dash-text);">S/ {{ number_format($p->precio,2) }}</td>
                    <td><span class="mini-pill pill-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span></td>
                </tr>
                @empty
                <tr>
                    <td><div style="font-weight:600;font-size:.82rem;">Kit ESP32 + Sensores IoT</div><div style="font-size:.7rem;color:var(--dash-muted);">esp32-kit</div></td>
                    <td><span style="font-size:.7rem;font-weight:700;text-transform:uppercase;background:rgba(0,212,255,.1);color:#00d4ff;border:1px solid rgba(0,212,255,.2);padding:.15rem .55rem;border-radius:4px;">IOT</span></td>
                    <td style="font-weight:700;">S/ 85.00</td>
                    <td><span class="mini-pill pill-nuevo">Nuevo</span></td>
                </tr>
                <tr>
                    <td><div style="font-weight:600;font-size:.82rem;">Dashboard IoT Web (Licencia)</div><div style="font-size:.7rem;color:var(--dash-muted);">dashboard-iot</div></td>
                    <td><span style="font-size:.7rem;font-weight:700;text-transform:uppercase;background:rgba(0,212,255,.1);color:#00d4ff;border:1px solid rgba(0,212,255,.2);padding:.15rem .55rem;border-radius:4px;">SOFTWARE</span></td>
                    <td style="font-weight:700;">S/ 299.00</td>
                    <td><span class="mini-pill pill-disponible">Disponible</span></td>
                </tr>
                <tr>
                    <td><div style="font-weight:600;font-size:.82rem;">Módulo Raspberry Pi 4 + Case</div><div style="font-size:.7rem;color:var(--dash-muted);">raspberry-pi4</div></td>
                    <td><span style="font-size:.7rem;font-weight:700;text-transform:uppercase;background:rgba(0,212,255,.1);color:#00d4ff;border:1px solid rgba(0,212,255,.2);padding:.15rem .55rem;border-radius:4px;">IOT</span></td>
                    <td style="font-weight:700;">S/ 320.00</td>
                    <td><span class="mini-pill pill-agotado">Agotado</span></td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="dash-info-row">
            <div class="dash-info-tag">
                <span class="dot-live"></span>
                Datos en tiempo real
            </div>
            <a href="{{ route('admin.productos.index') }}" style="font-size:.75rem;color:var(--dash-accent);text-decoration:none;font-weight:600;">
                Ver todos →
            </a>
        </div>
    </div>

    {{-- Solicitudes recientes --}}
    <div class="dash-panel accent-yellow">
        <div class="dash-panel-title">
            <span class="dot dot-yellow"></span>
            Solicitudes Recientes
        </div>
        <table class="dash-mini-table">
            <thead>
                <tr>
                    <th>Solicitante</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimasSolicitudes ?? [] as $s)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:.82rem;">{{ $s->nombre }}</div>
                        <div style="font-size:.7rem;color:var(--dash-muted);">{{ $s->email }}</div>
                    </td>
                    <td>
                        <span class="mini-pill
                            @if($s->estado === 'pendiente') pill-pending
                            @elseif($s->estado === 'aprobada') pill-approved
                            @else pill-rejected @endif">
                            {{ ucfirst($s->estado) }}
                        </span>
                    </td>
                    <td style="font-size:.75rem;color:var(--dash-muted);white-space:nowrap;">
                        {{ $s->created_at->format('d M') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center;color:var(--dash-muted);font-size:.8rem;padding:1.5rem 0;">
                        Sin solicitudes recientes
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="dash-info-row">
            @php
                $pendientes = isset($ultimasSolicitudes) ? $ultimasSolicitudes->where('estado','pendiente')->count() : 0;
            @endphp
            @if($pendientes > 0)
                <div class="dash-info-tag" style="color:#fbbf24;">
                    ⚠ {{ $pendientes }} pendiente{{ $pendientes > 1 ? 's' : '' }} por revisar
                </div>
            @else
                <div class="dash-info-tag">Todo al día ✓</div>
            @endif
            <a href="{{ route('admin.colaboradores.solicitudes.index') }}" style="font-size:.75rem;color:var(--dash-accent);text-decoration:none;font-weight:600;">
                Ver todas →
            </a>
        </div>
    </div>

</div>

{{-- ── FILA SECUNDARIA ─────────────────────── --}}
<div class="dash-row">

    {{-- Distribución por categoría --}}
    <div class="dash-panel accent-green">
        <div class="dash-panel-title">
            <span class="dot dot-green"></span>
            Productos por Categoría
        </div>

        @php
            $cats = [
                ['label' => 'IoT / Electrónica', 'value' => $catIot  ?? 2, 'total' => $totalProductos ?? 4, 'color' => '#00d4ff'],
                ['label' => 'Software',           'value' => $catSoft ?? 1, 'total' => $totalProductos ?? 4, 'color' => '#4ade80'],
                ['label' => 'Kits',               'value' => $catKits ?? 1, 'total' => $totalProductos ?? 4, 'color' => '#fbbf24'],
            ];
        @endphp

        @foreach($cats as $cat)
        @php $pct = $cat['total'] > 0 ? round(($cat['value']/$cat['total'])*100) : 0; @endphp
        <div class="activity-bar">
            <div class="activity-bar-label">{{ $cat['label'] }}</div>
            <div class="activity-bar-track">
                <div class="activity-bar-fill" style="width:{{ $pct }}%;background:{{ $cat['color'] }};box-shadow:0 0 8px {{ $cat['color'] }}50;"></div>
            </div>
            <div class="activity-bar-val">{{ $cat['value'] }}</div>
        </div>
        @endforeach

        <div style="margin-top:1.25rem;display:flex;gap:.75rem;flex-wrap:wrap;">
            <div style="font-size:.72rem;color:var(--dash-muted);">
                Total: <strong style="color:var(--dash-text);">{{ $totalProductos ?? 4 }} productos</strong>
            </div>
            <div style="font-size:.72rem;color:var(--dash-muted);">
                Visibles: <strong style="color:#4ade80;">{{ $productosActivos ?? 4 }}</strong>
            </div>
            <div style="font-size:.72rem;color:var(--dash-muted);">
                Agotados: <strong style="color:#f87171;">{{ $productosAgotados ?? 1 }}</strong>
            </div>
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="dash-panel">
        <div class="dash-panel-title">
            <span class="dot dot-blue"></span>
            Accesos Rápidos
        </div>
        <div class="dash-shortcuts">

            <a href="{{ route('admin.productos.create') }}" class="shortcut-btn">
                <div class="shortcut-icon" style="background:rgba(0,212,255,.12);border:1px solid rgba(0,212,255,.25);">➕</div>
                <div class="shortcut-label">Nuevo Producto</div>
            </a>

            <a href="{{ route('admin.productos.index') }}" class="shortcut-btn">
                <div class="shortcut-icon" style="background:rgba(74,144,217,.12);border:1px solid rgba(74,144,217,.25);">📦</div>
                <div class="shortcut-label">Productos</div>
            </a>

            <a href="{{ route('admin.colaboradores.solicitudes.index') }}" class="shortcut-btn">
                <div class="shortcut-icon" style="background:rgba(251,191,36,.12);border:1px solid rgba(251,191,36,.25);">📋</div>
                <div class="shortcut-label">Solicitudes</div>
            </a>

            <a href="{{ route('admin.colaboradores.index') }}" class="shortcut-btn">
                <div class="shortcut-icon" style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.25);">👥</div>
                <div class="shortcut-label">Colaboradores</div>
            </a>

            <a href="{{ url('/tienda') }}" class="shortcut-btn" target="_blank">
                <div class="shortcut-icon" style="background:rgba(139,92,246,.12);border:1px solid rgba(139,92,246,.25);">🛒</div>
                <div class="shortcut-label">Ver Tienda</div>
            </a>

            <a href="{{ url('/contacto') }}" class="shortcut-btn" target="_blank">
                <div class="shortcut-icon" style="background:rgba(236,72,153,.12);border:1px solid rgba(236,72,153,.25);">✉️</div>
                <div class="shortcut-label">Contacto</div>
            </a>

        </div>
    </div>

</div>

<script>
const d = new Date();
const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
document.getElementById('dash-date').textContent =
    d.toLocaleDateString('es-PE', opts).replace(/^\w/, c => c.toUpperCase());
</script>

@endsection