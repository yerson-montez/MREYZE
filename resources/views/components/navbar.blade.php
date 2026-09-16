@props(['showDashboard' => false])

<header class="mreyze-header" data-mreyze-header>
    <div class="mreyze-header-inner">
        <a class="mreyze-header-brand" href="{{ route('inicio') }}" aria-label="MREYZE, inicio">
            <img src="{{ asset('img/LOGOMREYZE1.png') }}" alt="" width="60" height="54" fetchpriority="high">
            <span class="mreyze-header-wordmark">MREYZE<span>Automatización y sistemas</span></span>
        </a>

        <button type="button" class="mreyze-header-toggle" id="navToggle" aria-expanded="false" aria-controls="navMobile" aria-label="Abrir menú de navegación" data-header-toggle hidden>
            <x-ui.icon><path d="M4 6h16M4 12h16M4 18h16" /></x-ui.icon>
        </button>

        <button type="button" class="mreyze-header-backdrop" aria-label="Cerrar menú de navegación" tabindex="-1" data-header-backdrop hidden></button>

        <div class="mreyze-header-panel" id="navMobile" data-header-panel>
            <div class="mreyze-header-drawer-top">
                <span id="mreyze-menu-title">Explora MREYZE</span>
                <button type="button" class="mreyze-header-close" aria-label="Cerrar menú de navegación" data-header-close>
                    <x-ui.icon><path d="m6 6 12 12M6 18 18 6" /></x-ui.icon>
                </button>
            </div>

            <nav class="mreyze-header-nav" aria-label="Navegación principal">
                <ul class="mreyze-header-links">
                    <li><x-navigation.link :href="route('inicio')" :active="request()->routeIs('inicio')">Inicio</x-navigation.link></li>
                    <li>
                        <details @class(['mreyze-header-group', 'is-active' => request()->routeIs('servicios*')]) data-header-group>
                            <summary aria-controls="mreyze-soluciones">Soluciones <x-navigation.chevron /></summary>
                            <div class="mreyze-header-dropdown mreyze-header-solutions" id="mreyze-soluciones">
                                <div class="mreyze-header-dropdown-intro">
                                    <span>Ingeniería para tu empresa</span>
                                    <x-navigation.link :href="route('servicios')" :active="request()->routeIs('servicios')">Todas las soluciones <x-ui.icon><path d="M5 12h14m-6-6 6 6-6 6" /></x-ui.icon></x-navigation.link>
                                </div>
                                <div class="mreyze-header-link-group">
                                    <p class="mreyze-header-label">Industria</p>
                                    <x-navigation.link :href="route('servicios.automatizacion')" :active="request()->routeIs('servicios.automatizacion')">Automatización industrial</x-navigation.link>
                                    <x-navigation.link :href="route('servicios.iot')" :active="request()->routeIs('servicios.iot')">IoT y sistemas embebidos</x-navigation.link>
                                </div>
                                <div class="mreyze-header-link-group">
                                    <p class="mreyze-header-label">Software</p>
                                    <x-navigation.link :href="route('servicios.desarrollo')" :active="request()->routeIs('servicios.desarrollo')">Desarrollo de software y web</x-navigation.link>
                                    <x-navigation.link :href="route('servicios.consultoria')" :active="request()->routeIs('servicios.consultoria')">Consultoría y soporte</x-navigation.link>
                                </div>
                            </div>
                        </details>
                    </li>
                    <li><x-navigation.link :href="route('proyectos')" :active="request()->routeIs('proyectos*', 'inversiones.*')" title="Proyectos y oportunidades de inversión">Proyectos</x-navigation.link></li>
                    <li><x-navigation.link :href="route('nosotros')" :active="request()->routeIs('nosotros')">Nosotros</x-navigation.link></li>
                    <li>
                        <details @class(['mreyze-header-group', 'is-active' => request()->routeIs('blog', 'tienda.*', 'tarjeta.*', 'perfil.corporativo')]) data-header-group>
                            <summary aria-controls="mreyze-recursos">Recursos <x-navigation.chevron /></summary>
                            <div class="mreyze-header-dropdown" id="mreyze-recursos">
                                <p class="mreyze-header-label">Conoce y conecta</p>
                                <x-navigation.link :href="route('blog')" :active="request()->routeIs('blog')">Blog</x-navigation.link>
                                <x-navigation.link :href="route('tienda.index')" :active="request()->routeIs('tienda.*')">Tienda tecnológica</x-navigation.link>
                                <x-navigation.link :href="route('tarjeta.debito')" :active="request()->routeIs('tarjeta.*')">Tarjeta de débito</x-navigation.link>
                                <x-navigation.link :href="route('perfil.corporativo')" :active="request()->routeIs('perfil.corporativo')">Perfil corporativo</x-navigation.link>
                            </div>
                        </details>
                    </li>
                </ul>
            </nav>

            <div class="mreyze-header-actions">
                @auth
                    <details @class(['mreyze-header-group', 'mreyze-header-account', 'is-active' => request()->routeIs('profile.*', 'pedidos.*', 'inversiones.mis', 'colaborador.link', 'colaboradores.*', 'dashboard')]) data-header-group>
                        <summary id="userMenuToggle" aria-controls="userDropdown">
                            <span class="mreyze-header-avatar" aria-hidden="true">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                            <span class="mreyze-header-account-copy"><span class="mreyze-header-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span><span class="mreyze-header-caption">Mi cuenta</span></span>
                            <x-navigation.chevron />
                        </summary>
                        <div class="mreyze-header-dropdown" id="userDropdown">
                            <p class="mreyze-header-label">Tu espacio MREYZE</p>
                            <x-navigation.link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">Mi perfil</x-navigation.link>
                            <x-navigation.link :href="route('pedidos.mis')" :active="request()->routeIs('pedidos.*')">Mis pedidos</x-navigation.link>
                            <x-navigation.link :href="route('inversiones.mis')" :active="request()->routeIs('inversiones.mis')">Mis inversiones</x-navigation.link>
                            @if($showDashboard)
                                <x-navigation.link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Mi Dashboard</x-navigation.link>
                            @endif
                            @php
                                $colaborador = auth()->user()->colaborador;
                            @endphp
                            @if($colaborador)
                                <x-navigation.link :href="route('colaborador.link')" :active="request()->routeIs('colaborador.link')">Mi link de colaborador</x-navigation.link>
                            @else
                                <x-navigation.link :href="route('colaboradores.solicitud')" :active="request()->routeIs('colaboradores.*')">Quiero ser colaborador</x-navigation.link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="mreyze-header-logout">
                                @csrf
                                <button type="submit"><x-ui.icon><path d="M9 5H5v14h4m5-12 5 5-5 5m-5-5h10" /></x-ui.icon>Cerrar sesión</button>
                            </form>
                        </div>
                    </details>
                @else
                    <details class="mreyze-header-group mreyze-header-signin" data-header-group>
                        <summary aria-controls="mreyze-access">Iniciar sesión <x-navigation.chevron /></summary>
                        <div class="mreyze-header-dropdown" id="mreyze-access">
                            <p class="mreyze-header-label">Accede a tu cuenta</p>
                            @if (Route::has('login'))
                                <x-navigation.link :href="route('login')">Con correo y contraseña</x-navigation.link>
                            @endif
                            <x-navigation.link :href="route('auth.google')">
                                <svg class="mreyze-icon" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M21.6 12.2c0-.7-.1-1.4-.2-2.1H12v4h5.4a4.6 4.6 0 0 1-2 3 6 6 0 1 1 .1-10l3-3A10 10 0 1 0 22 12c0 .1-.4.2-.4.2Z"/></svg>
                                Continuar con Google
                            </x-navigation.link>
                        </div>
                    </details>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="mreyze-header-register">Registrarse</a>
                    @endif
                @endauth
                <a href="{{ route('contacto') }}" @class(['mreyze-header-cta', 'is-active' => request()->routeIs('contacto')]) @if(request()->routeIs('contacto')) aria-current="page" @endif>Hablemos <x-ui.icon><path d="M5 12h14m-6-6 6 6-6 6" /></x-ui.icon></a>
            </div>
        </div>
    </div>
</header>
