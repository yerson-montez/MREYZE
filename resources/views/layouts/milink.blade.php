<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Panel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&display=swap');

        :root {
            --bg-deep:    #050810;
            --bg-nav:     #07090f;
            --bg-card:    #0b0f1e;
            --border:     rgba(99,210,255,0.10);
            --border-hot: rgba(99,210,255,0.30);
            --cyan:       #63d2ff;
            --violet:     #a78bfa;
            --green:      #34d399;
            --text-1:     #e8f4ff;
            --text-2:     #7a9ab5;
            --text-3:     #3d5a72;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            background: var(--bg-deep) !important;
            color: var(--text-1);
            font-family: 'Syne', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── grid de fondo ── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(99,210,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,210,255,0.025) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── glow superior ── */
        body::after {
            content: '';
            position: fixed;
            top: -180px; left: 50%;
            transform: translateX(-50%);
            width: 800px; height: 400px;
            background: radial-gradient(ellipse, rgba(99,210,255,0.055) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ══════════ NAVBAR ══════════ */
        .navbar {
            position: sticky; top: 0;
            z-index: 100;
            background: rgba(7,9,15,0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
            height: 58px;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex; align-items: center; gap: 0.6rem;
            text-decoration: none;
        }

        .nav-logo {
            width: 32px; height: 32px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--cyan), var(--violet));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem; font-weight: 800;
            color: #050810;
            box-shadow: 0 0 14px rgba(99,210,255,0.35);
        }

        .nav-name {
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-1);
        }

        .nav-right {
            display: flex; align-items: center; gap: 1.2rem;
        }

        .nav-user {
            display: flex; align-items: center; gap: 0.6rem;
        }

        .nav-user-avatar {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(99,210,255,0.3), rgba(167,139,250,0.3));
            border: 1px solid var(--border-hot);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 700;
            color: var(--cyan);
        }

        .nav-user-name {
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            color: var(--text-2);
            letter-spacing: 0.05em;
        }

        .nav-divider {
            width: 1px; height: 20px;
            background: var(--border);
        }

        .nav-link {
            font-family: 'Space Mono', monospace;
            font-size: 0.62rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-3);
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-link:hover { color: var(--cyan); }

        .nav-logout {
            font-family: 'Space Mono', monospace;
            font-size: 0.62rem;
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

        /* ══════════ CONTENIDO ══════════ */
        .page-content {
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 58px);
        }

        /* ══════════ FOOTER ══════════ */
        .footer {
            position: relative; z-index: 1;
            border-top: 1px solid var(--border);
            padding: 1.2rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-text {
            font-family: 'Space Mono', monospace;
            font-size: 0.58rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-3);
        }

        .footer-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: var(--green);
            box-shadow: 0 0 6px var(--green);
            animation: fpulse 2.5s ease-in-out infinite;
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

    {{-- ══ NAVBAR ══ --}}
    <nav class="navbar">
        <a href="{{ url('/') }}" class="nav-brand">
            <div class="nav-logo">{{ strtoupper(substr(config('app.name','P'),0,1)) }}</div>
            <span class="nav-name">{{ config('app.name', 'Panel') }}</span>
        </a>

        <div class="nav-right">
            @auth
            <div class="nav-user">
                <div class="nav-user-avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                <span class="nav-user-name">{{ Auth::user()->name }}</span>
            </div>

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
        <div class="footer-dot"></div>
        <span class="footer-text">Sistema activo</span>
    </footer>

</body>
</html>