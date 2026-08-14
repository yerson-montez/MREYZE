@extends('layouts.admin')

@section('title', 'Perfil corporativo — Admin')

@section('content')
<div class="max-w-3xl mx-auto mt-10 px-4">

    <h1 class="text-2xl font-semibold mb-1">Perfil corporativo</h1>
    <p class="text-sm text-gray-400 mb-6">Sube o actualiza el Perfil Corporativo que se muestra a los visitantes desde la sección «Nosotros».</p>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 rounded-lg px-4 py-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 rounded-lg px-4 py-3 mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Archivo actual --}}
    <div class="bg-white border rounded-xl p-5 mb-4">
        <h2 class="text-lg font-medium mb-3">Versión disponible para visitantes</h2>

        @if ($perfil && $perfil->ruta)
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $perfil->esPdf() ? '📄' : '🖼️' }}</span>
                    <div>
                        <div class="font-medium text-sm">{{ $perfil->nombre_original ?? 'Perfil corporativo' }}</div>
                        <div class="text-xs text-gray-400">
                            {{ $perfil->esPdf() ? 'PDF' : 'Imagen' }} · Actualizado el {{ $perfil->updated_at?->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                <a href="{{ $perfil->url() }}" target="_blank" rel="noopener noreferrer"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
                    Ver actual
                </a>
            </div>
        @else
            <p class="text-sm text-gray-400">Aún no hay un perfil corporativo subido. Los visitantes verán el mensaje de «próximamente».</p>
        @endif
    </div>

    {{-- Formulario de carga / reemplazo --}}
    <div class="bg-white border rounded-xl p-5">
        <h2 class="text-lg font-medium mb-1">
            {{ $perfil && $perfil->ruta ? 'Reemplazar perfil corporativo' : 'Subir perfil corporativo' }}
        </h2>
        <p class="text-xs text-gray-400 mb-4">Formatos: PDF, JPG, PNG · Máximo 10 MB. Al subir un archivo nuevo, el anterior se reemplaza automáticamente.</p>

        <form action="{{ route('admin.perfil-corporativo.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png"
                   class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg p-2 mb-4">

            <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                {{ $perfil && $perfil->ruta ? 'Guardar y reemplazar' : 'Subir perfil corporativo' }}
            </button>
        </form>
    </div>

</div>
@endsection
