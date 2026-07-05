@extends('layouts.admin')

@section('title', isset($producto) ? 'Editar producto' : 'Nuevo producto')

@push('styles')
<style>
/* ── Estilos específicos del formulario ── */

.adm-form-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(4, 9, 17, 0.25);
}

.adm-form-group { 
    margin-bottom: 1.4rem; 
}

.adm-form-row { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 1.25rem; 
}

.adm-select-field {
    width: 100%;
    background: rgba(0, 212, 255, 0.03);
    border: 1px solid var(--border);
    border-radius: 10px;
    color: var(--text);
    padding: .65rem 1rem;
    font-family: inherit;
    font-size: .9rem;
    outline: none;
    transition: border-color .18s, box-shadow .18s;
    cursor: pointer;
    box-sizing: border-box;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%237a9ab8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1.25rem;
    padding-right: 2.5rem;
}
.adm-select-field:focus {
    border-color: rgba(0, 212, 255, 0.5);
    box-shadow: 0 0 10px rgba(0, 212, 255, 0.15);
}

.adm-input-hint { 
    font-size: .75rem; 
    color: var(--muted); 
    margin-top: .3rem; 
}

.adm-error { 
    color: var(--dash-red, #ef4444); 
    font-size: .78rem; 
    margin-top: .3rem; 
}

.adm-divider { 
    border: none; 
    border-top: 1px solid var(--border); 
    margin: 1.8rem 0; 
}

.adm-section-label {
    font-size: .72rem; 
    font-weight: 700; 
    letter-spacing: .1em;
    text-transform: uppercase; 
    color: var(--accent);
    margin-bottom: 1.2rem; 
    margin-top: 0.5rem;
}

.adm-form-actions {
    display: flex; 
    justify-content: flex-end; 
    gap: .8rem; 
    margin-top: 1.8rem;
}

.adm-img-preview {
    display: block;
    margin-top: 10px;
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--border);
}

@media(max-width: 600px) {
    .adm-form-row { grid-template-columns: 1fr; }
    .adm-form-card { padding: 1.25rem; }
}
</style>
@endpush

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="adm-breadcrumb-link">Inicio</a>
    <span class="adm-breadcrumb-sep">/</span>
    <a href="{{ route('admin.productos.index') }}" class="adm-breadcrumb-link">Productos</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">{{ isset($producto) ? 'Editar' : 'Nuevo' }}</span>
@endsection

@section('content')

    <div style="max-width: 800px; margin: 0 auto;">
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">
                    @isset($producto)
                        Editar <span>Producto</span>
                    @else
                        Nuevo <span>Producto</span>
                    @endisset
                </h1>
                <p class="adm-page-sub">Completa los campos para configurar el catálogo.</p>
            </div>
            <a href="{{ route('admin.productos.index') }}" class="adm-btn adm-btn-ghost">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Volver
            </a>
        </div>

        {{-- Formulario --}}
        <form
            method="POST"
            action="{{ isset($producto)
                ? route('admin.productos.update', $producto)
                : route('admin.productos.store') }}"
            enctype="multipart/form-data"
        >
            @csrf
            @isset($producto) @method('PUT') @endisset

            <div class="adm-form-card">

                {{-- INFORMACIÓN BÁSICA --}}
                <div class="adm-section-label">Información básica</div>

                <div class="adm-form-group">
                    <label class="adm-label" for="nombre">Nombre del producto *</label>
                    <input id="nombre" name="nombre" type="text" class="adm-input"
                           value="{{ old('nombre', $producto->nombre ?? '') }}"
                           placeholder="Ej: Kit ESP32 Pro + Sensores" required>
                    @error('nombre')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                <div class="adm-form-group">
                    <label class="adm-label" for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="adm-textarea"
                              placeholder="Describe brevemente el producto...">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
                    @error('descripcion')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                {{-- PRECIOS --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Precios</div>

                <div class="adm-form-row">
                    <div class="adm-form-group">
                        <label class="adm-label" for="precio">Precio actual (S/) *</label>
                        <input id="precio" name="precio" type="number" step="0.01" min="0"
                               class="adm-input"
                               value="{{ old('precio', $producto->precio ?? '') }}"
                               placeholder="0.00" required>
                        @error('precio')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label" for="precio_anterior">
                            Precio anterior (S/) <span style="opacity:.5">(opcional)</span>
                        </label>
                        <input id="precio_anterior" name="precio_anterior" type="number" step="0.01" min="0"
                               class="adm-input"
                               value="{{ old('precio_anterior', $producto->precio_anterior ?? '') }}"
                               placeholder="Dejar vacío si no hay descuento">
                        <div class="adm-input-hint">Si se llena, se mostrará la etiqueta de descuento.</div>
                        @error('precio_anterior')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- CLASIFICACIÓN Y STOCK --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Clasificación y stock</div>

                <div class="adm-form-row">
                    <div class="adm-form-group">
                        <label class="adm-label" for="categoria">Categoría *</label>
                        <select id="categoria" name="categoria" class="adm-select-field" required>
                            <option value="">Seleccionar...</option>
                            @foreach(['iot' => 'IoT / Electrónica', 'software' => 'Software', 'kits' => 'Kits'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('categoria', $producto->categoria ?? '') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('categoria')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label" for="estado">Estado *</label>
                        <select id="estado" name="estado" class="adm-select-field" required>
                            <option value="">Seleccionar...</option>
                            @foreach(['nuevo' => 'Nuevo', 'disponible' => 'Disponible', 'agotado' => 'Agotado'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('estado', $producto->estado ?? '') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('estado')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="adm-form-group" style="max-width:200px">
                    <label class="adm-label" for="stock">Cantidad en stock *</label>
                    <input id="stock" name="stock" type="number" min="0" class="adm-input"
                           value="{{ old('stock', $producto->stock ?? 0) }}" required>
                    @error('stock')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                {{-- IMAGEN --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Imagen / Apariencia</div>

                <div class="adm-form-row">
                    <div class="adm-form-group">
                        <label class="adm-label">Imagen del producto (opcional)</label>
                        <input type="file" name="icono_imagen" class="adm-input"
                               accept="image/jpeg,image/png,image/webp"
                               onchange="previewImagen(event)">

                        {{-- Preview --}}
                        @if(isset($producto) && $producto->icono_imagen)
                            <img id="previewImg"
                                 src="{{ asset('storage/' . $producto->icono_imagen) }}"
                                 class="adm-img-preview">
                        @else
                            <img id="previewImg" class="adm-img-preview" style="display:none;">
                        @endif

                        <div class="adm-input-hint">Formatos: JPG, PNG, WEBP · Máx 2 MB</div>
                        @error('icono_imagen')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="adm-form-group">
                        <label class="adm-label">Color de fondo del ícono</label>
                        <input id="icono_color" name="icono_color" type="text" class="adm-input"
                               value="{{ old('icono_color', $producto->icono_color ?? 'rgba(0,212,255,0.12)') }}">
                        @error('icono_color')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- ACTIVO --}}
                <hr class="adm-divider">
                <div style="display:flex;align-items:center;gap:.8rem">
                    <input type="hidden" name="activo" value="0">
                    <input id="activo" name="activo" type="checkbox" value="1"
                           {{ old('activo', $producto->activo ?? true) ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--accent);cursor:pointer">
                    <label for="activo" style="cursor:pointer;font-size:.9rem">
                        Mostrar este producto en la tienda
                    </label>
                </div>

                {{-- BOTONES --}}
                <div class="adm-form-actions">
                    <a href="{{ route('admin.productos.index') }}" class="adm-btn adm-btn-ghost">
                        Cancelar
                    </a>
                    <button type="submit" class="adm-btn adm-btn-primary">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px">
                            <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                        </svg>
                        @isset($producto) Guardar cambios @else Crear producto @endisset
                    </button>
                </div>

            </div>
        </form>
    </div>

<script>
function previewImagen(event) {
    const file  = event.target.files[0];
    const preview = document.getElementById('previewImg');
    if (!preview) return;

    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}
</script>
@endsection