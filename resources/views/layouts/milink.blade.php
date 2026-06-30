<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Panel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-deep:     #060c1a;
            --bg-nav:      #06080f;
            --bg-card:     #0a1220;
            --border:      rgba(0, 212, 255, 0.10);
            --border-hot:  rgba(0, 212, 255, 0.30);
            --accent:      #00d4ff;
            --blue:        #2563c7;
            --blue-dark:   #185fa5;
            --green:       #1d9e75;
            --text-1:      #e2ecff;
            --text-2:      #7ab8f5;
            --text-3:      #2b5278;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            background: var(--bg-deep) !important;
            color: var(--text-1);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Grid de fondo ── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(0,212,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,255,0.03) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Glow superior ── */
        body::after {
            content: '';
            position: fixed;
            top: -120px; left: 50%;
            transform: translateX(-50%);
            width: 600px; height: 320px;
            background: radial-gradient(ellipse, rgba(0,212,255,0.055) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ══ BARRA DECORATIVA ══ */
        .accent-bar {
            height: 2px;
            background: linear-gradient(
                90deg,
                transparent 0%,
                var(--blue-dark) 20%,
                var(--accent) 50%,
                var(--blue-dark) 80%,
                transparent 100%
            );
            position: relative;
            z-index: 101;
        }

        /* ══ NAVBAR ══ */
        .navbar {
            position: sticky; top: 0;
            z-index: 100;
            background: rgba(6, 8, 15, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            height: 58px;
            display: flex;
            align-items: center;
            padding: 0 1.75rem;
            justify-content: space-between;
        }

        /* Brand */
        .nav-brand {
            display: flex; align-items: center; gap: 0.65rem;
            text-decoration: none;
        }

        .nav-logo {
            width: 34px; height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--blue-dark), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-family: 'Exo 2', sans-serif;
            font-size: 0.82rem; font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .nav-brand-text { line-height: 1; }

        .nav-name {
            font-family: 'Exo 2', sans-serif;
            font-size: 0.95rem; font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--text-1);
        }

        .nav-name em {
            color: var(--accent);
            font-style: normal;
        }

        .nav-sub {
            display: block;
            font-size: 0.56rem; font-weight: 300;
            color: var(--text-3);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-top: 1px;
        }

        /* Nav derecha */
        .nav-right {
            display: flex; align-items: center; gap: 1rem;
        }

        .nav-user {
            display: flex; align-items: center; gap: 0.6rem;
        }

        .nav-avatar {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(0,212,255,0.2), rgba(37,99,199,0.3));
            border: 1px solid var(--border-hot);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Exo 2', sans-serif;
            font-size: 0.68rem; font-weight: 700;
            color: var(--accent);
            flex-shrink: 0;
        }

        .nav-user-name {
            font-size: 0.72rem;
            color: var(--text-2);
            letter-spacing: 0.04em;
        }

        .nav-divider {
            width: 1px; height: 20px;
            background: var(--border);
        }

        .nav-link {
            font-family: 'Exo 2', sans-serif;
            font-size: 0.65rem; font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-3);
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-link:hover { color: var(--accent); }

        .nav-logout {
            font-family: 'Exo 2', sans-serif;
            font-size: 0.65rem; font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-3);
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.2s;
            padding: 0;
        }

        .nav-logout:hover { color: #f87171; }

        /* ══ CONTENIDO ══ */
        .page-content {
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 60px); /* 58px nav + 2px accent bar */
        }

        /* ══ FOOTER ══ */
        .footer {
            position: relative; z-index: 1;
            border-top: 1px solid var(--border);
            background: rgba(6, 8, 15, 0.6);
            padding: 1.1rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-text {
            font-family: 'Exo 2', sans-serif;
            font-size: 0.6rem; font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-3);
        }

        .footer-status {
            display: flex; align-items: center; gap: 0.5rem;
        }

        .footer-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 6px var(--green);
            animation: fpulse 2.5s ease-in-out infinite;
        }

        .footer-status-text {
            font-family: 'Exo 2', sans-serif;
            font-size: 0.6rem; font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-3);
        }

        @keyframes fpulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        @media (max-width: 600px) {
            .navbar { padding: 0 1rem; }
            .nav-user-name { display: none; }
            .footer { flex-direction: column; gap: 0.6rem; text-align: center; }
        }
    </style>
</head>
<body>

    {{-- ══ BARRA DECORATIVA ══ --}}
    <div class="accent-bar"></div>

    {{-- ══ NAVBAR ══ --}}
    <nav class="navbar">
        <a href="{{ url('/') }}" class="nav-brand">
            <div class="nav-logo">{{ strtoupper(substr(config('app.name', 'P'), 0, 2)) }}</div>
            <div class="nav-brand-text">
                <span class="nav-name">{{ config('app.name', 'Panel') }}<em>.</em></span>
                <span class="nav-sub">Panel de control</span>
            </div>
        </a>

        <div class="nav-right">
            @auth
                <div class="nav-user">
                    <div class="nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span class="nav-user-name">{{ Auth::user()->name }}</span>
                </div>

                <div class="nav-divider"></div>

                <a href="{{ url('/') }}" class="nav-link">Inicio</a>

                <div class="nav-divider"></div>

                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="nav-logout">Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Iniciar sesión</a>
            @endauth
        </div>
    </nav>

    {{-- ══ CONTENIDO ══ --}}
    <main class="page-content">
        @yield('content')
    </main>

    {{-- ══ FOOTER ══ --}}
    <footer class="footer">
        <span class="footer-text">{{ config('app.name') }} &mdash; {{ date('Y') }}</span>
        <div class="footer-status">
            <div class="footer-dot"></div>
            <span class="footer-status-text">Sistema activo</span>
        </div>
        <span class="footer-text">Panel</span>
    </footer>

</body>
</html>