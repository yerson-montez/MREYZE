<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="bg-gray-800 text-white p-4">
        <h1 class="font-bold">Panel Admin</h1>
    </nav>

    <!-- CONTENIDO -->
    <main class="p-6">
        {{ $slot }} {{-- 🔥 ESTO ES LO MÁS IMPORTANTE --}}
    </main>

</body>
</html>