<x-admin-layout>
    <div class="max-w-lg mx-auto p-6">

        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.colaboradores.show', $colaborador) }}"
               class="text-sm text-gray-400 hover:text-gray-600">← Volver</a>
            <h1 class="text-2xl font-semibold text-gray-900">Editar colaborador</h1>
        </div>

        <form action="{{ route('admin.colaboradores.update', $colaborador) }}" method="POST"
              class="bg-white rounded-xl border p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                <input type="text" name="nombre" value="{{ old('nombre', $colaborador->nombre) }}"
                    class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500
                           @error('nombre') border-red-400 @enderror"
                    required>
                @error('nombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email (opcional)</label>
                <input type="email" name="email" value="{{ old('email', $colaborador->email) }}"
                    class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500
                           @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono (opcional)</label>
                <input type="tel" name="telefono" value="{{ old('telefono', $colaborador->telefono) }}"
                    class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Porcentaje de comisión (%)</label>
                <input type="number" name="comision_porcentaje"
                    value="{{ old('comision_porcentaje', $colaborador->comision_porcentaje) }}"
                    min="0" max="100" step="0.5"
                    class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500
                           @error('comision_porcentaje') border-red-400 @enderror"
                    required>
                @error('comision_porcentaje')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" value="1" id="activo"
                    {{ $colaborador->activo ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                <label for="activo" class="text-sm font-medium text-gray-700">Colaborador activo</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.colaboradores.show', $colaborador) }}"
                   class="px-6 py-2.5 rounded-lg text-sm text-gray-600 border hover:bg-gray-50 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>