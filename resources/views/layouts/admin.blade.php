<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #07111e;
            --surface:   #0d1f33;
            --card:      #0f2540;
            --border:    rgba(0,212,255,.13);
            --border-md: rgba(0,212,255,.22);
            --accent:    #00d4ff;
            --accent-dim:#0099bb;
            --text:      #e2f0fb;
            --muted:     #7a9ab8;
            --sidebar-w: 260px;
        }

        html, body { height: 100%; }

        body {
            font-family: 'Exo 2', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── SIDEBAR (Bootstrap 5 Offcanvas Integration) ──────────────── */
        .adm-sidebar {
            width: var(--sidebar-w);
            background: var(--surface) !important;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .adm-logo {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid var(--border);
        }

        .adm-logo-text {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--accent);
            letter-spacing: .03em;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .adm-logo-text::before {
            content: '';
            display: inline-block;
            width: 8px; height: 8px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--accent);
        }

        .adm-logo-sub {
            font-size: .72rem;
            color: var(--muted);
            margin-top: .2rem;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .adm-user {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .adm-avatar {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: rgba(0,212,255,.15);
            border: 1px solid var(--border-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 700;
            color: var(--accent);
            flex-shrink: 0;
        }

        .adm-user-name {
            font-size: .85rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.2;
        }

        .adm-user-role {
            font-size: .72rem;
            color: var(--muted);
        }

        .adm-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .adm-nav-group {
            padding: .75rem 1.25rem .35rem;
            font-size: .68rem;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .adm-nav-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .6rem 1.25rem;
            font-size: .84rem;
            color: var(--muted);
            text-decoration: none;
            border-left: 2px solid transparent;
            transition: all .15s;
        }

        .adm-nav-link:hover {
            color: var(--text);
            background: rgba(0,212,255,.05);
        }

        .adm-nav-link.active {
            color: var(--accent) !important;
            border-left-color: var(--accent);
            background: rgba(0,212,255,.08);
            font-weight: 600;
        }

        .adm-nav-icon { font-size: 16px; flex-shrink: 0; }

        .adm-badge {
            margin-left: auto;
            background: rgba(0,212,255,.15);
            color: var(--accent);
            font-size: .68rem;
            font-weight: 700;
            padding: .15rem .45rem;
            border-radius: 20px;
        }

        .adm-sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
        }

        .adm-logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .6rem 1.25rem;
            font-size: .84rem;
            color: var(--muted);
            transition: all .15s;
            border-radius: 8px;
            text-align: left;
        }

        .adm-logout-btn:hover {
            color: #f43f5e;
            background: rgba(244, 63, 94, 0.08);
        }

        /* ── NAVBAR / TOPBAR ───────────────────── */
        .adm-topbar {
            position: fixed;
            top: 0; right: 0;
            height: 56px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem;
            z-index: 90;
            gap: 1rem;
        }

        .adm-sidebar-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--accent);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: all 0.2s;
        }
        .adm-sidebar-toggle:hover {
            background: rgba(0, 212, 255, 0.1);
        }
        .adm-sidebar-toggle svg {
            width: 20px; height: 20px;
        }

        .adm-breadcrumb {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            color: var(--muted);
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            margin-bottom: 0;
            margin-right: auto;
        }

        .adm-breadcrumb a {
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
        }
        .adm-breadcrumb a:hover { color: var(--accent); }

        .adm-breadcrumb-sep { opacity: .4; }

        .adm-breadcrumb-current {
            color: var(--text);
            font-weight: 600;
        }

        /* ── MAIN CONTENT AREA ─────────────────── */
        .adm-main {
            padding-top: 56px;
            flex: 1;
            min-height: 100vh;
            width: 100%;
        }

        .adm-content {
            padding: 2rem 1.25rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .adm-page-header {
            margin-bottom: 2rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .adm-page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
        }

        .adm-page-sub {
            font-size: .85rem;
            color: var(--muted);
            margin-top: .35rem;
        }

        /* ── RESPONSIVE TABLE COMPATIBILITY (NO HORIZONTAL SCROLL) ── */
        .adm-table-container, 
        .table-wrap, 
        .bg-white.rounded-xl.border {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            max-width: 100% !important;
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
        }
        .adm-table-container table, 
        .table-wrap table, 
        .bg-white.rounded-xl.border table, 
        table.adm-table,
        table.w-full {
            min-width: 650px !important;
            width: 100% !important;
        }

        /* ── FORM RESPONSIVE AUTO-FIT ─────────── */
        .adm-form-card, 
        form.bg-white.rounded-xl.border {
            max-width: 100% !important;
            box-sizing: border-box !important;
            background: var(--card) !important;
            border-color: var(--border) !important;
        }
        .adm-form-row, 
        .grid-cols-2 {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)) !important;
            gap: 1.25rem !important;
        }

        /* ── TAILWIND OVERRIDES FOR STYLING ───── */
        .adm-content .bg-white { background-color: var(--card) !important; }
        .adm-content .bg-gray-50 { background-color: rgba(0, 212, 255, 0.02) !important; }
        .adm-content .bg-gray-100 { background-color: var(--bg) !important; }
        .adm-content .text-gray-900, .adm-content .text-gray-800 { color: var(--text) !important; }
        .adm-content .text-gray-600, .adm-content .text-gray-500, .adm-content .text-gray-400 { color: var(--muted) !important; }
        .adm-content .border, .adm-content .border-b, .adm-content .border-t, .adm-content .divide-y > * { border-color: var(--border) !important; }
        .adm-content .divide-gray-50 > * { border-color: rgba(0, 212, 255, 0.05) !important; }
        .adm-content .hover\:bg-gray-50:hover { background-color: rgba(0, 212, 255, 0.02) !important; }

        /* General layout overrides for 2 columns (stats & info) */
        .adm-content .grid-cols-3 {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) !important;
            gap: 1rem !important;
        }

        /* ── BOOTSTRAP OFFCANVAS CUSTOMIZATION ── */
        .offcanvas-backdrop {
            background-color: rgba(4, 9, 17, 0.85) !important;
            backdrop-filter: blur(4px);
        }

        /* ── RESPONSIVE RESPONSIVENESS BREAKPOINTS ── */
        @media (min-width: 992px) {
            .adm-sidebar {
                position: fixed !important;
                top: 0; left: 0; bottom: 0;
                width: var(--sidebar-w) !important;
                background: var(--surface) !important;
                border-right: 1px solid var(--border) !important;
                display: flex !important;
                flex-direction: column !important;
                z-index: 100 !important;
                transform: none !important;
                visibility: visible !important;
            }
            .adm-main {
                margin-left: var(--sidebar-w) !important;
                width: calc(100% - var(--sidebar-w)) !important;
            }
            .adm-topbar {
                left: var(--sidebar-w) !important;
            }
            .adm-sidebar-toggle {
                display: none !important;
            }
        }

        @media (max-width: 991.98px) {
            .adm-sidebar {
                width: var(--sidebar-w) !important;
                box-shadow: 0 0 35px rgba(0, 0, 0, 0.8) !important;
            }
            .adm-topbar {
                left: 0 !important;
            }
            .adm-sidebar-toggle {
                display: flex !important;
            }
            .adm-main {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        @media (max-width: 480px) {
            .adm-breadcrumb {
                font-size: 0.75rem;
            }
            .adm-page-title {
                font-size: 1.3rem;
            }
            .adm-content {
                padding: 1.25rem 0.75rem;
            }
        }

        /* ── UTILIDADES ADMIN (alertas, botones, tablas, badges, paginación) ── */
        .adm-alert {
            display: flex; align-items: center; gap: .5rem;
            padding: .9rem 1.1rem; border-radius: 12px;
            font-size: .85rem; font-weight: 500; margin-bottom: 1.25rem;
        }
        .adm-alert-success { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3); color: #34d399; }
        .adm-alert-error   { background: rgba(244,63,94,.1);  border: 1px solid rgba(244,63,94,.3);  color: #fb7185; }
        .adm-alert svg { width: 17px; height: 17px; flex-shrink: 0; }

        .adm-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .adm-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem 1.3rem;
        }

        .adm-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .45rem;
            padding: .55rem 1.1rem; border-radius: 10px;
            font-size: .83rem; font-weight: 600; cursor: pointer;
            border: 1px solid transparent; text-decoration: none;
            transition: all .18s; font-family: inherit; white-space: nowrap;
        }
        .adm-btn-primary { background: linear-gradient(135deg, var(--ice-500), var(--ice-400)); color: #fff; border-color: rgba(74,144,217,.4); }
        .adm-btn-primary:hover { background: linear-gradient(135deg, var(--ice-400), var(--glow)); box-shadow: 0 6px 20px rgba(59,130,246,.4); }
        .adm-btn-ghost { background: rgba(37,99,199,.15); color: var(--ice-200); border: 1px solid rgba(74,144,217,.2); }
        .adm-btn-ghost:hover { background: rgba(37,99,199,.3); color: #fff; }
        .adm-btn-danger { background: rgba(244,63,94,.12); color: #f43f5e; border: 1px solid rgba(244,63,94,.3); }
        .adm-btn-danger:hover { background: rgba(244,63,94,.25); }
        .adm-btn-success { background: rgba(16,185,129,.12); color: #10b981; border: 1px solid rgba(16,185,129,.3); }
        .adm-btn-success:hover { background: rgba(16,185,129,.25); }

        .adm-input, .adm-select {
            background: rgba(7,26,46,.6);
            border: 1px solid var(--border-md);
            border-radius: 10px;
            padding: .6rem .95rem;
            color: var(--text);
            font-size: .85rem;
            outline: none;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
        }
        .adm-input:focus, .adm-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(0,212,255,.12); }

        .adm-table-container {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow-x: auto;
        }
        table.adm-table { width: 100%; border-collapse: collapse; min-width: 680px; }
        table.adm-table th {
            text-align: left; padding: .85rem 1rem;
            font-size: .7rem; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: .07em;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        table.adm-table td {
            padding: .8rem 1rem; font-size: .83rem;
            color: var(--muted); border-bottom: 1px solid rgba(0,212,255,.06);
            vertical-align: middle;
        }
        table.adm-table tbody tr:hover { background: rgba(0,212,255,.03); }

        .adm-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: .3rem .75rem; border-radius: 20px;
            font-size: .7rem; font-weight: 600; white-space: nowrap;
        }
        .adm-pill {
            display: inline-flex; align-items: center; gap: 4px;
            padding: .28rem .7rem; border-radius: 20px;
            font-size: .7rem; font-weight: 600; text-decoration: none; white-space: nowrap;
        }
        .adm-pill-pending { background: rgba(245,158,11,.1);  border: 1px solid rgba(245,158,11,.3);  color: #f59e0b; }
        .adm-pill-approved { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3); color: #10b981; }
        .adm-pill-rejected { background: rgba(244,63,94,.1);  border: 1px solid rgba(244,63,94,.3);  color: #f43f5e; }

        .adm-pagination {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: .75rem; padding: 1rem;
            border-top: 1px solid var(--border);
        }
        .adm-pagination-info { font-size: .78rem; color: var(--muted); }
        .adm-pagination nav { display: flex; }
        .adm-pagination .pagination { --bs-pagination-bg: var(--card); --bs-pagination-color: var(--muted); --bs-pagination-border-color: var(--border); --bs-pagination-hover-bg: rgba(0,212,255,.1); --bs-pagination-hover-color: var(--accent); --bs-pagination-active-bg: var(--accent-dim); --bs-pagination-active-border-color: var(--accent-dim); --bs-pagination-disabled-bg: var(--bg); --bs-pagination-disabled-color: var(--muted); margin: 0; }

        .adm-empty { text-align: center; padding: 56px 20px; }
        .adm-empty-icon { font-size: 2.8rem; margin-bottom: 14px; opacity: .7; }
        .adm-empty p { color: var(--muted); margin-bottom: 0; }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR (Official Bootstrap 5 Responsive Offcanvas) --}}
<aside class="adm-sidebar offcanvas-lg offcanvas-start border-0" id="admSidebar" tabindex="-1" aria-labelledby="admSidebarLabel">
    <div class="adm-logo d-flex justify-content-between align-items-center">
        <div>
            <div class="adm-logo-text">Panel Admin</div>
            <div class="adm-logo-sub">Tienda · Sistema</div>
        </div>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#admSidebar" aria-label="Cerrar"></button>
    </div>

    <div class="adm-user">
        <div class="adm-avatar">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
        </div>
        <div>
            <div class="adm-user-name">{{ auth()->user()->name ?? 'Administrador' }}</div>
            <div class="adm-user-role">Administrador</div>
        </div>
    </div>

    <nav class="adm-nav">
        <div class="adm-nav-group">General</div>

        <a href="{{ route('admin.dashboard') }}" class="adm-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="adm-nav-icon">⊞</span> Dashboard
        </a>

        <div class="adm-nav-group">Colaboradores</div>

        <a href="{{ route('admin.colaboradores.solicitudes.index') }}" class="adm-nav-link {{ request()->routeIs('admin.colaboradores.solicitudes*') ? 'active' : '' }}">
            <span class="adm-nav-icon">📋</span> Solicitudes
            @php $pendientes = \App\Models\SolicitudColaborador::where('estado','pendiente')->count() @endphp
            @if($pendientes > 0)
                <span class="adm-badge">{{ $pendientes }}</span>
            @endif
        </a>

        <a href="{{ route('admin.colaboradores.index') }}" class="adm-nav-link {{ request()->routeIs('admin.colaboradores.index') || request()->routeIs('admin.colaboradores.show') || request()->routeIs('admin.colaboradores.edit') || request()->routeIs('admin.colaboradores.create') ? 'active' : '' }}">
            <span class="adm-nav-icon">👥</span> Colaboradores 
        </a>

        <div class="adm-nav-group">Tienda</div>

        <a href="{{ route('admin.productos.index') }}" class="adm-nav-link {{ request()->routeIs('admin.productos*') ? 'active' : '' }}">
            <span class="adm-nav-icon">📦</span> Productos
        </a>

        <a href="{{ route('admin.pedidos.index') }}" class="adm-nav-link {{ request()->routeIs('admin.pedidos*') ? 'active' : '' }}">
            <span class="adm-nav-icon">🛒</span> Pedidos
        </a>

        <a href="{{ route('admin.pagos.index') }}" class="adm-nav-link {{ request()->routeIs('admin.pagos*') ? 'active' : '' }}">
            <span class="adm-nav-icon">💰</span> Pagos
            @php $pagosPendientes = \App\Models\Pago::where('estado_pago','pendiente')->count() @endphp
            @if($pagosPendientes > 0)
                <span class="adm-badge">{{ $pagosPendientes }}</span>
            @endif
        </a>

        <div class="adm-nav-group">Contenido</div>

        <a href="{{ route('admin.proyectos.index') }}" class="adm-nav-link {{ request()->routeIs('admin.proyectos*') ? 'active' : '' }}">
            <span class="adm-nav-icon">📁</span> Proyectos
        </a>

        <a href="{{ route('admin.inversiones.index') }}" class="adm-nav-link {{ request()->routeIs('admin.inversiones*') ? 'active' : '' }}">
            <span class="adm-nav-icon">📈</span> Inversiones
            @php $invPendientes = \App\Models\Inversion::where('estado','pendiente')->count() @endphp
            @if($invPendientes > 0)
                <span class="adm-badge">{{ $invPendientes }}</span>
            @endif
        </a>

        <a href="{{ route('admin.solicitudes-tarjeta.index') }}" class="adm-nav-link {{ request()->routeIs('admin.solicitudes-tarjeta*') ? 'active' : '' }}">
            <span class="adm-nav-icon">💳</span> Tarjeta MReyze
            @php $tarjetaPendientes = \App\Models\SolicitudTarjeta::where('estado','pendiente')->count() @endphp
            @if($tarjetaPendientes > 0)
                <span class="adm-badge">{{ $tarjetaPendientes }}</span>
            @endif
        </a>

        <a href="{{ route('admin.metodos-pago.index') }}" class="adm-nav-link {{ request()->routeIs('admin.metodos-pago*') ? 'active' : '' }}">
            <span class="adm-nav-icon">💳</span> Métodos de Pago
        </a>

        <div class="adm-nav-group">Contacto</div>

        <a href="{{ route('admin.contacto.index') }}" class="adm-nav-link {{ request()->routeIs('admin.contacto*') ? 'active' : '' }}">
            <span class="adm-nav-icon">📨</span> Mensajes de Contacto
            @php $contactoNuevos = \App\Models\MensajeContacto::where('estado','nuevo')->count() @endphp
            @if($contactoNuevos > 0)
                <span class="adm-badge">{{ $contactoNuevos }}</span>
            @endif
        </a>
    </nav>

    <div class="adm-sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="adm-logout-btn">
                <span>⎋</span> Cerrar sesión
            </button>
        </form>
    </div>
</aside>

{{-- TOPBAR (NAVBAR) --}}
<header class="adm-topbar">
    <button class="adm-sidebar-toggle" id="admSidebarToggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#admSidebar" aria-controls="admSidebar" aria-label="Abrir menú">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
        </svg>
    </button>
    
    <nav class="adm-breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Panel Admin</a>
        <span class="adm-breadcrumb-sep">/</span>
        @yield('breadcrumb', '<span class="adm-breadcrumb-current">Inicio</span>')
    </nav>

    <!-- Usuario en Navbar -->
    <div class="d-flex align-items-center gap-2">
        <div class="adm-avatar" style="width: 32px; height: 32px; font-size: 0.75rem;">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
        </div>
        <span class="d-none d-sm-inline" style="font-size: 0.8rem; font-weight: 600; color: var(--text);">
            {{ auth()->user()->name ?? 'Administrador' }}
        </span>
    </div>
</header>

{{-- MAIN --}}
<main class="adm-main">
    <div class="adm-content">
        @yield('content')
    </div>
</main>

<!-- Bootstrap 5 Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarEl = document.getElementById('admSidebar');
    if (!sidebarEl) return;
    
    let bsOffcanvas = null;
    if (window.bootstrap && window.bootstrap.Offcanvas) {
        bsOffcanvas = window.bootstrap.Offcanvas.getOrCreateInstance(sidebarEl);
    }
    
    // 1. Cerrar automáticamente al seleccionar una opción del menú
    const links = sidebarEl.querySelectorAll('.adm-nav-link, .adm-logout-btn, button[type="submit"]');
    links.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992 && bsOffcanvas) {
                bsOffcanvas.hide();
            }
        });
    });
    
    // 2. Cerrar al cambiar el tamaño de la pantalla
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992 && bsOffcanvas) {
            bsOffcanvas.hide();
            // Limpieza de restos del backdrop de Bootstrap en transiciones rápidas
            const backdrop = document.querySelector('.offcanvas-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    });
});
</script>
@stack('scripts')
</body>
</html>