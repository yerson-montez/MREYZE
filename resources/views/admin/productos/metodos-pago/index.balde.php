{{-- resources/views/admin/metodos-pago/index.blade.php --}}
@extends('admin.layout')
@section('title', 'Métodos de Pago — Admin')

@section('content')
<div class="admin-page">
    <div class="admin-page-header">
        <h1 class="admin-title">💳 Métodos de Pago</h1>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="metodos-grid">
        @foreach($metodos as $m)
        <div class="metodo-card {{ $m->activo ? 'activo' : 'inactivo' }}" id="metodo-{{ $m->id }}">

            {{-- Toggle activo/inactivo --}}
            <div class="metodo-header">
                <span class="metodo-emoji">{{ $m->emoji }}</span>
                <strong class="metodo-nombre">{{ $m->nombre }}</strong>
                <button class="toggle-btn {{ $m->activo ? 'on' : 'off' }}"
                        onclick="toggleMetodo({{ $m->id }}, this)"
                        title="{{ $m->activo ? 'Desactivar' : 'Activar' }}">
                    {{ $m->activo ? '✅ Activo' : '⬜ Inactivo' }}
                </button>
            </div>

            {{-- Formulario de datos --}}
            <form method="POST" action="{{ route('admin.metodos-pago.update', $m) }}" class="metodo-form">
                @csrf @method('PUT')
                <input type="hidden" name="activo" value="{{ $m->activo ? 1 : 0 }}" class="input-activo">

                @if(in_array($m->clave, ['yape','plin','efectivo','tarjeta']))
                    @if($m->clave !== 'efectivo' && $m->clave !== 'tarjeta')
                    <label>Número de celular</label>
                    <input type="text" name="datos[numero]"
                           value="{{ $m->datos['numero'] ?? '' }}"
                           class="form-input" placeholder="999 000 111">
                    <label>Alias / Nombre en app</label>
                    <input type="text" name="datos[alias]"
                           value="{{ $m->datos['alias'] ?? '' }}"
                           class="form-input" placeholder="MiEmpresa">
                    <label>Titular</label>
                    <input type="text" name="datos[titular]"
                           value="{{ $m->datos['titular'] ?? '' }}"
                           class="form-input" placeholder="Juan Pérez">
                    @endif
                @elseif($m->clave === 'paypal')
                    <label>Email / Alias PayPal</label>
                    <input type="text" name="datos[alias]"
                           value="{{ $m->datos['alias'] ?? '' }}"
                           class="form-input" placeholder="pagos@empresa.com">
                @else
                    {{-- BCP, Interbank, BBVA --}}
                    <label>Número de cuenta</label>
                    <input type="text" name="datos[numero]"
                           value="{{ $m->datos['numero'] ?? '' }}"
                           class="form-input" placeholder="191-12345678-0-12">
                    <label>CCI (código interbancario)</label>
                    <input type="text" name="datos[cci]"
                           value="{{ $m->datos['cci'] ?? '' }}"
                           class="form-input" placeholder="002-191-001234567890-12">
                    <label>Titular</label>
                    <input type="text" name="datos[titular]"
                           value="{{ $m->datos['titular'] ?? '' }}"
                           class="form-input" placeholder="Juan Pérez">
                @endif

                <button type="submit" class="btn-sm-primary" style="margin-top:.5rem">Guardar</button>
            </form>
        </div>
        @endforeach
    </div>
</div>

<script>
function toggleMetodo(id, btn) {
    fetch(`/admin/metodos-pago/${id}/toggle`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        const card = document.getElementById('metodo-' + id);
        const inputActivo = card.querySelector('.input-activo');
        if (data.activo) {
            btn.textContent = '✅ Activo';
            btn.className = 'toggle-btn on';
            card.className = 'metodo-card activo';
            inputActivo.value = 1;
        } else {
            btn.textContent = '⬜ Inactivo';
            btn.className = 'toggle-btn off';
            card.className = 'metodo-card inactivo';
            inputActivo.value = 0;
        }
    });
}
</script>
@endsection