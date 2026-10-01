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
                    <li><x-navigation.link :href="route('tienda.index')" :active="request()->routeIs('tienda.*')" title="Hardware e IoT">Tienda</x-navigation.link></li>
                    <li>
                        <details @class(['mreyze-header-group', 'is-active' => request()->routeIs('blog', 'tarjeta.*', 'perfil.corporativo')]) data-header-group>
                            <summary aria-controls="mreyze-recursos">Recursos <x-navigation.chevron /></summary>
                            <div class="mreyze-header-dropdown" id="mreyze-recursos">
                                <p class="mreyze-header-label">Conoce y conecta</p>
                                <x-navigation.link :href="route('blog')" :active="request()->routeIs('blog')">Blog</x-navigation.link>
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
                            <form method="POST" action="{{ route('logout') }}" class="mreyze-header-logout">
                                @csrf
                                <button type="submit"><x-ui.icon><path d="M9 5H5v14h4m5-12 5 5-5 5m-5-5h10" /></x-ui.icon>Cerrar sesión</button>
                            </form>
                        </div>
                    </details>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="mreyze-header-register" style="background: transparent; border-color: var(--border-default); color: var(--text-primary);">Iniciar sesión</a>
                    @endif
                @endauth
                @auth
                    @if(auth()->user()->colaborador)
                        <a href="{{ route('colaborador.link') }}" class="mreyze-header-register" style="background: rgba(37, 99, 199, 0.15); border-color: rgba(74, 144, 217, 0.2); color: var(--text-primary); margin-right: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                            Mi Link
                        </a>
                    @else
                        <a href="{{ route('colaboradores.solicitud') }}" class="mreyze-header-register" style="background: rgba(37, 99, 199, 0.15); border-color: rgba(74, 144, 217, 0.2); color: var(--text-primary); margin-right: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5.5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                            Ser colaborador
                        </a>
                    @endif
                @endauth
                <a href="{{ route('contacto') }}" @class(['mreyze-header-cta', 'is-active' => request()->routeIs('contacto')]) @if(request()->routeIs('contacto')) aria-current="page" @endif>Hablemos <x-ui.icon><path d="M5 12h14m-6-6 6 6-6 6" /></x-ui.icon></a>
            </div>
        </div>
    </div>
</header>
