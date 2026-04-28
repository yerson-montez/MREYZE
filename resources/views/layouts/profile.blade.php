<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MreyZE') }} — {{ $title ?? 'Panel' }}</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:   #050d1a;
            --bg2:  #0a1628;
            --blue: #1e4d99;
            --cyan: #00d4ff;
            --ice1: #e0eeff;
            --ice2: #b3cff5;
            --ice3: #7aabec;
            --green: #4ade80;
            --panel: rgba(8,20,45,.92);
        }

        html, body {
            background: var(--bg);
            color: var(--ice1);
            font-family: 'Exo 2', sans-serif;
            min-height: 100vh;
        }

        /* ── LAYOUT ── */
        .cyber-layout { display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .cyber-sidebar {
            width: 220px;
            min-width: 220px;
            background: rgba(5,13,26,.98);
            border-right: 1px solid rgba(0,212,255,.12);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 50;
        }

        .sidebar-logo {
            padding: 1.2rem 1.2rem 1rem;
            border-bottom: 1px solid rgba(0,212,255,.1);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .logo-hex {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #1e4d99, #00d4ff);
            clip-path: polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Share Tech Mono', monospace;
            font-size: .65rem; color: #fff; font-weight: 700;
        }
        .logo-text  { font-family: 'Share Tech Mono', monospace; font-size: .8rem; color: var(--cyan); letter-spacing: .08em; }
        .logo-ver   { font-size: .6rem; color: var(--ice3); font-family: 'Share Tech Mono', monospace; }

        .sys-status {
            margin: 1rem 1rem .8rem;
            background: rgba(0,212,255,.05);
            border: 1px solid rgba(0,212,255,.15);
            border-radius: 8px; padding: .5rem .7rem;
            display: flex; align-items: center; gap: 7px;
            font-family: 'Share Tech Mono', monospace;
            font-size: .62rem; color: var(--green);
        }
        .s-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--green); box-shadow: 0 0 6px var(--green);
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        .nav-section {
            padding: .5rem 1rem .3rem;
            font-family: 'Share Tech Mono', monospace;
            font-size: .58rem; color: rgba(123,171,236,.45);
            letter-spacing: .12em; text-transform: uppercase;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: .55rem 1rem; margin: 1px .5rem;
            border-radius: 8px; cursor: pointer;
            font-size: .82rem; color: var(--ice2);
            transition: all .2s; border: 1px solid transparent;
            font-family: 'Exo 2', sans-serif; text-decoration: none;
        }
        .nav-item:hover { background: rgba(30,77,153,.25); border-color: rgba(0,212,255,.2); color: #fff; }
        .nav-item.active { background: rgba(30,77,153,.4); border-color: rgba(0,212,255,.35); color: var(--cyan); }
        .nav-ico { font-family: 'Share Tech Mono', monospace; font-size: .8rem; width: 18px; text-align: center; color: var(--cyan); }
        .nav-badge {
            margin-left: auto;
            background: rgba(0,212,255,.15); border: 1px solid rgba(0,212,255,.3);
            color: var(--cyan); font-size: .58rem; padding: 1px 6px;
            border-radius: 10px; font-family: 'Share Tech Mono', monospace;
        }
        .nav-badge.warn { background: rgba(249,115,22,.15); border-color: rgba(249,115,22,.3); color: #f97316; }

        .sidebar-bottom {
            margin-top: auto;
            border-top: 1px solid rgba(0,212,255,.1);
            padding: 1rem;
        }
        .user-chip {
            display: flex; align-items: center; gap: 9px;
            padding: .5rem; border-radius: 8px;
            border: 1px solid rgba(0,212,255,.12);
            transition: all .2s; cursor: pointer; text-decoration: none;
        }
        .user-chip:hover { background: rgba(30,77,153,.2); }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #1e4d99, #00d4ff);
            display: flex; align-items: center; justify-content: center;
            font-size: .72rem; font-weight: 700; color: #fff;
            font-family: 'Share Tech Mono', monospace;
        }
        .user-name  { font-size: .78rem; font-weight: 600; color: var(--ice1); }
        .user-role  { font-size: .62rem; color: var(--ice3); font-family: 'Share Tech Mono', monospace; }

        /* ── MAIN ── */
        .cyber-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

        .cyber-topbar {
            height: 52px;
            background: rgba(5,13,26,.95);
            border-bottom: 1px solid rgba(0,212,255,.1);
            display: flex; align-items: center;
            padding: 0 1.5rem; gap: 1rem;
            position: sticky; top: 0; z-index: 40;
            backdrop-filter: blur(10px);
        }
        .breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-family: 'Share Tech Mono', monospace;
            font-size: .7rem; color: var(--ice3);
        }
        .bread-sep    { color: rgba(0,212,255,.4); }
        .bread-active { color: var(--cyan); }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: .8rem; }
        .top-clock    { font-family: 'Share Tech Mono', monospace; font-size: .68rem; color: var(--ice3); }
        .top-btn {
            width: 30px; height: 30px; border-radius: 8px;
            background: rgba(0,212,255,.07); border: 1px solid rgba(0,212,255,.2);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s; position: relative;
            color: var(--ice2); font-size: .8rem; text-decoration: none;
        }
        .top-btn:hover { background: rgba(0,212,255,.15); color: #fff; }
        .notif-pip {
            position: absolute; top: 4px; right: 4px;
            width: 6px; height: 6px; border-radius: 50%;
            background: #f97316; box-shadow: 0 0 6px #f97316;
        }

        .cyber-content { flex: 1; padding: 1.5rem; overflow: auto; }

        /* ── SCAN LINE ── */
        .scan-line {
            position: fixed; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, rgba(0,212,255,.08), transparent);
            animation: scan 8s linear infinite; pointer-events: none; z-index: 9999;
        }
        @keyframes scan { from{transform:translateY(-100%)} to{transform:translateY(100vh)} }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .cyber-sidebar { display: none; }
        }
    </style>
</head>
<body>

<div class="scan-line"></div>

<div class="cyber-layout">

    {{-- ── SIDEBAR ── --}}
    <aside class="cyber-sidebar">

        <a href="{{ url('/') }}" class="sidebar-logo">
            <div class="logo-hex">
                <img src="{{ asset('img/LOGOMREYZE1.png') }}">
            </div>
            <div>
                <div class="logo-text">MREYZE</div>
                <div class="logo-ver">v2.4.1 — INDUSTRIA 4.0</div>
            </div>
        </a>

        <div class="sys-status">
            <span class="s-dot"></span>
            SYS_STATUS: ONLINE
        </div>

        <div class="nav-section">MAIN</div>

        <a href="{{ url('/') }}"
           class="nav-item {{ request()->is('inicio') ? 'active' : '' }}">
            <span class="nav-ico">◈</span> Inicio
        </a>

        <a href="{{ url('/profile') }}"
           class="nav-item {{ request()->is('profile') ? 'active' : '' }}">
            <span class="nav-ico">◉</span> Mi Perfil
        </a>

        {{-- Agrega aquí tus rutas personalizadas --}}
        {{-- Ejemplo:
        <a href="{{ url('/iot/nodes') }}"
           class="nav-item {{ request()->is('iot/nodes*') ? 'active' : '' }}">
            <span class="nav-ico">⬡</span> Nodos IoT
            <span class="nav-badge">14</span>
        </a>
        --}}

        <div class="nav-section">SISTEMA</div>

       

        <div class="sidebar-bottom">
            <a href="{{ url('/profile') }}" class="user-chip">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                </div>
                <div>
                    <div class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</div>
                    <div class="user-role">USER_SESSION</div>
                </div>
            </a>
        </div>

    </aside>

    {{-- ── MAIN ── --}}
    <div class="cyber-main">

        {{-- TOPBAR --}}
        <div class="cyber-topbar">
            <div class="breadcrumb">
                <span>~/</span>
                <span class="bread-sep">›</span>
                <span class="bread-active">{{ $breadcrumb ?? 'panel' }}</span>
            </div>
            <div class="topbar-right">
                <span class="top-clock" id="cyber-clock">--:--:--</span>
                <span class="top-btn">⚑</span>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="top-btn" title="Cerrar sesión">⏻</button>
                </form>
            </div>
        </div>

        {{-- CONTENIDO --}}
        <main class="cyber-content">
            @yield('content')
        </main>

    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const t = now.toTimeString().slice(0,8);
        const d = now.toLocaleDateString('es-PE',{day:'2-digit',month:'2-digit',year:'numeric'});
        const el = document.getElementById('cyber-clock');
        if (el) el.textContent = t + ' · ' + d;
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>

</body>
</html>