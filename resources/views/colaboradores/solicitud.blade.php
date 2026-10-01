@extends('layouts.app')
@section('title', 'Únete como Colaborador — MREYZE')
@section('content')

<div class="mreyze-container" style="min-height: calc(100vh - 70px); display: flex; align-items: center; justify-content: center; padding: 3rem 1rem;">
    <div class="mreyze-card" style="width: 100%; max-width: 520px;">
        
        <div style="width: 64px; height: 64px; background: rgba(0,212,255,.1); border: 1px solid rgba(0,212,255,.25); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 28px;">
            🤝
        </div>
        
        <h1 class="mreyze-heading-2" style="text-align: center; margin-bottom: 0.5rem; font-size: 1.8rem;">Únete como Colaborador</h1>
        <p class="mreyze-copy" style="text-align: center; margin-bottom: 2rem; max-width: 100%;">
            Genera ingresos compartiendo nuestros productos.<br>
            Gana comisión por cada venta que refieras.
        </p>

        {{-- Beneficios --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 2rem;">
            <div style="background: rgba(0,212,255,.06); border: 1px solid rgba(0,212,255,.12); border-radius: 12px; padding: 0.9rem; display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem; color: var(--text-primary);">
                <span style="font-size: 20px; flex-shrink: 0;">💰</span>
                <span>Comisión por cada venta</span>
            </div>
            <div style="background: rgba(0,212,255,.06); border: 1px solid rgba(0,212,255,.12); border-radius: 12px; padding: 0.9rem; display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem; color: var(--text-primary);">
                <span style="font-size: 20px; flex-shrink: 0;">🔗</span>
                <span>Enlace personalizado</span>
            </div>
            <div style="background: rgba(0,212,255,.06); border: 1px solid rgba(0,212,255,.12); border-radius: 12px; padding: 0.9rem; display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem; color: var(--text-primary);">
                <span style="font-size: 20px; flex-shrink: 0;">📊</span>
                <span>Sin límite de ganancias</span>
            </div>
            <div style="background: rgba(0,212,255,.06); border: 1px solid rgba(0,212,255,.12); border-radius: 12px; padding: 0.9rem; display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem; color: var(--text-primary);">
                <span style="font-size: 20px; flex-shrink: 0;">🚀</span>
                <span>Registro 100% gratis</span>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid var(--border-hover); margin: 1.5rem 0;">

        @if(session('success'))
        <div style="background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3); color: #10b981; border-radius: 10px; padding: 0.8rem 1rem; font-size: 0.86rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            ✓ {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('colaboradores.solicitud.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.2rem;">
                <label class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Nombre completo *</label>
                <input type="text" name="nombre"
                       value="{{ old('nombre') }}"
                       placeholder="Ej: María García"
                       class="mreyze-input"
                       style="width: 100%;"
                       aria-invalid="{{ $errors->has('nombre') ? 'true' : 'false' }}"
                       required>
                @error('nombre')
                    <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Correo electrónico *</label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="tu@email.com"
                       class="mreyze-input"
                       style="width: 100%;"
                       aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                       required>
                @error('email')
                    <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">Número de teléfono *</label>
                <input type="tel" name="telefono"
                       value="{{ old('telefono') }}"
                       placeholder="Ej: 999 888 777"
                       class="mreyze-input"
                       style="width: 100%;"
                       aria-invalid="{{ $errors->has('telefono') ? 'true' : 'false' }}"
                       required>
                @error('telefono')
                    <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="mreyze-label" style="display: block; margin-bottom: 0.4rem;">¿Por qué quieres ser colaborador? (opcional)</label>
                <textarea name="mensaje_solicitud"
                          rows="3"
                          placeholder="Cuéntanos cómo planeas promocionar nuestros productos..."
                          class="mreyze-input"
                          style="width: 100%; resize: vertical;"
                          aria-invalid="{{ $errors->has('mensaje_solicitud') ? 'true' : 'false' }}">{{ old('mensaje_solicitud') }}</textarea>
                @error('mensaje_solicitud')
                    <div class="mreyze-error" style="margin-top: 0.4rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="mreyze-btn" style="width: 100%; justify-content: center;">
                Enviar solicitud →
            </button>
        </form>

    </div>
</div>
@endsection