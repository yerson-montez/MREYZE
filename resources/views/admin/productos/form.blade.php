@extends('layouts.admin')

@section('title', isset($producto) ? 'Editar producto' : 'Nuevo producto')

@push('styles')
<style>
:root {
    --adm-bg:      #07111e;
    --adm-surface: #0d1f33;
    --adm-card:    #0f2540;
    --adm-border:  rgba(0,212,255,0.13);
    --adm-accent:  #00d4ff;
    --adm-text:    #e2f0fb;
    --adm-muted:   #7a9ab8;
    --adm-danger:  #f43f5e;
    --adm-font:    'Exo 2', sans-serif;
}
.adm-wrapper { min-height:100vh; background:var(--adm-bg); font-family:var(--adm-font); color:var(--adm-text); }

.adm-topbar {
    background: var(--adm-surface);
    border-bottom: 1px solid var(--adm-border);
    padding: 1rem 2rem;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50;
}
.adm-topbar-brand { display:flex;align-items:center;gap:.75rem;font-size:1.05rem;font-weight:700;color:var(--adm-accent); }
.adm-back { display:inline-flex;align-items:center;gap:.4rem;color:var(--adm-muted);font-size:.85rem;text-decoration:none;
    padding:.4rem .9rem;border:1px solid var(--adm-border);border-radius:8px;transition:all .18s; }
.adm-back:hover { color:var(--adm-accent); border-color:var(--adm-accent); }

.adm-main { max-width:780px; margin:0 auto; padding:2.5rem 1.5rem; }

.adm-form-title { font-size:1.55rem; font-weight:800; margin-bottom:2rem; }
.adm-form-title span { color:var(--adm-accent); }

.adm-form-card {
    background: var(--adm-card);
    border: 1px solid var(--adm-border);
    border-radius: 18px;
    padding: 2rem;
}

.adm-form-group { margin-bottom: 1.4rem; }
.adm-form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
.adm-label {
    display: block; font-size: .8rem; font-weight:700;
    letter-spacing:.06em; text-transform:uppercase;
    color: var(--adm-muted); margin-bottom:.45rem;
}
.adm-input, .adm-textarea, .adm-select-field {
    width: 100%; box-sizing: border-box;
    background: rgba(0,212,255,.04);
    border: 1px solid var(--adm-border);
    border-radius: 10px;
    color: var(--adm-text);
    padding: .65rem 1rem;
    font-family: var(--adm-font);
    font-size: .9rem;
    outline: none;
    transition: border-color .18s;
}
.adm-input:focus, .adm-textarea:focus, .adm-select-field:focus {
    border-color: rgba(0,212,255,.55);
}
.adm-textarea { resize: vertical; min-height: 90px; }
.adm-select-field { cursor: pointer; }
.adm-input-hint { font-size:.75rem; color:var(--adm-muted); margin-top:.3rem; }

.adm-error { color: var(--adm-danger); font-size:.78rem; margin-top:.3rem; }

.adm-divider {
    border: none; border-top: 1px solid var(--adm-border);
    margin: 1.8rem 0;
}

.adm-section-label {
    font-size:.72rem; font-weight:700; letter-spacing:.1em;
    text-transform:uppercase; color:var(--adm-accent);
    margin-bottom:1rem; margin-top:1.6rem;
}

.adm-form-actions {
    display: flex; justify-content: flex-end; gap: .8rem; margin-top: 1.8rem;
}
.adm-btn { display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1.4rem;border-radius:10px;
    font-family:var(--adm-font);font-size:.9rem;font-weight:600;cursor:pointer;border:none;
    text-decoration:none;transition:all .18s; }
.adm-btn-primary { background:var(--adm-accent); color:#07111e; }
.adm-btn-primary:hover { background:#00bce0; transform:translateY(-1px); }
.adm-btn-ghost { background:transparent;color:var(--adm-muted);border:1px solid var(--adm-border); }
.adm-btn-ghost:hover { border-color:var(--adm-accent); color:var(--adm-accent); }

/* Preview emoji */
.adm-emoji-preview {
    display:inline-flex; align-items:center; justify-content:center;
    width:52px; height:52px;
    border-radius:14px;
    font-size:1.8rem;
    border:1px solid var(--adm-border);
    background:rgba(0,212,255,.06);
    margin-bottom:.5rem;
    transition:background .2s;
}

@media(max-width:600px) {
    .adm-form-row { grid-template-columns:1fr; }
    .adm-topbar { padding:.8rem 1rem; }
}
</style>
@endpush

@section('content')
<div class="adm-wrapper">

    {{-- Top Bar --}}
    <div class="adm-topbar">
        <div class="adm-topbar-brand">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:20px;height:20px">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            Panel Admin
        </div>
        <a href="{{ route('admin.productos.index') }}" class="adm-back">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
            Volver
        </a>
    </div>

    <div class="adm-main">
        <h1 class="adm-form-title">
            @isset($producto)
                Editar <span>producto</span>
            @else
                Nuevo <span>producto</span>
            @endisset
        </h1>

        <div class="adm-form-card">
            <form
                method="POST"
                action="{{ isset($producto)
                    ? route('admin.productos.update', $producto)
                    : route('admin.productos.store') }}"
            >
                @csrf
                @isset($producto) @method('PUT') @endisset

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
                    <label class="adm-label" for="descripcion">Descripción *</label>
                    <textarea id="descripcion" name="descripcion" class="adm-textarea"
                              placeholder="Describe brevemente el producto..."
                              required>{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
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
                        <label class="adm-label" for="precio_anterior">Precio anterior (S/) <span style="opacity:.5">(opcional)</span></label>
                        <input id="precio_anterior" name="precio_anterior" type="number" step="0.01" min="0"
                               class="adm-input"
                               value="{{ old('precio_anterior', $producto->precio_anterior ?? '') }}"
                               placeholder="Dejar vacío si no hay descuento">
                        <div class="adm-input-hint">Si se llena, se mostrará la etiqueta de descuento.</div>
                        @error('precio_anterior')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- CATEGORÍA, ESTADO, STOCK --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Clasificación y stock</div>

                <div class="adm-form-row">
                    <div class="adm-form-group">
                        <label class="adm-label" for="categoria">Categoría *</label>
                        <select id="categoria" name="categoria" class="adm-select-field" required>
                            <option value="">Seleccionar...</option>
                            @foreach(['iot' => 'IoT / Electrónica', 'software' => 'Software', 'kits' => 'Kits'] as $val => $label)
                            <option value="{{ $val }}" {{ old('categoria', $producto->categoria ?? '') === $val ? 'selected' : '' }}>
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
                            <option value="{{ $val }}" {{ old('estado', $producto->estado ?? '') === $val ? 'selected' : '' }}>
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

                {{-- ÍCONO --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Ícono / Apariencia</div>

                <div class="adm-form-row">
                    <div class="adm-form-group">
                        <label class="adm-label" for="icono_emoji">Emoji del producto</label>
                        <div class="adm-emoji-preview" id="emojiPreview">
                            {{ old('icono_emoji', $producto->icono_emoji ?? '📦') }}
                        </div>
                        <input id="icono_emoji" name="icono_emoji" type="text" class="adm-input"
                               value="{{ old('icono_emoji', $producto->icono_emoji ?? '📦') }}"
                               placeholder="📡"
                               oninput="document.getElementById('emojiPreview').textContent=this.value||'📦'"
                               maxlength="4">
                        <div class="adm-input-hint">Pega un emoji (📡 💻 ⚙️ 🔌 ...)</div>
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label" for="icono_color">Color de fondo del ícono</label>
                        <input id="icono_color" name="icono_color" type="text" class="adm-input"
                               value="{{ old('icono_color', $producto->icono_color ?? 'rgba(0,212,255,0.12)') }}"
                               placeholder="rgba(0,212,255,0.12)">
                        <div class="adm-input-hint">Formato CSS rgba()</div>
                    </div>
                </div>

                {{-- ACTIVO --}}
                <hr class="adm-divider">
                <div style="display:flex;align-items:center;gap:.8rem">
                    <input type="hidden" name="activo" value="0">
                    <input id="activo" name="activo" type="checkbox" value="1"
                           {{ old('activo', $producto->activo ?? true) ? 'checked' : '' }}
                           style="width:18px;height:18px;accent-color:var(--adm-accent);cursor:pointer">
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

            </form>
        </div>
    </div>
</div>
@endsection
