@extends('layouts.app')
@section('title', 'Admin · Solicitudes')
@section('content')  {{-- 👈 FALTA ESTO --}}
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
.sol-page {
    min-height: 100vh;
    background: var(--sol-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    font-family: 'Exo 2', 'Segoe UI', sans-serif;
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
.sol-icon {
    width: 64px; height: 64px;
    background: rgba(0,212,255,.1);
    border: 1px solid rgba(0,212,255,.25);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 28px;
}
.sol-title {
    text-align: center;
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--sol-text);
    margin-bottom: .5rem;
}
.sol-subtitle {
    text-align: center;
    color: var(--sol-muted);
    font-size: .9rem;
    margin-bottom: 2rem;
    line-height: 1.6;
}
.sol-benefits {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
    margin-bottom: 2rem;
}
.sol-benefit {
    background: rgba(0,212,255,.06);
    border: 1px solid rgba(0,212,255,.12);
    border-radius: 12px;
    padding: .9rem;
    display: flex;
    align-items: center;
    gap: .6rem;
    font-size: .82rem;
    color: var(--sol-text);
}
.sol-benefit-icon { font-size: 20px; flex-shrink: 0; }
.sol-label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--sol-muted);
    margin-bottom: .4rem;
    letter-spacing: .05em;
    text-transform: uppercase;
}
.sol-input {
    width: 100%;
    background: var(--sol-surface);
    border: 1px solid var(--sol-border);
    border-radius: 10px;
    color: var(--sol-text);
    padding: .75rem 1rem;
    font-size: .9rem;
    font-family: inherit;
    outline: none;
    transition: border-color .2s;
    box-sizing: border-box;
}
.sol-input:focus { border-color: rgba(0,212,255,.5); }
.sol-input::placeholder { color: var(--sol-muted); }
.sol-input.error { border-color: rgba(244,63,94,.5); }
.sol-error { color: #f43f5e; font-size: .75rem; margin-top: .35rem; }
.sol-field { margin-bottom: 1.1rem; }
.sol-btn {
    width: 100%;
    background: var(--sol-accent);
    color: #07111e;
    border: none;
    border-radius: 12px;
    padding: .9rem;
    font-size: 1rem;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: all .2s;
    margin-top: .5rem;
}
.sol-btn:hover { background: #00bce0; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,212,255,.25); }
.sol-divider {
    border: none;
    border-top: 1px solid var(--sol-border);
    margin: 1.5rem 0;
}
.sol-alert-success {
    background: rgba(16,185,129,.1);
    border: 1px solid rgba(16,185,129,.3);
    color: #10b981;
    border-radius: 10px;
    padding: .8rem 1rem;
    font-size: .86rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}
</style>

<div class="sol-page">
    <div class="sol-card">

        <div class="sol-icon">🤝</div>
        <h1 class="sol-title">Únete como Colaborador</h1>
        <p class="sol-subtitle">
            Genera ingresos compartiendo nuestros productos.<br>
            Gana comisión por cada venta que refieras.
        </p>

        {{-- Beneficios --}}
        <div class="sol-benefits">
            <div class="sol-benefit">
                <span class="sol-benefit-icon">💰</span>
                <span>Comisión por cada venta</span>
            </div>
            <div class="sol-benefit">
                <span class="sol-benefit-icon">🔗</span>
                <span>Enlace personalizado</span>
            </div>
            <div class="sol-benefit">
                <span class="sol-benefit-icon">📊</span>
                <span>Sin límite de ganancias</span>
            </div>
            <div class="sol-benefit">
                <span class="sol-benefit-icon">🚀</span>
                <span>Registro 100% gratis</span>
            </div>
        </div>

        <hr class="sol-divider">

        @if(session('success'))
        <div class="sol-alert-success">
            ✓ {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('colaboradores.solicitud.store') }}" method="POST">
            @csrf

            <div class="sol-field">
                <label class="sol-label">Nombre completo *</label>
                <input type="text" name="nombre"
                       value="{{ old('nombre') }}"
                       placeholder="Ej: María García"
                       class="sol-input {{ $errors->has('nombre') ? 'error' : '' }}"
                       required>
                @error('nombre')
                    <p class="sol-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sol-field">
                <label class="sol-label">Correo electrónico *</label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="tu@email.com"
                       class="sol-input {{ $errors->has('email') ? 'error' : '' }}"
                       required>
                @error('email')
                    <p class="sol-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sol-field">
                <label class="sol-label">Número de teléfono *</label>
                <input type="tel" name="telefono"
                       value="{{ old('telefono') }}"
                       placeholder="Ej: 999 888 777"
                       class="sol-input {{ $errors->has('telefono') ? 'error' : '' }}"
                       required>
                @error('telefono')
                    <p class="sol-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sol-field">
                <label class="sol-label">¿Por qué quieres ser colaborador? (opcional)</label>
                <textarea name="mensaje_solicitud"
                          rows="3"
                          placeholder="Cuéntanos cómo planeas promocionar nuestros productos..."
                          class="sol-input {{ $errors->has('mensaje_solicitud') ? 'error' : '' }}"
                          style="resize:vertical">{{ old('mensaje_solicitud') }}</textarea>
                @error('mensaje_solicitud')
                    <p class="sol-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="sol-btn">
                Enviar solicitud →
            </button>
        </form>

    </div>
</div>
@endsection