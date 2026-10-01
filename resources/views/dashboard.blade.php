@extends('layouts.profile')

@section('title', 'Mi Dashboard — MREYZE')

@section('private-content')
<div class="private-header">
    <h1 class="private-title">Buenos días, {{ explode(' ', Auth::user()->name)[0] }}</h1>
    <p class="private-subtitle">Bienvenido a tu espacio MREYZE. Desde aquí puedes gestionar tu cuenta, revisar tus pedidos y hacer seguimiento a tus inversiones.</p>
</div>

<div class="mreyze-grid" style="margin-top: 2rem;">
    
    <a href="{{ route('profile.edit') }}" class="mreyze-card mreyze-card--interactive" style="text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(20, 216, 255, 0.1); display: flex; align-items: center; justify-content: center; color: var(--cyan);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
        <div>
            <h3 class="mreyze-heading-3" style="font-size: 1.1rem; margin-bottom: 0.2rem;">Mi perfil</h3>
            <p class="mreyze-copy" style="font-size: 0.85rem; margin: 0;">Administra tu información personal y credenciales de acceso.</p>
        </div>
    </a>

    @if(Route::has('pedidos.mis'))
    <a href="{{ route('pedidos.mis') }}" class="mreyze-card mreyze-card--interactive" style="text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(37, 99, 199, 0.15); display: flex; align-items: center; justify-content: center; color: var(--primary);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </div>
        <div>
            <h3 class="mreyze-heading-3" style="font-size: 1.1rem; margin-bottom: 0.2rem;">Mis pedidos</h3>
            <p class="mreyze-copy" style="font-size: 0.85rem; margin: 0;">Consulta el estado de tus compras de hardware y componentes.</p>
        </div>
    </a>
    @endif

    @if(Route::has('inversiones.mis'))
    <a href="{{ route('inversiones.mis') }}" class="mreyze-card mreyze-card--interactive" style="text-decoration: none; display: flex; flex-direction: column; gap: 0.8rem;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(116, 223, 176, 0.15); display: flex; align-items: center; justify-content: center; color: var(--color-success);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <div>
            <h3 class="mreyze-heading-3" style="font-size: 1.1rem; margin-bottom: 0.2rem;">Mis inversiones</h3>
            <p class="mreyze-copy" style="font-size: 0.85rem; margin: 0;">Revisa el progreso y retorno de tus proyectos de inversión activos.</p>
        </div>
    </a>
    @endif

</div>

@endsection