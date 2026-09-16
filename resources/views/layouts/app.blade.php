<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>@yield('title', 'MREYZE — Automatización & Sistemas S.A.C')</title>
    <meta name="description" content="@yield('meta_description', 'Soluciones de automatización industrial, IoT y desarrollo de software a medida en Trujillo, Perú.')">

    <!-- FAVICON -->
    <link rel="icon" href="/favicon.ico?v=2" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico?v=2">
    <link rel="apple-touch-icon" href="/img/LOGOMREYZE1.png">
    <meta name="theme-color" content="#0a2826">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --ice-900: #064945;
            --ice-800: #0d1f3c;
            --ice-700: #112952;
            --ice-600: #1a3a6e;
            --ice-500: #1e4d99;
            --ice-400: #2563c7;
            --ice-300: #4a90d9;
            --ice-200: #7ab8f5;
            --ice-100: #b8d9f9;
            --ice-50:  #e8f4ff;
            --glow:    #3b82f6;
            --accent:  #00d4ff;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            font-family: 'Inter', sans-serif;
            background: var(--ice-900);
            color: #e2ecff;
            min-height: 100vh;
        }
        body { padding-top: 70px; }

        /* ── NAVBAR ── */
        .nav-wrapper {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            background: rgba(10, 22, 40, 0.85);
            backdrop-filter: blur(18px) saturate(180%);
            -webkit-backdrop-filter: blur(18px) saturate(180%);
            border-bottom: 1px solid rgba(74, 144, 217, 0.18);
            box-shadow: 0 4px 32px rgba(0, 90, 200, 0.12);
        }
        .navbar { display: flex; align-items: center; max-width: 1280px; margin: 0 auto; height: 68px; padding: 0 2rem; }
        .nav-logo { display: flex; align-items: center; gap: 4px; text-decoration: none; }
        .nav-logo-icon { width: 78px; height: 70px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .nav-logo-icon img { width: 100%; height: 100%; object-fit: contain; }
        .nav-logo-text { font-family: 'Exo 2', sans-serif; font-weight: 700; font-size: 1.2rem; letter-spacing: 0.03em; color: #fff; line-height: 1; }
        .nav-logo-text span { color: var(--accent); }
        .nav-logo-sub { font-size: 0.58rem; font-weight: 300; color: var(--ice-200); letter-spacing: 0.15em; text-transform: uppercase; display: block; margin-top: 2px; }
        .nav-links { display: flex; align-items: center; justify-content: center; gap: 0; list-style: none; flex: 1; }
        .nav-links a { display: flex; align-items: center; gap: 2px; padding: 0.35rem 0.45rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; color: var(--ice-100); text-decoration: none; letter-spacing: 0.02em; transition: all 0.22s ease; position: relative; white-space: nowrap; }
        .nav-links a svg { width: 15px; height: 15px; opacity: 0.7; transition: opacity 0.2s; }
        .nav-links a:hover { color: #fff; background: rgba(37, 99, 199, 0.25); }
        .nav-links a:hover svg { opacity: 1; }
        .nav-links a::after { content: ''; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%) scaleX(0); width: 60%; height: 2px; background: linear-gradient(90deg, transparent, var(--accent), transparent); border-radius: 2px; transition: transform 0.25s ease; }
        .nav-links a:hover::after, .nav-links a.active::after { transform: translateX(-50%) scaleX(1); }
        .nav-links a.active { color: #fff; background: rgba(37, 99, 199, 0.2); }
        .nav-item { position: relative; }
        .nav-item:hover .dropdown { opacity: 1; pointer-events: all; transform: translateY(0); }
        .dropdown { position: absolute; top: calc(100% + 10px); left: 0; min-width: 230px; background: rgba(13, 31, 60, 0.97); border: 1px solid rgba(74, 144, 217, 0.22); border-radius: 14px; padding: 0.6rem; box-shadow: 0 16px 48px rgba(0, 30, 90, 0.5); opacity: 0; pointer-events: none; transform: translateY(-8px); transition: all 0.22s ease; backdrop-filter: blur(16px); }
        .dropdown a { display: flex; align-items: center; gap: 10px; padding: 0.55rem 0.85rem; border-radius: 8px; font-size: 0.835rem; color: var(--ice-100); text-decoration: none; transition: all 0.18s; }
        .dropdown a svg { width: 16px; height: 16px; color: var(--ice-300); flex-shrink: 0; }
        .dropdown a:hover { background: rgba(37, 99, 199, 0.3); color: #fff; padding-left: 1.1rem; }
        .dropdown-label { font-size: 0.7rem; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: var(--ice-300); padding: 0.5rem 0.85rem 0.25rem; }
        .dropdown-divider { height: 1px; background: rgba(74, 144, 217, 0.15); margin: 0.4rem 0; }
        .nav-right { display: flex; align-items: center; gap: 0.4rem; }
        .btn-login { display: flex; align-items: center; gap: 3px; padding: 0.35rem 0.7rem; border-radius: 8px; font-size: 0.85rem; font-weight: 500; color: var(--ice-100); background: rgba(37, 99, 199, 0.18); border: 1px solid rgba(74, 144, 217, 0.3); text-decoration: none; transition: all 0.22s; cursor: pointer; }
        .btn-login svg { width: 15px; height: 15px; }
        .btn-login:hover { background: rgba(37, 99, 199, 0.35); border-color: rgba(74, 144, 217, 0.6); color: #fff; }
        .btn-cta { display: flex; align-items: center; gap: 3px; padding: 0.4rem 0.85rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; color: #fff; background: linear-gradient(135deg, var(--ice-500), var(--ice-400)); border: 1px solid rgba(74, 144, 217, 0.4); text-decoration: none; letter-spacing: 0.02em; transition: all 0.25s; box-shadow: 0 4px 16px rgba(30, 77, 153, 0.45); cursor: pointer; white-space: nowrap; }
        .btn-cta svg { width: 15px; height: 15px; }
        .btn-cta:hover { background: linear-gradient(135deg, var(--ice-400), var(--glow)); box-shadow: 0 6px 24px rgba(59, 130, 246, 0.55); transform: translateY(-1px); }
        .btn-google { display: flex; align-items: center; gap: 3px; padding: 0.35rem 0.6rem; border-radius: 8px; font-size: 0.835rem; font-weight: 500; color: var(--ice-100); background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); text-decoration: none; transition: all 0.22s; cursor: pointer; }
        .btn-google img { width: 16px; height: 16px; }
        .btn-google:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.25); color: #fff; }
        .nav-user { display: flex; align-items: center; gap: 8px; padding: 0.35rem 0.75rem 0.35rem 0.4rem; border-radius: 30px; background: rgba(37, 99, 199, 0.18); border: 1px solid rgba(74, 144, 217, 0.28); cursor: pointer; transition: all 0.22s; text-decoration: none; color: var(--ice-100); font-size: 0.85rem; font-weight: 500; }
        .nav-user:hover { background: rgba(37, 99, 199, 0.32); color: #fff; }
        .nav-user-avatar { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, var(--ice-500), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; color: #fff; letter-spacing: 0.02em; flex-shrink: 0; }
        .nav-toggle { display: none !important; margin-left: auto; flex-direction: column; gap: 5px; background: none; border: none; cursor: pointer; padding: 8px; border-radius: 8px; transition: background 0.2s; }
        .nav-toggle:hover { background: rgba(37, 99, 199, 0.2); }
        .nav-toggle span { display: block; width: 22px; height: 2px; background: var(--ice-200); border-radius: 2px; transition: all 0.3s; }
        .nav-toggle.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        .nav-toggle.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .nav-toggle.open span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }
        .nav-accent-line { height: 2px; background: linear-gradient(90deg, transparent 0%, var(--ice-500) 20%, var(--accent) 50%, var(--ice-500) 80%, transparent 100%); }

        /* Mobile nav */
        .nav-mobile { display: none !important; flex-direction: column; gap: 0.8rem; padding: 0.9rem 0.9rem 1rem; border-top: 1px solid rgba(74, 144, 217, 0.16); background: linear-gradient(180deg, rgba(13, 31, 60, 0.95), rgba(7, 18, 36, 0.96)); backdrop-filter: blur(14px); }
        .nav-mobile.open { display: flex !important; animation: slideDown 0.28s ease; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .nav-mobile-section { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.6rem; }
        .nav-mobile-link { display: flex; align-items: center; gap: 0.7rem; padding: 0.8rem 0.9rem; border-radius: 12px; border: 1px solid rgba(74, 144, 217, 0.16); background: rgba(255, 255, 255, 0.03); color: var(--ice-100); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s ease; }
        .nav-mobile-link:hover, .nav-mobile-link.active { background: rgba(37, 99, 199, 0.22); border-color: rgba(74, 144, 217, 0.35); color: #fff; transform: translateY(-1px); }
        .nav-mobile-icon { width: 34px; height: 34px; display: grid; place-items: center; flex-shrink: 0; border-radius: 10px; background: rgba(37, 99, 199, 0.16); color: var(--accent); }
        .nav-mobile-icon svg { width: 16px; height: 16px; }
        .nav-mobile-divider { height: 1px; background: rgba(74, 144, 217, 0.14); margin: 0.2rem 0; }
        .nav-mobile-actions { display: flex; flex-direction: column; gap: 0.6rem; }
        .nav-mobile-action { display: flex; align-items: center; justify-content: center; gap: 0.6rem; padding: 0.75rem 0.95rem; border-radius: 999px; border: 1px solid rgba(74, 144, 217, 0.18); background: rgba(255, 255, 255, 0.05); color: var(--ice-100); text-decoration: none; font-weight: 600; transition: all 0.2s ease; }
        .nav-mobile-action:hover { background: rgba(37, 99, 199, 0.2); color: #fff; }
        .nav-mobile-action.primary { background: linear-gradient(135deg, var(--ice-500), var(--ice-400)); border-color: rgba(74, 144, 217, 0.4); color: #fff; box-shadow: 0 6px 18px rgba(30, 77, 153, 0.3); }
        .nav-mobile-action.google { justify-content: center; }
        .nav-mobile-action img { width: 16px; height: 16px; }
        @media (max-width: 560px) {
            .nav-mobile-section { grid-template-columns: 1fr; }
            .nav-mobile { padding: 0.8rem 0.8rem 1rem; }
        }

        /* Nav user dropdown */
        .nav-user-wrapper { position: relative; }
        .nav-user-dropdown { position: absolute; top: calc(100% + 10px); right: 0; min-width: 190px; background: rgba(13, 31, 60, 0.98); border: 1px solid rgba(74, 144, 217, 0.25); border-radius: 12px; padding: 0.5rem; box-shadow: 0 16px 40px rgba(0, 30, 90, 0.55); backdrop-filter: blur(16px); opacity: 0; transform: translateY(-8px); pointer-events: none; transition: all 0.22s ease; z-index: 999; }
        .nav-user-dropdown.show { opacity: 1; transform: translateY(0); pointer-events: auto; }
        .nav-user-dropdown a { display: block; padding: 0.55rem 0.75rem; border-radius: 8px; font-size: 0.85rem; color: var(--ice-100); text-decoration: none; transition: all 0.18s; }
        .nav-user-dropdown a:hover { background: rgba(37, 99, 199, 0.3); color: #fff; }
        .nav-user-dropdown form { margin: 0; }
        .nav-user-dropdown button { width: 100%; padding: 0.55rem 0.75rem; border-radius: 8px; font-size: 0.85rem; background: none; border: none; text-align: left; color: #ff6b6b; cursor: pointer; transition: background 0.18s; }
        .nav-user-dropdown button:hover { background: rgba(255, 107, 107, 0.1); }

        /* Scroll Reveal */
        .reveal { opacity: 0; transform: translateY(32px); transition: opacity 0.75s ease, transform 0.75s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.12s; }
        .reveal-delay-2 { transition-delay: 0.24s; }
        .reveal-delay-3 { transition-delay: 0.36s; }

        /* Shared hero button */
        .hero-btn-primary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 0.85rem 2rem; border-radius: 10px;
            font-size: 0.95rem; font-weight: 600; color: #fff;
            background: linear-gradient(135deg, var(--ice-500), var(--ice-400));
            border: 1px solid rgba(74, 144, 217, 0.4);
            text-decoration: none; transition: all 0.3s;
            box-shadow: 0 6px 24px rgba(30, 77, 153, 0.5);
            letter-spacing: 0.02em;
        }
        .hero-btn-primary:hover {
            background: linear-gradient(135deg, var(--ice-400), var(--glow));
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.6);
            transform: translateY(-2px); color: #fff;
        }

        /* Section shared styles */
        .section-label { display: inline-flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: var(--accent); margin-bottom: 1rem; }
        .section-label::before { content: ''; width: 24px; height: 2px; background: var(--accent); border-radius: 2px; }
        .section-title { font-family: 'Exo 2', sans-serif; font-size: clamp(1.8rem, 3.5vw, 2.8rem); font-weight: 700; color: #fff; line-height: 1.15; letter-spacing: -0.02em; margin-bottom: 1rem; }
        .section-title span { background: linear-gradient(135deg, var(--ice-200), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .section-desc { font-size: 1rem; color: var(--ice-200); line-height: 1.7; max-width: 520px; }

        /* Footer */
        .site-footer { background: var(--ice-800); border-top: 1px solid rgba(74, 144, 217, 0.2); padding: 56px 2rem 0; }
        .footer-inner { max-width: 1280px; margin: 0 auto; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; padding-bottom: 40px; }
        .footer-brand-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; text-decoration: none; }
        .footer-brand p { font-size: 0.875rem; color: var(--ice-300); line-height: 1.65; max-width: 280px; margin: 0; }
        .footer-col h4 { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: var(--ice-300); margin: 0 0 14px; }
        .footer-col a { display: block; font-size: 0.875rem; color: var(--ice-200); text-decoration: none; margin-bottom: 10px; transition: color 0.2s; }
        .footer-col a:hover { color: #fff; padding-left: 4px; }
        .footer-bottom { border-top: 1px solid rgba(74, 144, 217, 0.12); padding: 20px 0 24px; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: var(--ice-300); }
        .footer-bottom a { color: var(--accent); text-decoration: none; }
        .footer-bottom a:hover { color: #fff; }

        /* Page main */
        .page-main { min-height: calc(100vh - 70px); }

        @media (max-width: 900px) {
            .nav-links, .nav-right { display: none !important; }
            .nav-toggle { display: flex !important; }
        }
        @media (min-width: 901px) {
            .nav-toggle { display: none !important; }
            .nav-mobile { display: none !important; }
        }
        @media (max-width: 768px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .footer-brand { grid-column: 1 / -1; }
            .footer-bottom { flex-direction: column; gap: 8px; text-align: center; }
        }
        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>

    @stack('styles')
</head>
<body class="mreyze-ui mreyze-has-header">

    {{-- Línea decorativa --}}

    {{-- ══════════════════════════════════════
         NAVBAR
    ══════════════════════════════════════ --}}
    <x-navbar :show-dashboard="true" />

    {{-- ══════════════════════════════════════
         CONTENIDO DE LA PÁGINA
    ══════════════════════════════════════ --}}
    <main class="page-main">
        @yield('content')
    </main>

    {{-- ══════════════════════════════════════
         FOOTER
    ══════════════════════════════════════ --}}
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">

                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="footer-brand-logo">
                        <div class="nav-logo-icon">
                            <img src="{{ asset('img/LOGOMREYZE1.png') }}" alt="MREYZE Logo">
                        </div>
                        <div>
                            <span class="nav-logo-sub">Automatización y Sistemas S.A.C</span>
                        </div>
                    </a>
                    <p>Soluciones de automatización industrial, IoT y desarrollo de software a medida para la industria moderna en Trujillo, Perú.</p>
                </div>

                <div class="footer-col">
                    <h4>Servicios</h4>
                    <a href="{{ url('/servicios/automatizacion') }}">Automatización Industrial</a>
                    <a href="{{ url('/servicios/iot') }}">IoT &amp; Electrónica</a>
                    <a href="{{ url('/servicios/desarrollo') }}">Desarrollo de Software</a>
                    <a href="{{ url('/servicios/consultoria') }}">Consultoría</a>
                </div>

                <div class="footer-col">
                    <h4>Empresa</h4>
                    <a href="{{ url('/nosotros') }}">Nosotros</a>
                    <a href="{{ url('/proyectos') }}">Proyectos</a>
                    <a href="{{ url('/tienda') }}">Tienda</a>
                    <a href="{{ url('/blog') }}">Blog</a>
                    <a href="{{ url('/contacto') }}">Contacto</a>
                </div>

                <div class="footer-col">
                    <h4>Legal</h4>
                    <a href="{{ url('/privacidad') }}">Política de privacidad</a>
                    <a href="{{ url('/terminos') }}">Términos de uso</a>
                    <h4 style="margin-top: 1.5rem;">Contacto</h4>
                    <a href="mailto:{{ config('empresa.email') }}">{{ config('empresa.email') }}</a>
                    <a href="tel:{{ config('empresa.telefono_corto') }}">{{ config('empresa.telefono') }}</a>
                </div>

            </div>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} MREYZE. Todos los derechos reservados.</span>
                <span>Hecho en Perú 🇵🇪</span>
            </div>
        </div>
    </footer>

    <script>
        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    </script>

    @stack('scripts')

</body>
</html>
