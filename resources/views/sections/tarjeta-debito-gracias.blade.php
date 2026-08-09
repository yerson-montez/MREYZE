@extends('welcome')

@section('title', 'Solicitud enviada — MREYZE')
@section('meta_description', 'Tu solicitud de Tarjeta Débito MReyze ha sido registrada correctamente.')

@section('content')

<style>
    .tg-page {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5rem 1.5rem;
    }
    .tg-card {
        max-width: 640px;
        width: 100%;
        padding: 3rem 2.5rem;
        border-radius: 22px;
        text-align: center;
        background: rgba(13,31,60,0.7);
        border: 1px solid rgba(74,144,217,0.2);
        box-shadow: 0 30px 70px rgba(0,0,0,0.45), 0 0 110px rgba(0,212,255,0.08);
    }
    .tg-check {
        width: 84px;
        height: 84px;
        margin: 0 auto 1.6rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(16,185,129,0.18), rgba(16,185,129,0.05));
        border: 1px solid rgba(16,185,129,0.45);
        box-shadow: 0 0 0 8px rgba(16,185,129,0.07), 0 0 40px rgba(16,185,129,0.25);
        color: #34d399;
    }
    .tg-check svg { width: 40px; height: 40px; }
    .tg-kicker {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.7rem;
    }
    .tg-title {
        font-family: 'Exo 2', sans-serif;
        font-size: clamp(1.6rem, 3.4vw, 2.3rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin: 0 0 1rem;
    }
    .tg-title span {
        background: linear-gradient(90deg, #00d4ff, #7ab8f5);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .tg-text {
        font-size: 0.98rem;
        color: var(--ice-200);
        line-height: 1.7;
        margin: 0 0 1.6rem;
    }
    @if(session('solicitudId'))
    .tg-num {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        background: rgba(0,212,255,0.08);
        border: 1px dashed rgba(0,212,255,0.4);
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 1.8rem;
    }
    @endif
    .tg-cta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.9rem;
        justify-content: center;
        margin-top: 1.4rem;
    }
    .tg-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.85rem 1.7rem;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.25s;
        cursor: pointer;
    }
    .tg-btn-primary {
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        color: #fff;
        box-shadow: 0 8px 24px rgba(0,136,204,0.35);
    }
    .tg-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(0,136,204,0.5);
    }
    .tg-btn-ghost {
        background: rgba(74,144,217,0.06);
        border: 1px solid rgba(74,144,217,0.3);
        color: var(--ice-100);
    }
    .tg-btn-ghost:hover {
        background: rgba(74,144,217,0.14);
        color: #fff;
    }
    @media (max-width: 560px) {
        .tg-card { padding: 2.4rem 1.4rem; }
        .tg-cta { flex-direction: column; }
        .tg-btn { width: 100%; }
    }
</style>

<div class="tg-page">
    <div class="tg-card">
        <div class="tg-check">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        </div>

        <div class="tg-kicker">Solicitud registrada</div>
        <h1 class="tg-title">¡Gracias por <span>solicitar</span> tu Tarjeta Débito MReyze!</h1>

        <p class="tg-text">
            Hemos recibido tu solicitud correctamente. Nuestro equipo la revisará y te
            contactaremos al correo o celular registrado con el avance del proceso.
        </p>

        @if(session('solicitudId'))
            <div class="tg-num">N.º de solicitud: #{{ session('solicitudId') }}</div>
        @endif

        <div class="tg-cta">
            <a href="{{ route('tarjeta.debito') }}" class="tg-btn tg-btn-primary">Volver a la Tarjeta Débito</a>
            <a href="{{ url('/') }}" class="tg-btn tg-btn-ghost">Volver al inicio</a>
        </div>
    </div>
</div>

@endsection
