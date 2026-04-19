@extends('layouts.admin')
@section('title', 'Admin · Solicitudes')
@section('content')  
<style>
.grac-page {
    min-height: 100vh;
    background: #07111e;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    font-family: 'Exo 2', 'Segoe UI', sans-serif;
}
.grac-card {
    background: #0f2540;
    border: 1px solid rgba(0,212,255,0.13);
    border-radius: 20px;
    padding: 3rem 2rem;
    width: 100%;
    max-width: 480px;
    text-align: center;
    box-shadow: 0 24px 64px rgba(0,0,0,.5);
}
.grac-check {
    width: 72px; height: 72px;
    background: rgba(16,185,129,.12);
    border: 2px solid rgba(16,185,129,.3);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 32px;
}
.grac-title { font-size: 1.7rem; font-weight: 800; color: #e2f0fb; margin-bottom: .75rem; }
.grac-text  { color: #7a9ab8; font-size: .93rem; line-height: 1.7; margin-bottom: 2rem; }
.grac-steps {
    background: rgba(0,212,255,.04);
    border: 1px solid rgba(0,212,255,.1);
    border-radius: 14px;
    padding: 1.25rem;
    text-align: left;
    margin-bottom: 2rem;
}
.grac-step {
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    margin-bottom: .85rem;
    font-size: .85rem;
    color: #e2f0fb;
}
.grac-step:last-child { margin-bottom: 0; }
.grac-step-num {
    width: 24px; height: 24px;
    background: rgba(0,212,255,.15);
    border: 1px solid rgba(0,212,255,.3);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem;
    font-weight: 700;
    color: #00d4ff;
    flex-shrink: 0;
    margin-top: 1px;
}
.grac-btn {
    display: inline-block;
    background: #00d4ff;
    color: #07111e;
    border-radius: 12px;
    padding: .8rem 2rem;
    font-weight: 700;
    font-size: .95rem;
    text-decoration: none;
    transition: all .2s;
    font-family: inherit;
}
.grac-btn:hover { background: #00bce0; transform: translateY(-1px); }
</style>

<div class="grac-page">
    <div class="grac-card">

        <div class="grac-check">✓</div>

        <h1 class="grac-title">¡Solicitud enviada!</h1>
        <p class="grac-text">
            Recibimos tu solicitud correctamente.<br>
            Revisaremos tu información y te contactaremos pronto.
        </p>

        <div class="grac-steps">
            <div class="grac-step">
                <span class="grac-step-num">1</span>
                <span>Tu solicitud fue registrada y está en revisión.</span>
            </div>
            <div class="grac-step">
                <span class="grac-step-num">2</span>
                <span>Recibirás una respuesta en menos de 48 horas.</span>
            </div>
            <div class="grac-step">
                <span class="grac-step-num">3</span>
                <span>Al aprobarte, recibirás tu enlace personalizado para comenzar a ganar.</span>
            </div>
        </div>

        <a href="{{ url('/') }}" class="grac-btn">Volver a la tienda</a>

    </div>
</div>
@endsection