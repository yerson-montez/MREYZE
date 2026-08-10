{{-- resources/views/components/navbar.blade.php --}}

{{-- Top accent line --}}
<div class="nav-accent-line"></div>

<header class="nav-wrapper">
    <nav class="navbar">

        {{-- ── Logo ── --}}
        <a href="{{ url('/') }}" class="nav-logo">
            <div class="nav-logo-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13 3L4 14h7l-1 7 9-11h-7l1-7z"/>
                </svg>
            </div>
            <div>
                <span class="nav-logo-text">Tech<span>Sys</span></span>
                <span class="nav-logo-sub">Automatización & Software</span>
            </div>
        </a>

        {{-- ── Links de navegación ── --}}
        <ul class="nav-links">

            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    Inicio
                </a>
            </li>

            <li>
                <a href="{{ url('/nosotros') }}" class="{{ request()->is('nosotros') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    Nosotros
                </a>
            </li>

            {{-- Servicios con dropdown --}}
            <li class="nav-item">
                <a href="{{ url('/servicios') }}" class="{{ request()->is('servicios*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                    Servicios
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
                </a>
                <div class="dropdown">
                    <div class="dropdown-label">Industria</div>
                    <a href="{{ url('/servicios/automatizacion') }}">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                        Automatización Industrial 
                    </a>
                    <a href="{{ url('/servicios/iot') }}">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z"/></svg>
                        IoT &amp; Electrónica Embebida
                    </a>
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-label">Software</div>
                    <a href="{{ url('/servicios/desarrollo') }}">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                        Desarrollo de Software
                    </a>
                    <a href="{{ url('/servicios/consultoria') }}">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                        Consultoría &amp; Soporte
                    </a>
                </div>
            </li>

            <li>
                <a href="{{ url('/proyectos') }}" class="{{ request()->is('proyectos*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 6h-2.18c.07-.44.18-.87.18-1.33C18 2.54 16.46 1 14.67 1c-1.06 0-1.96.54-2.67 1.4L12 2.5l-.33-.4C10.96 1.54 10 1 9.33 1 7.54 1 6 2.54 6 4.33c0 .43.1.86.18 1.3H4c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/></svg>
                    Proyectos
                </a>
            </li>

            <li>
                <a href="{{ url('/tienda') }}" class="{{ request()->is('tienda*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    Tiendaaaa
                    <span class="nav-dot"></span>
                </a>
            </li>

            <li>
                <a href="{{ url('/blog') }}" class="{{ request()->is('blog*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    Blog
                </a>
            </li>

            <li>
                <a href="{{ url('/contacto') }}" class="{{ request()->is('contacto') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    Contacto
                </a>
            </li>

        </ul>

        {{-- ── Botones derecha (auth) ── --}}
        <div class="nav-right">
            @auth
                {{-- Usuario logueado: muestra avatar con iniciales --}}
                <a href="{{ url('/dashboard') }}" class="nav-user">
                    <div class="nav-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    {{ Auth::user()->name }}
                </a>
            @else
                {{-- Google Login --}}
                <a href="{{ route('auth.google') }}" class="btn-google">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                    Google
                </a>

                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="btn-login">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z"/></svg>
                        Iniciar sesión
                    </a>
                @endif

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-cta">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Registrarse
                    </a>
                @endif
            @endauth
        </div>

        {{-- ── Botón hamburguesa (móvil) ── --}}
        <button class="nav-toggle" id="navToggle" aria-label="Menú">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </nav>

    {{-- ── Menú móvil desplegable ── --}}
    <div class="nav-mobile" id="navMobile">

        <a href="{{ url('/') }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
            Inicio
        </a>
        <a href="{{ url('/nosotros') }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            Nosotros
        </a>
        <a href="{{ url('/servicios') }}">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
            Servicios
        </a>
        <a href="{{ url('/proyectos') }}">Proyectos</a>
        <a href="{{ url('/tienda') }}">Tienda</a>
        <a href="{{ url('/blog') }}">Blog</a>
        <a href="{{ url('/contacto') }}">Contacto</a>
        
        @auth
            @php
                $colaborador = auth()->user()->colaborador;
            @endphp
            @if($colaborador)
                <a href="{{ route('colaborador.link') }}">🔗 Mi link</a>
            @else
                <a href="{{ route('colaboradores.solicitud') }}">Quiero ser colaborador</a>
            @endif
            <div class="nav-mobile-divider"></div>
            <a href="{{ url('/dashboard') }}">Mi Dashboard</a>
        @else
            <a href="{{ route('auth.google') }}">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" style="width:16px">
                Iniciar con Google
            </a>
            @if (Route::has('login'))
                <a href="{{ route('login') }}">Iniciar sesión</a>
            @endif
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-cta">Registrarse</a>
            @endif
        @endauth

    </div>
</header>

<script>
    const toggle = document.getElementById('navToggle');
    const mobile = document.getElementById('navMobile');
    toggle.addEventListener('click', () => {
        toggle.classList.toggle('open');
        mobile.classList.toggle('open');
    });
</script>