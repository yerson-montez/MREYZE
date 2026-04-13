<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0f1117;
            --bg2: #161b27;
            --bg3: #1e2535;
            --border: rgba(255,255,255,0.08);
            --accent: #00d4ff;
            --accent2: #14b8a6;
            --text: #e2e8f0;
            --text2: #94a3b8;
            --red: #ef4444;
            --green: #22c55e;
            --yellow: #f59e0b;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 240px;
            background: var(--bg2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: .5px;
        }

        .sidebar-logo span { color: var(--text2); font-weight: 400; font-size:.8rem; display:block; margin-top:2px; }

        .sidebar-nav { padding: 1rem 0; flex: 1; }

        .nav-label {
            font-size: .65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text2);
            padding: .75rem 1.25rem .25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1.25rem;
            color: var(--text2);
            text-decoration: none;
            font-size: .9rem;
            border-left: 3px solid transparent;
            transition: all .15s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text);
            background: rgba(0,212,255,0.06);
            border-left-color: var(--accent);
        }

        .nav-link .icon { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            font-size: .8rem;
            color: var(--text2);
        }

        .sidebar-footer a {
            color: var(--red);
            text-decoration: none;
            font-weight: 600;
        }

        /* ── MAIN ── */
        .main {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            padding: .9rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .85rem;
        }

        .topbar-title { font-weight: 600; color: var(--text); }
        .topbar-user { color: var(--text2); }
        .topbar-user strong { color: var(--accent); }

        .content { padding: 2rem; flex: 1; }

        /* ── COMPONENTES ── */
        .admin-page {}
        .admin-page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .admin-title { font-size: 1.5rem; font-weight: 700; color: var(--text); }
        .admin-sub { color: var(--text2); font-size: .85rem; margin-top: .25rem; }
        .back-link { color: var(--accent); text-decoration: none; font-size: .85rem; margin-top: .25rem; display: inline-block; }
        .back-link:hover { text-decoration: underline; }

        .admin-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Tabla */
        .admin-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .admin-table th {
            text-align: left;
            padding: .6rem 1rem;
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text2);
            border-bottom: 1px solid var(--border);
        }
        .admin-table td {
            padding: .75rem 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: rgba(255,255,255,.02); }

        .drag-handle { cursor: grab; color: var(--text2); font-size: 1.1rem; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: .2rem .6rem;
            border-radius: 99px;
            font-size: .72rem;
            font-weight: 600;
        }
        .badge-iot      { background: rgba(0,212,255,.15); color: #00d4ff; }
        .badge-software { background: rgba(20,184,166,.15); color: #14b8a6; }
        .badge-kits     { background: rgba(99,102,241,.15); color: #818cf8; }
        .badge-green    { background: rgba(34,197,94,.15);  color: #22c55e; }
        .badge-gray     { background: rgba(148,163,184,.1); color: #94a3b8; }
        .badge-estado-nuevo       { background: rgba(0,212,255,.15);  color: #00d4ff; }
        .badge-estado-disponible  { background: rgba(34,197,94,.15);  color: #22c55e; }
        .badge-estado-agotado     { background: rgba(239,68,68,.15);  color: #ef4444; }

        /* Botones */
        .btn-primary {
            background: var(--accent);
            color: #000;
            border: none;
            padding: .6rem 1.2rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: .875rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: opacity .15s;
        }
        .btn-primary:hover { opacity: .85; }

        .btn-secondary {
            background: var(--bg3);
            color: var(--text);
            border: 1px solid var(--border);
            padding: .6rem 1.2rem;
            border-radius: 8px;
            font-size: .875rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background .15s;
        }
        .btn-secondary:hover { background: var(--bg); }

        .btn-edit {
            background: rgba(20,184,166,.15);
            color: #14b8a6;
            border: 1px solid rgba(20,184,166,.3);
            padding: .35rem .8rem;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-edit:hover { background: rgba(20,184,166,.25); }

        .btn-delete {
            background: rgba(239,68,68,.12);
            color: #ef4444;
            border: 1px solid rgba(239,68,68,.3);
            padding: .35rem .8rem;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-delete:hover { background: rgba(239,68,68,.22); }

        .btn-sm-primary {
            background: var(--accent);
            color: #000;
            border: none;
            padding: .4rem .9rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: .8rem;
            cursor: pointer;
        }

        .acciones { display: flex; gap: .5rem; align-items: center; }

        /* Formularios */
        .form-group { margin-bottom: 1.1rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-group label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--text2);
            margin-bottom: .4rem;
        }
        .form-input {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: .6rem .9rem;
            color: var(--text);
            font-size: .875rem;
            transition: border-color .15s;
            outline: none;
        }
        .form-input:focus { border-color: var(--accent); }
        .form-input::placeholder { color: var(--text2); }
        select.form-input option { background: var(--bg3); }
        textarea.form-input { resize: vertical; }
        .form-error { color: #ef4444; font-size: .78rem; margin-top: .3rem; }
        .req { color: #ef4444; }
        .checkbox-label { display: flex; align-items: center; gap: .5rem; font-size: .875rem; cursor: pointer; }
        .form-actions { display: flex; gap: .75rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border); }

        /* Metodos pago */
        .metodos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px,1fr)); gap: 1rem; }
        .metodo-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.2rem;
            transition: border-color .2s;
        }
        .metodo-card.activo  { border-color: rgba(34,197,94,.3); }
        .metodo-card.inactivo { opacity: .6; }
        .metodo-header { display: flex; align-items: center; gap: .75rem; margin-bottom: 1rem; }
        .metodo-emoji { font-size: 1.4rem; }
        .metodo-nombre { flex: 1; font-weight: 600; }
        .toggle-btn {
            border: none;
            border-radius: 6px;
            padding: .3rem .7rem;
            font-size: .75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
        }
        .toggle-btn.on  { background: rgba(34,197,94,.15); color: #22c55e; }
        .toggle-btn.off { background: rgba(148,163,184,.1); color: #94a3b8; }
        .metodo-form label { font-size: .75rem; color: var(--text2); font-weight: 600; display: block; margin: .5rem 0 .2rem; }

        /* Alertas */
        .alert-success {
            background: rgba(34,197,94,.1);
            border: 1px solid rgba(34,197,94,.3);
            color: #22c55e;
            border-radius: 8px;
            padding: .8rem 1.2rem;
            margin-bottom: 1.2rem;
            font-size: .875rem;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        ⚡ MTech Admin
        <span>Panel de administración</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Tienda</div>
        <a href="{{ route('admin.productos.index') }}"
           class="nav-link {{ request()->routeIs('admin.productos*') ? 'active' : '' }}">
            <span class="icon">🛒</span> Productos
        </a>
        <a href="{{ route('admin.metodos-pago.index') }}"
           class="nav-link {{ request()->routeIs('admin.metodos-pago*') ? 'active' : '' }}">
            <span class="icon">💳</span> Métodos de pago
        </a>

        <div class="nav-label" style="margin-top:.5rem">Sitio</div>
        <a href="{{ url('/') }}" class="nav-link" target="_blank">
            <span class="icon">🌐</span> Ver sitio web
        </a>
        <a href="{{ route('tienda.index') }}" class="nav-link" target="_blank">
            <span class="icon">🏪</span> Ver tienda
        </a>
    </nav>

    <div class="sidebar-footer">
        Sesión: <strong style="color:var(--text)">{{ auth()->user()->name }}</strong><br>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Cerrar sesión
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
            @csrf
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="main">
    <div class="topbar">
        <span class="topbar-title">@yield('title', 'Panel Admin')</span>
        <span class="topbar-user">Hola, <strong>{{ auth()->user()->name }}</strong> 👋</span>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>

</body>
</html>