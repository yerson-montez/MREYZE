<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" style="background: #0a0e1a; font-family: 'Inter', sans-serif;">

    <!-- Glows decorativos -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-cyan-500/20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-[400px] h-[400px] rounded-full bg-cyan-400/10 blur-3xl"></div>
    </div>

    <div class="relative min-h-screen flex flex-col items-center justify-center px-4">

        <!-- Título MREYZE -->
        <div class="mb-8 text-center">
            <h1 style="font-family: 'Exo 2', sans-serif; font-weight: 700; font-size: 1.75rem; letter-spacing: 0.02em; color: #fff;">
                MREYZE
            </h1>
            <p style="font-size: 0.75rem; color: #9ca3af; letter-spacing: 0.15em; text-transform: uppercase; margin-top: 2px;">
                Automatización &amp; Sistemas
            </p>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md p-6 rounded-2xl shadow-2xl" style="background: #0f1729; border: 1px solid rgba(34, 211, 238, 0.1);">
            {{ $slot }}
        </div>

    </div>

</body>
</html>