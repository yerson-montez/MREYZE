<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTech — @yield('title', 'Automatización & Software')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    @include('navbar')   {{-- o como se llame tu archivo de nav --}}

    <main>
        @yield('content')
    </main>

    {{-- @include('layouts.footer')    o como se llame tu footer --}}

    @stack('scripts')
</body>
</html>