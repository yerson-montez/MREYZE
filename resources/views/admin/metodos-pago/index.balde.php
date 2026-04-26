@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">

    <h1 class="text-2xl font-semibold mb-6">Métodos de pago</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 rounded-lg px-4 py-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @foreach($metodos as $metodo)
    <div class="bg-white border rounded-xl p-5 mb-4 {{ $metodo->activo ? '' : 'opacity-60' }}">

        {{-- Cabecera con toggle --}}
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium">
                {{ $metodo->emoji }} {{ $metodo->nombre }}
            </h2>

            <label class="inline-flex items-center gap-2 cursor-pointer">
                <span class="text-sm text-gray-500">
                    {{ $metodo->activo ? 'Activo' : 'Inactivo' }}
                </span>
                <div class="relative">
                    <input type="checkbox"
                           class="sr-only toggle-activo"
                           data-id="{{ $metodo->id }}"
                           {{ $metodo->activo ? 'checked' : '' }}>
                    <div class="toggle-bg w-10 h-6 rounded-full border-2
                        {{ $metodo->activo ? 'bg-green-500 border-green-500' : 'bg-gray-200 border-gray-300' }}">
                        <div class="toggle-dot absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition
                            {{ $metodo->activo ? 'translate-x-4' : '' }}">
                        </div>
                    </div>
                </div>
            </label>
        </div>

        {{-- Formulario de edición --}}
        <form action="{{ route('admin.metodos-pago.update', $metodo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Nombre visible</label>
                    <input type="text" name="nombre" value="{{ $metodo->nombre }}"
                           class="w-full border rounded-lg p-2 text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Emoji</label>
                    <input type="text" name="emoji" value="{{ $metodo->emoji }}"
                           class="w-full border rounded-lg p-2 text-sm">
                </div>
            </div>

            {{-- Campos según tipo --}}
            @if(in_array($metodo->clave, ['yape','plin']))
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Número</label>
                        <input type="text" name="datos[numero]"
                               value="{{ $metodo->datos['numero'] ?? '' }}"
                               class="w-full border rounded-lg p-2 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Titular</label>
                        <input type="text" name="datos[titular]"
                               value="{{ $metodo->datos['titular'] ?? '' }}"
                               class="w-full border rounded-lg p-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">URL imagen QR (opcional)</label>
                    <input type="text" name="datos[qr]"
                           value="{{ $metodo->datos['qr'] ?? '' }}"
                           placeholder="https://... o ruta storage"
                           class="w-full border rounded-lg p-2 text-sm">
                </div>

            @elseif(in_array($metodo->clave, ['bcp','interbank','bbva','scotiabank']))
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">N° de cuenta</label>
                        <input type="text" name="datos[numero]"
                               value="{{ $metodo->datos['numero'] ?? '' }}"
                               class="w-full border rounded-lg p-2 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">CCI</label>
                        <input type="text" name="datos[cci]"
                               value="{{ $metodo->datos['cci'] ?? '' }}"
                               class="w-full border rounded-lg p-2 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Titular</label>
                        <input type="text" name="datos[titular]"
                               value="{{ $metodo->datos['titular'] ?? '' }}"
                               class="w-full border rounded-lg p-2 text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Alias (opcional)</label>
                        <input type="text" name="datos[alias]"
                               value="{{ $metodo->datos['alias'] ?? '' }}"
                               class="w-full border rounded-lg p-2 text-sm">
                    </div>
                </div>

            @elseif($metodo->clave === 'culqi')
                <p class="text-sm text-gray-400 mb-4">
                    Culqi se configura en el archivo <code>.env</code> con
                    <code>CULQI_PUBLIC_KEY</code> y <code>CULQI_SECRET_KEY</code>.
                </p>
            @endif

            <input type="hidden" name="activo" value="{{ $metodo->activo ? 1 : 0 }}">

            <button type="submit"
                    class="mt-2 bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                Guardar cambios
            </button>
        </form>
    </div>
    @endforeach
</div>

<script>
document.querySelectorAll('.toggle-activo').forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        const id = this.dataset.id;
        fetch(`/admin/metodos-pago/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            // Recarga para reflejar el estado visual
            location.reload();
        });
    });
});
</script>
@endsection