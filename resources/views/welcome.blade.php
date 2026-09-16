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
            --ice-900: #0a2826;
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
        

        [x-cloak] { display: none !important; }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            font-family: 'Inter', sans-serif;
            background: var(--ice-900);
            color: #e2ecff;
            min-height: 100vh;
            overflow-x: hidden;  
            width: 100%;
            overflow-y: auto;
            scrollbar-width: none;      
        }
        body { padding-top: 70px; }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            display: none;

        }

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
    <x-navbar />

    {{-- ══════════════════════════════════════
         CONTENIDO DE LA PÁGINA
    ══════════════════════════════════════ --}}
    <main class="page-main">
        @yield('content')
    </main>

    <nav class="mreyze-mobile-nav" aria-label="Navegación móvil">
        <a href="{{ route('inicio') }}" @class(['is-active' => request()->routeIs('inicio')]) @if(request()->routeIs('inicio')) aria-current="page" @endif>
            <x-ui.icon><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10Z" /></x-ui.icon>
            <span>Inicio</span>
        </a>
        <a href="{{ route('servicios') }}" @class(['is-active' => request()->routeIs('servicios*')]) @if(request()->routeIs('servicios*')) aria-current="page" @endif>
            <x-ui.icon><path d="M12 3v18M3 12h18M5.5 5.5l13 13M18.5 5.5l-13 13" /></x-ui.icon>
            <span>Soluciones</span>
        </a>
        <a href="{{ route('tienda.index') }}" @class(['is-active' => request()->routeIs('tienda.*')]) @if(request()->routeIs('tienda.*')) aria-current="page" @endif>
            <x-ui.icon><path d="M6 8h12l1 13H5L6 8Zm2 0a4 4 0 0 1 8 0" /></x-ui.icon>
            <span>Tienda</span>
        </a>
        <a href="{{ route('contacto') }}" @class(['is-active' => request()->routeIs('contacto')]) @if(request()->routeIs('contacto')) aria-current="page" @endif>
            <x-ui.icon><path d="M4 5h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-6l-4 3v-3H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" /></x-ui.icon>
            <span>Contacto</span>
        </a>
    </nav>

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
                    <p>MreyZe Automatización y Sistemas S.A.C. desarrolla soluciones tecnológicas para empresas e industrias, ofreciendo servicios de desarrollo de software, automatización, sistemas IoT, integración tecnológica, soporte técnico y consultoría especializada.</p>
                </div>

                <div class="footer-col">
                    <h4>Enlaces rápidos</h4>
                    <a href="{{ route('inicio') }}">Inicio</a>
                    <a href="{{ route('nosotros') }}">Nosotros</a>
                    <a href="{{ route('servicios') }}">Servicios</a>
                    <a href="{{ route('proyectos') }}">Proyectos</a>
                    <a href="{{ route('tienda.index') }}">Tienda</a>
                    <a href="{{ route('blog') }}">Blog</a>
                    <a href="{{ route('contacto') }}">Contacto</a>
                </div>

                <div class="footer-col">
                    <h4>Contacto</h4>
                    <a href="tel:{{ config('empresa.telefono_corto') }}"><svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:6px;vertical-align:middle"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>{{ config('empresa.telefono_corto') }}</a>
                    <a href="mailto:{{ config('empresa.email') }}"><svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:6px;vertical-align:middle"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>{{ config('empresa.email') }}</a>
                    <a href="{{ config('empresa.whatsapp_url') }}" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#25D366;color:#fff;border-radius:6px;font-size:0.8rem;font-weight:600;text-decoration:none;margin-top:6px;width:fit-content"><svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-1.102-.984-1.847-2.2-2.063-2.572-.216-.373-.023-.575.163-.763.166-.167.373-.434.56-.651.187-.217.249-.373.373-.622.124-.248.062-.467-.031-.65-.093-.183-.67-1.618-.92-2.217-.242-.579-.487-.48-.67-.49-.173-.01-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.903.521 3.684 1.427 5.226L2 22l4.806-1.414A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18c-1.653 0-3.198-.539-4.446-1.444l-.318-.213-3.052.898.898-3.052-.213-.318A7.94 7.94 0 014 12c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z"/></svg>WhatsApp</a>
                    <h4 style="margin-top:1rem;">Horario</h4>
                    <span style="font-size:0.875rem;color:var(--ice-200);display:block;line-height:1.6">{!! config('empresa.horario_html') !!}</span>
                </div>

                <div class="footer-col">
                    <h4>Redes Sociales</h4>
                    <a href="{{ config('empresa.whatsapp_url') }}" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:6px;vertical-align:middle"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-1.102-.984-1.847-2.2-2.063-2.572-.216-.373-.023-.575.163-.763.166-.167.373-.434.56-.651.187-.217.249-.373.373-.622.124-.248.062-.467-.031-.65-.093-.183-.67-1.618-.92-2.217-.242-.579-.487-.48-.67-.49-.173-.01-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.903.521 3.684 1.427 5.226L2 22l4.806-1.414A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18c-1.653 0-3.198-.539-4.446-1.444l-.318-.213-3.052.898.898-3.052-.213-.318A7.94 7.94 0 014 12c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z"/></svg>WhatsApp</a>
                    <a href="{{ config('empresa.whatsapp_grupo') }}" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:6px;vertical-align:middle"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>Grupo de WhatsApp</a>
                    <a href="{{ config('empresa.redes.facebook') }}" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:6px;vertical-align:middle"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>Facebook</a>
                    <a href="{{ config('empresa.redes.tiktok') }}" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px;margin-right:6px;vertical-align:middle"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.9 2.89 2.89 0 01-2.88-2.89 2.89 2.89 0 012.88-2.89c.3 0 .59.05.87.14v-3.5a6.37 6.37 0 00-.87-.05A6.33 6.33 0 103.9 15.8a6.33 6.33 0 005.69 3.7 6.33 6.33 0 006.33-6.33V8.96a8.27 8.27 0 004.78 1.49v-3.5a4.8 4.8 0 01-3.11-1.26z"/></svg>TikTok</a>
                </div>

            </div>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} MreyZe Automatización y Sistemas S.A.C. Todos los derechos reservados.</span>
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
