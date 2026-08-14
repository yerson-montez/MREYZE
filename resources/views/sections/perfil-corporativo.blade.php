@extends('welcome')

@section('title', 'Perfil Corporativo — MREYZE')

@section('content')

<style>
    /* ═══════════════════════════════════════
       PERFIL CORPORATIVO
    ═══════════════════════════════════════ */
    .pc {
        padding: 6rem 2rem;
        background: var(--ice-900);
        position: relative;
        overflow: hidden;
        min-height: calc(100vh - 70px);
    }

    .pc::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .pc-container {
        max-width: 960px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .pc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .pc-title {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(1.5rem, 3vw, 2.2rem);
        font-weight: 700;
        color: #fff;
        letter-spacing: -0.02em;
    }

    .pc-title span {
        background: linear-gradient(135deg, var(--ice-200), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .pc-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ice-200);
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        background: rgba(37, 99, 199, 0.18);
        border: 1px solid rgba(74, 144, 217, 0.3);
        transition: all 0.22s;
    }

    .pc-back:hover {
        color: #fff;
        background: rgba(37, 99, 199, 0.32);
        border-color: rgba(74, 144, 217, 0.6);
    }

    .pc-card {
        background: rgba(13, 31, 60, 0.5);
        border: 1px solid rgba(74, 144, 217, 0.15);
        border-radius: 14px;
        padding: 1.5rem;
    }

    .pc-pdf {
        width: 100%;
        height: 80vh;
        border: 1px solid rgba(74, 144, 217, 0.25);
        border-radius: 10px;
        background: #fff;
    }

    .pc-img {
        display: block;
        max-width: 100%;
        height: auto;
        margin: 0 auto;
        border-radius: 10px;
        border: 1px solid rgba(74, 144, 217, 0.25);
    }

    .pc-download {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 1.25rem;
        padding: 0.65rem 1.3rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--ice-500), var(--ice-400));
        border: 1px solid rgba(74, 144, 217, 0.4);
        text-decoration: none;
        transition: all 0.25s;
    }

    .pc-download:hover {
        background: linear-gradient(135deg, var(--ice-400), var(--glow));
        transform: translateY(-1px);
    }

    .pc-empty {
        text-align: center;
        padding: 4rem 1rem;
        color: var(--ice-200);
    }

    .pc-empty svg {
        width: 56px;
        height: 56px;
        color: var(--ice-400);
        margin-bottom: 1rem;
        opacity: 0.7;
    }

    .pc-empty p {
        font-size: 1rem;
        line-height: 1.7;
        max-width: 420px;
        margin: 0 auto;
    }
</style>

<section class="pc">
    <div class="pc-container">

        <div class="pc-header">
            <h1 class="pc-title">Perfil <span>Corporativo</span></h1>
            <a href="{{ route('nosotros') }}" class="pc-back">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px;"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
                Volver a Nosotros
            </a>
        </div>

        <div class="pc-card">
            @if ($perfil && $perfil->ruta)
                @if ($perfil->esPdf())
                    <iframe class="pc-pdf" src="{{ $perfil->url() }}" title="Perfil Corporativo MReyze"></iframe>
                @else
                    <img class="pc-img" src="{{ $perfil->url() }}" alt="Perfil Corporativo MReyze">
                @endif

                <div style="display:flex;justify-content:center;">
                    <a class="pc-download" href="{{ $perfil->url() }}" download="{{ $perfil->nombre_original ?: 'perfil-corporativo-mreyze' }}">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px;"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                        Descargar Perfil Corporativo
                    </a>
                </div>
            @else
                <div class="pc-empty">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    <p>El Perfil Corporativo de MReyze estará disponible próximamente.</p>
                </div>
            @endif
        </div>

    </div>
</section>

@endsection
