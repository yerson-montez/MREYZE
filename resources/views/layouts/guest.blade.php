<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body.mreyze-auth-page::after {
            content: '';
            position: fixed;
            z-index: 0;
            right: 0;
            bottom: 0;
            left: 0;
            height: 34vh;
            pointer-events: none;
            opacity: .72;
            background-position: center bottom;
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-mask-image: linear-gradient(to bottom, transparent, #000 42%);
            mask-image: linear-gradient(to bottom, transparent, #000 42%);
        }
        body.mreyze-auth-login::after { background-image: url('/img/fondo1.png'); }
        body.mreyze-auth-register::after { background-image: url('/img/fondo2.png'); }

        .mreyze-auth-shell {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: minmax(0, .9fr) minmax(22rem, 28rem);
            gap: clamp(2rem, 7vw, 7rem);
            width: min(100% - 2rem, 76rem);
            margin-inline: auto;
            padding-block: clamp(2rem, 7vw, 5rem);
        }

        .mreyze-auth-shell::before {
            content: 'MREYZE\A Automatización · IoT · Software\A\A Tecnología para operaciones reales.';
            white-space: pre-wrap;
            display: flex;
            align-items: center;
            min-height: 28rem;
            padding: clamp(1.5rem, 4vw, 3rem);
            border: 1px solid rgba(125, 226, 255, .16);
            border-radius: 1.25rem;
            background:
                radial-gradient(circle at 75% 20%, rgba(20, 216, 255, .16), transparent 35%),
                linear-gradient(145deg, rgba(10, 35, 54, .95), rgba(7, 19, 31, .98));
            color: #bcecff;
            font-family: 'Exo 2', sans-serif;
            font-size: clamp(1.4rem, 3vw, 2.6rem);
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -.04em;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .18);
        }

        .mreyze-auth-shell > div:first-of-type {
            position: absolute;
            top: 1.5rem;
            left: max(1rem, calc((100% - 76rem) / 2));
            margin: 0;
            text-align: left;
        }

        .mreyze-auth-shell > div:last-of-type {
            width: 100%;
            max-width: none;
            padding: clamp(1.25rem, 3vw, 2rem);
            border-radius: 1rem;
            background: rgba(9, 25, 40, .92);
            border-color: rgba(130, 190, 240, .16);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .24);
        }

        .mreyze-auth-brand { font-size: 1.35rem; }
        .mreyze-auth-shell input { min-height: 48px; font-size: 1rem; }
        .mreyze-auth-shell button { min-height: 48px; }

        @media (max-width: 760px) {
            body.mreyze-auth-page::after {
                height: 31vh;
                opacity: .58;
                -webkit-mask-image: linear-gradient(to bottom, transparent, #000 56%);
                mask-image: linear-gradient(to bottom, transparent, #000 56%);
            }
            .mreyze-auth-shell {
                display: block;
                width: min(100% - 1.25rem, 32rem);
                padding-top: 4.5rem;
            }
            .mreyze-auth-shell::before {
                display: none;
            }
            body.mreyze-auth-login .mreyze-auth-shell > div:last-of-type::before,
            body.mreyze-auth-register .mreyze-auth-shell > div:last-of-type::before {
                display: block;
                margin-bottom: 1rem;
                color: #f5f8fc;
                font-family: 'Exo 2', sans-serif;
                font-size: 1.45rem;
                font-weight: 700;
                letter-spacing: -.03em;
            }
            body.mreyze-auth-login .mreyze-auth-shell > div:last-of-type::before { content: 'Bienvenido de vuelta'; }
            body.mreyze-auth-register .mreyze-auth-shell > div:last-of-type::before { content: 'Crea tu cuenta'; }
            .mreyze-auth-shell > div:first-of-type {
                top: 1rem;
                left: 1rem;
            }
            .mreyze-auth-shell > div:last-of-type { padding: 1.15rem; }
        }
    </style>
</head>
<body class="mreyze-ui font-sans antialiased mreyze-auth-page @if(request()->routeIs('register')) mreyze-auth-register @else mreyze-auth-login @endif">

    <!-- Glows decorativos -->
    <div class="mreyze-auth-glow fixed inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-cyan-500/20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-[400px] h-[400px] rounded-full bg-cyan-400/10 blur-3xl"></div>
    </div>

    <div class="mreyze-auth-shell relative min-h-screen flex flex-col items-center justify-center px-4">

        <!-- Título MREYZE -->
        <div class="mb-8 text-center">
            <h1 class="mreyze-auth-brand">
                MREYZE
            </h1>
            <p class="mreyze-auth-caption">
                Automatización &amp; Sistemas
            </p>
        </div>

        <!-- Card -->
        <div class="mreyze-card w-full max-w-md p-6 rounded-2xl shadow-2xl">
            {{ $slot }}
        </div>

    </div>

</body>
</html>
