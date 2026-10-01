@extends('layouts.profile')

@section('title', 'Mi perfil — MREYZE')

@section('private-content')

<div class="private-header">
    <h1 class="private-title">Configuración de Perfil</h1>
    <p class="private-subtitle">Gestiona tu información personal, actualiza tus credenciales de acceso y configura la seguridad de tu cuenta.</p>
</div>

<div class="mreyze-stack" style="--stack-space: 2rem;">

    {{-- ═════════════════════════════════════════════════════════════
         INFORMACIÓN PERSONAL
    ═════════════════════════════════════════════════════════════ --}}
    <section class="mreyze-card" id="profile-info">
        <header style="margin-bottom: 1.5rem;">
            <h2 class="mreyze-heading-3" style="margin-bottom: 0.25rem;">Información personal</h2>
            <p class="mreyze-copy" style="font-size: 0.9rem;">Actualiza tu nombre y la dirección de correo electrónico vinculada a tu cuenta.</p>
        </header>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mreyze-grid" style="gap: 1.5rem; margin-bottom: 1.5rem; --grid-gap: 1.5rem;">
                
                {{-- Nombre --}}
                <div>
                    <label for="name" class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Nombre completo</label>
                    <input id="name" name="name" type="text" 
                           class="mreyze-input" style="width: 100%;"
                           value="{{ old('name', $user->name) }}" 
                           required autofocus autocomplete="name"
                           aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
                    @error('name')
                        <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Correo --}}
                <div>
                    <label for="email" class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Correo electrónico</label>
                    <input id="email" name="email" type="email" 
                           class="mreyze-input" style="width: 100%;"
                           value="{{ old('email', $user->email) }}" 
                           required autocomplete="username"
                           aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    @error('email')
                        <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="background: rgba(255, 164, 174, 0.1); border: 1px solid rgba(255, 164, 174, 0.2); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                    <p class="mreyze-copy" style="font-size: 0.85rem; color: var(--color-danger); margin-bottom: 0.5rem;">
                        Tu dirección de correo electrónico no está verificada.
                    </p>
                    <button form="send-verification" class="mreyze-btn mreyze-btn--secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; min-height: auto;">
                        Haz clic aquí para reenviar el correo de verificación
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--color-success);">
                            Se ha enviado un nuevo enlace de verificación a tu correo.
                        </p>
                    @endif
                </div>
            @endif

            <div style="display: flex; align-items: center; gap: 1rem;">
                <button type="submit" class="mreyze-btn">Guardar cambios</button>

                @if (session('status') === 'profile-updated')
                    <span class="mreyze-badge mreyze-badge--success"
                          x-data="{ show: true }"
                          x-show="show"
                          x-transition
                          x-init="setTimeout(() => show = false, 3000)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path d="M20 6L9 17l-5-5"></path></svg>
                        Guardado
                    </span>
                @endif
            </div>
        </form>
    </section>

    {{-- Formulario oculto para reenvío de verificación --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>


    {{-- ═════════════════════════════════════════════════════════════
         CONTRASEÑA
    ═════════════════════════════════════════════════════════════ --}}
    <section class="mreyze-card" id="profile-password">
        <header style="margin-bottom: 1.5rem;">
            <h2 class="mreyze-heading-3" style="margin-bottom: 0.25rem;">Actualizar contraseña</h2>
            <p class="mreyze-copy" style="font-size: 0.9rem;">Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerse segura.</p>
        </header>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mreyze-stack" style="--stack-space: 1.2rem; margin-bottom: 1.5rem; max-width: 480px;">
                
                {{-- Contraseña actual --}}
                <div>
                    <label for="update_password_current_password" class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Contraseña actual</label>
                    <input id="update_password_current_password" name="current_password" type="password" 
                           class="mreyze-input" style="width: 100%;"
                           autocomplete="current-password"
                           aria-invalid="{{ $errors->updatePassword->has('current_password') ? 'true' : 'false' }}">
                    @error('current_password', 'updatePassword')
                        <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nueva contraseña --}}
                <div>
                    <label for="update_password_password" class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Nueva contraseña</label>
                    <input id="update_password_password" name="password" type="password" 
                           class="mreyze-input" style="width: 100%;"
                           autocomplete="new-password"
                           aria-invalid="{{ $errors->updatePassword->has('password') ? 'true' : 'false' }}">
                    @error('password', 'updatePassword')
                        <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirmar contraseña --}}
                <div>
                    <label for="update_password_password_confirmation" class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Confirmar contraseña</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                           class="mreyze-input" style="width: 100%;"
                           autocomplete="new-password"
                           aria-invalid="{{ $errors->updatePassword->has('password_confirmation') ? 'true' : 'false' }}">
                    @error('password_confirmation', 'updatePassword')
                        <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div style="display: flex; align-items: center; gap: 1rem;">
                <button type="submit" class="mreyze-btn">Actualizar contraseña</button>

                @if (session('status') === 'password-updated')
                    <span class="mreyze-badge mreyze-badge--success"
                          x-data="{ show: true }"
                          x-show="show"
                          x-transition
                          x-init="setTimeout(() => show = false, 3000)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;"><path d="M20 6L9 17l-5-5"></path></svg>
                        Guardado
                    </span>
                @endif
            </div>
        </form>
    </section>


    {{-- ═════════════════════════════════════════════════════════════
         ELIMINAR CUENTA
    ═════════════════════════════════════════════════════════════ --}}
    <section class="mreyze-card" id="profile-delete" style="border-color: rgba(146, 45, 64, 0.3);">
        <header style="margin-bottom: 1.5rem;">
            <h2 class="mreyze-heading-3" style="color: #ff8b9d; margin-bottom: 0.25rem;">Eliminar cuenta</h2>
            <p class="mreyze-copy" style="font-size: 0.9rem;">Una vez eliminada la cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de proceder, por favor descarga cualquier dato o información que desees conservar.</p>
        </header>

        <form method="post" action="{{ route('profile.destroy') }}" style="max-width: 480px;">
            @csrf
            @method('delete')

            <div style="margin-bottom: 1.5rem;">
                <label for="confirmacion" class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Escribe la palabra "eliminar" para confirmar</label>
                <input id="confirmacion" name="confirmacion" type="text" 
                       class="mreyze-input" style="width: 100%;"
                       placeholder="eliminar"
                       aria-invalid="{{ $errors->userDeletion->has('confirmacion') ? 'true' : 'false' }}">
                @error('confirmacion', 'userDeletion')
                    <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="mreyze-btn mreyze-btn--danger" onclick="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')">
                Eliminar cuenta de forma permanente
            </button>
        </form>
    </section>

</div>

@endsection