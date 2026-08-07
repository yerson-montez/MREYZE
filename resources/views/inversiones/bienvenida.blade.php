@extends('welcome')

@section('title', 'Bienvenido — MREYZE')

@section('content')

<style>
    .bienvenida {
        max-width: 960px;
        margin: 0 auto;
        padding: 6.5rem 1.5rem 5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .bienvenida-img {
        width: 100%;
        max-width: 860px;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(0,212,255,.25);
        box-shadow: 0 20px 60px rgba(0,60,160,.35);
        background: rgba(13,31,60,.6);
    }

    .bienvenida-img img {
        display: block;
        width: 100%;
        height: auto;
        object-fit: contain;
    }

    .bienvenida-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 2.2rem;
        padding: 1.05rem 2.6rem;
        border-radius: 12px;
        cursor: pointer;
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: .02em;
        text-decoration: none;
        background: linear-gradient(135deg, #0088cc, #00a8e8);
        box-shadow: 0 8px 26px rgba(0,136,204,.4);
        transition: all .25s;
    }

    .bienvenida-btn:hover {
        background: linear-gradient(135deg, #0099dd, #00bbff);
        transform: translateY(-2px);
        box-shadow: 0 12px 34px rgba(0,136,204,.55);
    }
</style>

<div class="bienvenida">
    <div class="bienvenida-img">
        <img src="{{ asset('img/bienvenida-mreyze.svg') }}" alt="Bienvenido a MreyZe">
    </div>

    <a href="{{ route('inversiones.form', $proyecto) }}" class="bienvenida-btn">
        CONTINUAR CON LA INVERSIÓN
    </a>
</div>

@endsection
