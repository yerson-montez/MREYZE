@extends('layouts.app')

@push('styles')
<style>
    /* ═════════════════════════════════════════════════════════════
       MREYZE PRIVATE AREA LAYOUT (Dashboard & Profile)
    ═════════════════════════════════════════════════════════════ */
    .private-layout {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 70px); /* Discounting navbar height */
        padding: var(--section-space) 0;
    }

    @media (min-width: 769px) {
        .private-layout {
            flex-direction: row;
            gap: 2rem;
            align-items: flex-start;
        }
    }

    /* ── SIDEBAR ── */
    .private-sidebar {
        width: 100%;
        margin-bottom: 2rem;
    }

    @media (min-width: 769px) {
        .private-sidebar {
            width: 250px;
            flex-shrink: 0;
            position: sticky;
            top: 100px;
            margin-bottom: 0;
        }
    }

    .sidebar-user {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 1.2rem;
        margin-bottom: 1.2rem;
        border-bottom: 1px solid var(--border-hover);
    }

    .sidebar-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--ice-500), var(--accent));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .sidebar-user-info {
        min-width: 0;
    }

    .sidebar-user-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-user-role {
        font-size: 0.75rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Navegación del Sidebar */
    .sidebar-nav {
        display: flex;
        flex-direction: row;
        overflow-x: auto;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        scrollbar-width: none; /* Firefox */
    }
    .sidebar-nav::-webkit-scrollbar { display: none; } /* Chrome */

    @media (min-width: 769px) {
        .sidebar-nav {
            flex-direction: column;
            overflow-x: visible;
            padding-bottom: 0;
        }
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.65rem 1rem;
        border-radius: var(--radius-md);
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-decoration: none;
        transition: all var(--motion-fast);
        white-space: nowrap;
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }

    .sidebar-link svg {
        width: 18px;
        height: 18px;
        opacity: 0.7;
        transition: opacity var(--motion-fast);
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.03);
        color: var(--text-primary);
    }

    .sidebar-link.active {
        background: rgba(37, 99, 199, 0.15);
        color: #fff;
        border-color: rgba(74, 144, 217, 0.2);
    }
    
    .sidebar-link.active svg {
        color: var(--cyan);
        opacity: 1;
    }

    /* ── MAIN CONTENT ── */
    .private-content {
        flex: 1;
        min-width: 0; /* Prevenir desbordamiento de grid/flex hijos */
    }

    .private-header {
        margin-bottom: 2rem;
    }

    .private-title {
        font-family: var(--font-heading);
        font-size: 1.8rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.02em;
        margin-bottom: 0.4rem;
    }

    .private-subtitle {
        color: var(--text-secondary);
        font-size: 0.9rem;
        max-width: 600px;
    }

</style>
@endpush

@section('content')
<div class="mreyze-container private-layout">
    
    <aside class="private-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ Auth::user()->name ?? 'Usuario' }}</div>
                <div class="sidebar-user-role">{{ Auth::user()->email ?? 'Sesión activa' }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9"></rect>
                    <rect x="14" y="3" width="7" height="5"></rect>
                    <rect x="14" y="12" width="7" height="9"></rect>
                    <rect x="3" y="16" width="7" height="5"></rect>
                </svg>
                Resumen
            </a>
            
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Mi perfil
            </a>

            @if(Route::has('pedidos.mis'))
            <a href="{{ route('pedidos.mis') }}" class="sidebar-link {{ request()->routeIs('pedidos.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Mis pedidos
            </a>
            @endif

            @if(Route::has('inversiones.mis'))
            <a href="{{ route('inversiones.mis') }}" class="sidebar-link {{ request()->routeIs('inversiones.mis') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Mis inversiones
            </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" style="margin-top: auto; padding-top: 1rem; min-width: max-content;">
                @csrf
                <button type="submit" class="sidebar-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </nav>
    </aside>

    <div class="private-content">
        @yield('private-content')
    </div>

</div>
@endsection
