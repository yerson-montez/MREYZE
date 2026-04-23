<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            --sidebar-w: 240px;
        }

        html, body { height: 100%; }

        body {
            font-family: 'Exo 2', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ─────────────────────────── */
        .adm-sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
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
            padding: .25rem 1rem .5rem;
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
            color: var(--accent);
            border-left-color: var(--accent);
            background: rgba(0,212,255,.08);
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

        .adm-logout {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .82rem;
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
        }

        .adm-logout:hover { color: #f43f5e; }

        /* ── TOPBAR ─────────────────────────── */
        .adm-topbar {
            position: fixed;
            top: 0; left: var(--sidebar-w); right: 0;
            height: 56px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.75rem;
            z-index: 90;
            gap: 1rem;
        }

        .adm-breadcrumb {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            color: var(--muted);
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

        /* ── MAIN ─────────────────────────── */
        .adm-main {
            margin-left: var(--sidebar-w);
            padding-top: 56px;
            flex: 1;
            min-height: 100vh;
        }

        .adm-content {
            padding: 2rem 1.75rem;
        }

        .adm-page-header {
            margin-bottom: 2rem;
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

        /* ── CARDS ─────────────────────────── */
        .adm-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
        }

        /* ── STAT CARD ─────────────────────── */
        .adm-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .adm-stat {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
        }

        .adm-stat-label {
            font-size: .72rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: .5rem;
        }

        .adm-stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }

        .adm-stat-value.accent { color: var(--accent); }

        /* ── TABLES ─────────────────────────── */
        .adm-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .85rem;
        }

        .adm-table th {
            text-align: left;
            font-size: .7rem;
            font-weight: 700;
            color: var(--muted);
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .75rem 1rem;
            border-bottom: 1px solid var(--border);
        }

        .adm-table td {
            padding: .85rem 1rem;
            border-bottom: 1px solid rgba(0,212,255,.06);
            color: var(--text);
            vertical-align: middle;
        }

        .adm-table tbody tr:hover td {
            background: rgba(0,212,255,.03);
        }

        /* ── BADGES ─────────────────────────── */
        .adm-pill {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            font-size: .72rem;
            font-weight: 600;
            padding: .25rem .7rem;
            border-radius: 20px;
        }

        .adm-pill-pending {
            background: rgba(250,180,0,.1);
            color: #f59e0b;
            border: 1px solid rgba(250,180,0,.2);
        }

        .adm-pill-approved {
            background: rgba(16,185,129,.1);
            color: #10b981;
            border: 1px solid rgba(16,185,129,.2);
        }

        .adm-pill-rejected {
            background: rgba(244,63,94,.1);
            color: #f43f5e;
            border: 1px solid rgba(244,63,94,.2);
        }

        /* ── BUTTONS ─────────────────────────── */
        .adm-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem 1.1rem;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            border: none;
        }

        .adm-btn-primary {
            background: var(--accent);
            color: #07111e;
        }

        .adm-btn-primary:hover {
            background: var(--accent-dim);
            transform: translateY(-1px);
        }

        .adm-btn-ghost {
            background: rgba(0,212,255,.07);
            color: var(--accent);
            border: 1px solid rgba(0,212,255,.2);
        }

        .adm-btn-ghost:hover {
            background: rgba(0,212,255,.13);
        }

        .adm-btn-danger {
            background: rgba(244,63,94,.1);
            color: #f43f5e;
            border: 1px solid rgba(244,63,94,.2);
        }

        .adm-btn-danger:hover {
            background: rgba(244,63,94,.18);
        }

        /* ── ALERTS ─────────────────────────── */
        .adm-alert {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .8rem 1rem;
            border-radius: 10px;
            font-size: .85rem;
            margin-bottom: 1.25rem;
        }

        .adm-alert-success {
            background: rgba(16,185,129,.1);
            border: 1px solid rgba(16,185,129,.25);
            color: #10b981;
        }

        .adm-alert-error {
            background: rgba(244,63,94,.1);
            border: 1px solid rgba(244,63,94,.25);
            color: #f43f5e;
        }

        /* ── EMPTY STATE ─────────────────────── */
        .adm-empty {
            text-align: center;
            padding: 3.5rem 2rem;
            color: var(--muted);
        }

        .adm-empty-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: .5;
        }

        .adm-empty-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: .4rem;
        }

        .adm-empty-sub {
            font-size: .85rem;
        }

        /* ── SCROLLBAR ─────────────────────── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(0,212,255,.2); border-radius: 4px; }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="adm-sidebar">
    <div class="adm-logo">
        <div class="adm-logo-text">Panel Admin</div>
        <div class="adm-logo-sub">Tienda · Sistema</div>
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

        <a href="{{ route('admin.productos.index') }}" class="adm-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
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

        <a href="{{ route('admin.colaboradores.solicitudes.index') }}" class="adm-nav-link {{ request()->routeIs('admin.colaboradores.index') ? 'active' : '' }}">
            <span class="adm-nav-icon">👥</span> Colaboradores 
        </a>

        <div class="adm-nav-group">Tienda</div>

        <a href="{{ route('admin.productos.index') }}" class="adm-nav-link {{ request()->routeIs('admin.productos*') ? 'active' : '' }}">
            <span class="adm-nav-icon">📦</span> Productos
        </a>

        <a href="{{ route('admin.pedidos.index') }}" class="adm-nav-link {{ request()->routeIs('admin.pedidos*') ? 'active' : '' }}">
            <span class="adm-nav-icon">📦</span> Pedidos
        </a>

       
    </nav>

    <div class="adm-sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="adm-logout" style="background:none;border:none;cursor:pointer;width:100%;font-family:inherit;">
                <span>⎋</span> Cerrar sesión
            </button>
        </form>
    </div>
</aside>

{{-- TOPBAR --}}
<header class="adm-topbar">
    <nav class="adm-breadcrumb">
        <a href="{{ route('admin.productos.index') }}">Panel Admin</a>
        <span class="adm-breadcrumb-sep">/</span>
        @yield('breadcrumb', '<span class="adm-breadcrumb-current">Inicio</span>')
    </nav>
</header>

{{-- MAIN --}}
<main class="adm-main">
    <div class="adm-content">
        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>