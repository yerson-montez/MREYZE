<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mreyze-eyebrow mb-2 flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full" style="background-color: var(--primary);"></span>
        SISTEMA CENTRAL
    </div>
    
    <h1 class="text-2xl lg:text-3xl font-heading font-bold mb-3" style="color: var(--text-primary);">Accede a tu cuenta</h1>
    <p class="text-sm mb-8" style="color: var(--text-secondary);">Ingresa al sistema para gestionar tus proyectos e infraestructura tecnológica.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium mb-1.5" style="color: var(--text-secondary);">Correo electrónico</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 opacity-50" style="color: var(--text-secondary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input id="email" class="mreyze-input pl-10" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="tu@empresa.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium mb-1.5" style="color: var(--text-secondary);">Clave de acceso</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 opacity-50" style="color: var(--text-secondary);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" class="mreyze-input pl-10 pr-10" type="password" name="password" required autocomplete="current-password" placeholder="Tu contraseña" />
                
                <button type="button" class="absolute inset-y-0 right-3 flex items-center text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors" onclick="togglePassword('password', 'eye-icon-login')">
                    <svg id="eye-icon-login" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <script>
            function togglePassword(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
                } else {
                    input.type = 'password';
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                }
            }
        </script>

        <!-- Remember Me & Forgot -->
        <div class="flex items-center justify-between mb-8">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-600 focus:ring-offset-0 focus:ring-0 w-4 h-4" style="background-color: var(--surface-3); border-color: var(--border-default); color: var(--primary);" name="remember">
                <span class="ms-2 text-sm" style="color: var(--text-secondary);">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm hover:underline transition-colors" style="color: var(--primary);" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <div class="mb-6">
            <button type="submit" class="mreyze-btn mreyze-btn-primary w-full">
                Autenticar &rarr;
            </button>
        </div>
        
        <div class="relative flex py-4 items-center mb-6">
            <div class="flex-grow border-t border-[var(--border-default)]"></div>
            <span class="flex-shrink-0 mx-4 text-xs font-semibold uppercase text-[var(--text-muted)]">O accede con</span>
            <div class="flex-grow border-t border-[var(--border-default)]"></div>
        </div>

        <div>
            <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-4 w-full rounded-lg font-medium transition-all" style="background-color: var(--surface-3); border: 1px solid var(--border-default); color: var(--text-primary); padding: 0.85rem 1rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="text-sm">Continuar con Google</span>
            </a>
        </div>
        
        <div class="mt-8 text-center text-sm" style="color: var(--text-secondary);">
            ¿No tienes una cuenta? 
            <a href="{{ route('register') }}" class="hover:underline" style="color: var(--primary);">Regístrate aquí</a>
        </div>
    </form>
</x-guest-layout>
