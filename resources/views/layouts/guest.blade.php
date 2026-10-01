<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MREYZE') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="mreyze-ui min-h-screen flex items-center justify-center p-4 md:p-8" style="background-color: var(--background);">

    <div class="w-full max-w-5xl rounded-[var(--radius-xl)] overflow-hidden border shadow-[var(--shadow)] flex flex-col md:flex-row" style="background-color: var(--surface); border-color: var(--border);">
        
        <!-- Left: Form Area -->
        <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center">
            
            <!-- Brand -->
            <a href="/" class="flex items-center gap-3 mb-10 hover:opacity-90 transition-opacity w-fit">
                <img src="{{ asset('img/LOGOMREYZE1.png') }}" alt="MREYZE" style="height: 48px; width: auto; object-fit: contain;">
                <div class="flex flex-col justify-center">
                    <div class="font-heading font-bold text-xl leading-tight" style="color: var(--text-primary);">MREYZE</div>
                    <div class="text-[10px] font-semibold tracking-wider uppercase" style="color: var(--text-muted);">Automatización y sistemas</div>
                </div>
            </a>

            <!-- Slot content (Login/Register Form) -->
            {{ $slot }}
            
        </div>
        
        <!-- Right: Image Area -->
        <div class="hidden md:flex w-1/2 relative flex-col justify-end p-12 lg:p-16 bg-cover bg-center" style="background-image: url('{{ request()->routeIs('register') ? '/img/fondo2.png' : '/img/fondo1.png' }}');">
            <!-- Gradient overlay for text readability -->
            <div class="absolute inset-0 opacity-90" style="background: linear-gradient(to top, var(--background) 0%, transparent 100%);"></div>
            
            <div class="relative z-10">
                <h2 class="text-3xl lg:text-4xl font-heading font-bold mb-4 leading-tight" style="color: var(--text-primary);">
                    Tecnología<br>para un mundo<br>más productivo.
                </h2>
                <div class="w-12 h-1 rounded-full mb-6" style="background: var(--primary);"></div>
                <p class="text-sm font-medium tracking-wide" style="color: var(--text-secondary);">
                    Automatización &nbsp;•&nbsp; IoT &nbsp;•&nbsp; Software &nbsp;•&nbsp; Resultados reales
                </p>
            </div>
        </div>

    </div>

</body>
</html>
