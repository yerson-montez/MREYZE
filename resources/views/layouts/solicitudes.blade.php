<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Solicitudes')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
    :root {
        --sol-bg:      #07111e;
        --sol-surface: #0d1f33;
        --sol-card:    #0f2540;
        --sol-border:  rgba(0,212,255,0.13);
        --sol-accent:  #00d4ff;
        --sol-text:    #e2f0fb;
        --sol-muted:   #7a9ab8;
    }

    body {
        margin: 0;
        font-family: 'Exo 2', 'Segoe UI', sans-serif;
        background: var(--sol-bg);
    }

    .sol-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .sol-card {
        background: var(--sol-card);
        border: 1px solid var(--sol-border);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        width: 100%;
        max-width: 520px;
        box-shadow: 0 24px 64px rgba(0,0,0,.5);
    }

    .sol-title {
        text-align: center;
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--sol-text);
    }

    .sol-input {
        width: 100%;
        background: var(--sol-surface);
        border: 1px solid var(--sol-border);
        border-radius: 10px;
        color: var(--sol-text);
        padding: .75rem 1rem;
        margin-top: .3rem;
    }

    .sol-btn {
        width: 100%;
        background: var(--sol-accent);
        border: none;
        border-radius: 12px;
        padding: .9rem;
        font-weight: bold;
        cursor: pointer;
        margin-top: 1rem;
    }

    .sol-error {
        color: #f43f5e;
        font-size: .8rem;
    }
    </style>

</head>
<body>

    <div class="sol-page">
        @yield('content')
    </div>

</body>
</html>