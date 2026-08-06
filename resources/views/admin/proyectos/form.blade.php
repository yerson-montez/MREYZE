@extends('layouts.admin')

@section('title', isset($proyecto) ? 'Editar proyecto' : 'Nuevo proyecto')

@push('styles')
<style>
.adm-form-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(4, 9, 17, 0.25);
}
.adm-form-group { margin-bottom: 1.4rem; }
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
.adm-input-hint { font-size: .75rem; color: var(--muted); margin-top: .3rem; }
.adm-error { color: #ef4444; font-size: .78rem; margin-top: .3rem; }
.adm-divider { border: none; border-top: 1px solid var(--border); margin: 1.8rem 0; }
.adm-section-label {
    font-size: .72rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; color: var(--accent);
    margin-bottom: 1.2rem; margin-top: 0.5rem;
}
.adm-form-actions {
    display: flex; justify-content: flex-end; gap: .8rem; margin-top: 1.8rem;
}
.adm-img-preview {
    display: block; margin-top: 10px; width: 80px; height: 80px;
    object-fit: cover; border-radius: 10px; border: 1px solid var(--border);
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 0.75rem;
    margin-top: 0.75rem;
}
.gallery-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    aspect-ratio: 1;
    cursor: pointer;
    transition: border-color .2s, box-shadow .2s;
}
.gallery-item:hover { border-color: rgba(0,212,255,.4); }
.gallery-item.principal {
    border-color: var(--accent);
    box-shadow: 0 0 12px rgba(0,212,255,.25);
}
.gallery-item img {
    width: 100%; height: 100%; object-fit: cover; display: block;
}
.gallery-item-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(transparent 40%, rgba(0,0,0,.7));
    opacity: 0; transition: opacity .2s;
    display: flex; align-items: flex-end; justify-content: center;
    padding: 6px; gap: 4px;
}
.gallery-item:hover .gallery-item-overlay { opacity: 1; }
.gallery-btn {
    width: 26px; height: 26px; border-radius: 6px;
    border: none; cursor: pointer; display: flex;
    align-items: center; justify-content: center;
    font-size: 12px; transition: all .15s;
}
.gallery-btn-star { background: rgba(251,191,36,.9); color: #000; }
.gallery-btn-star:hover { background: #fbbf24; transform: scale(1.1); }
.gallery-btn-star.active { background: #fbbf24; }
.gallery-btn-del { background: rgba(244,63,94,.9); color: #fff; }
.gallery-btn-del:hover { background: #f43f5e; transform: scale(1.1); }
.gallery-principal-badge {
    position: absolute; top: 4px; left: 4px;
    font-size: .55rem; font-weight: 700; padding: 2px 6px;
    border-radius: 4px; background: var(--accent); color: #000;
    letter-spacing: .05em; text-transform: uppercase;
    opacity: 0; transition: opacity .2s;
}
.gallery-item.principal .gallery-principal-badge { opacity: 1; }

.dropzone {
    border: 2px dashed var(--border);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all .25s;
    background: rgba(0,212,255,.02);
}
.dropzone:hover, .dropzone.dragover {
    border-color: rgba(0,212,255,.5);
    background: rgba(0,212,255,.06);
}
.dropzone-icon { font-size: 2rem; margin-bottom: .5rem; }
.dropzone-text { font-size: .85rem; color: var(--muted); }
.dropzone-text strong { color: var(--accent); }
.dropzone-hint { font-size: .72rem; color: var(--muted); margin-top: .4rem; }

.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 0.5rem;
    margin-top: 0.75rem;
}
.preview-item {
    position: relative; border-radius: 8px; overflow: hidden;
    aspect-ratio: 1; border: 1px solid var(--border);
}
.preview-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.preview-item-remove {
    position: absolute; top: 3px; right: 3px;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(244,63,94,.9); color: #fff;
    border: none; cursor: pointer; font-size: 11px;
    display: flex; align-items: center; justify-content: center;
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
    <a href="{{ route('admin.proyectos.index') }}" class="adm-breadcrumb-link">Proyectos</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">{{ isset($proyecto) ? 'Editar' : 'Nuevo' }}</span>
@endsection

@section('content')

    <div style="max-width: 800px; margin: 0 auto;">
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">
                    @isset($proyecto)
                        Editar <span>Proyecto</span>
                    @else
                        Nuevo <span>Proyecto</span>
                    @endisset
                </h1>
                <p class="adm-page-sub">Completa los campos para configurar el proyecto.</p>
            </div>
            <a href="{{ route('admin.proyectos.index') }}" class="adm-btn adm-btn-ghost">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
                Volver
            </a>
        </div>

        <form
            method="POST"
            action="{{ isset($proyecto)
                ? route('admin.proyectos.update', $proyecto)
                : route('admin.proyectos.store') }}"
            enctype="multipart/form-data"
            id="proyectoForm"
        >
            @csrf
            @isset($proyecto) @method('PUT') @endisset

            <div class="adm-form-card">

                {{-- INFORMACIÓN BÁSICA --}}
                <div class="adm-section-label">Información básica</div>

                <div class="adm-form-group">
                    <label class="adm-label" for="titulo">Título del proyecto *</label>
                    <input id="titulo" name="titulo" type="text" class="adm-input"
                           value="{{ old('titulo', $proyecto->titulo ?? '') }}"
                           placeholder="Ej: Sistema de riego automatizado"
                           oninput="autoSlug(this.value)"
                           required>
                    @error('titulo')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                <div class="adm-form-group">
                    <label class="adm-label" for="slug">Slug (URL) *</label>
                    <input id="slug" name="slug" type="text" class="adm-input"
                           value="{{ old('slug', $proyecto->slug ?? '') }}"
                           placeholder="se-genera-automaticamente"
                           required>
                    @error('slug')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                <div class="adm-form-group">
                    <label class="adm-label" for="descripcion_corta">Descripción corta</label>
                    <textarea id="descripcion_corta" name="descripcion_corta" class="adm-textarea"
                              rows="2"
                              placeholder="Resumen breve del proyecto...">{{ old('descripcion_corta', $proyecto->descripcion_corta ?? '') }}</textarea>
                    @error('descripcion_corta')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                <div class="adm-form-group">
                    <label class="adm-label" for="descripcion_completa">Descripción completa</label>
                    <textarea id="descripcion_completa" name="descripcion_completa" class="adm-textarea"
                              rows="8"
                              placeholder="Descripción detallada del proyecto, alcance, resultados...">{{ old('descripcion_completa', $proyecto->descripcion_completa ?? '') }}</textarea>
                    @error('descripcion_completa')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                {{-- CLASIFICACIÓN --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Clasificación</div>

                <div class="adm-form-row">
                    <div class="adm-form-group">
                        <label class="adm-label" for="categoria">Categoría *</label>
                        <select id="categoria" name="categoria" class="adm-select-field" required>
                            <option value="">Seleccionar...</option>
                            @foreach(['automatizacion' => 'Automatización', 'iot' => 'IoT / Electrónica', 'software' => 'Software', 'otro' => 'Otro'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('categoria', $proyecto->categoria ?? '') === $val ? 'selected' : '' }}>
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
                            @foreach(['en_curso' => 'En curso', 'completado' => 'Completado', 'proximamente' => 'Próximamente'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('estado', $proyecto->estado ?? '') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('estado')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="adm-form-group" style="max-width:280px">
                    <label class="adm-label" for="tipo">Tipo *</label>
                    <select id="tipo" name="tipo" class="adm-select-field" required>
                        <option value="portafolio" {{ old('tipo', $proyecto->tipo ?? '') === 'portafolio' ? 'selected' : '' }}>Portafolio</option>
                        <option value="inversion"  {{ old('tipo', $proyecto->tipo ?? '') === 'inversion'  ? 'selected' : '' }}>Inversión</option>
                    </select>
                    @error('tipo')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                {{-- FECHAS --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Fechas</div>

                <div class="adm-form-row">
                    <div class="adm-form-group">
                        <label class="adm-label" for="fecha_inicio">Fecha de inicio</label>
                        <input id="fecha_inicio" name="fecha_inicio" type="date" class="adm-input"
                               value="{{ old('fecha_inicio', isset($proyecto) && $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('Y-m-d') : '') }}">
                        @error('fecha_inicio')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label" for="fecha_fin">Fecha de fin</label>
                        <input id="fecha_fin" name="fecha_fin" type="date" class="adm-input"
                               value="{{ old('fecha_fin', isset($proyecto) && $proyecto->fecha_fin ? $proyecto->fecha_fin->format('Y-m-d') : '') }}">
                        @error('fecha_fin')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- CLIENTE Y TECNOLOGÍAS --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Cliente y tecnologías</div>

                <div class="adm-form-group">
                    <label class="adm-label" for="cliente">Cliente</label>
                    <input id="cliente" name="cliente" type="text" class="adm-input"
                           value="{{ old('cliente', $proyecto->cliente ?? '') }}"
                           placeholder="Nombre del cliente o empresa">
                    @error('cliente')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                <div class="adm-form-group">
                    <label class="adm-label" for="tecnologias">Tecnologías</label>
                    <input id="tecnologias" name="tecnologias" type="text" class="adm-input"
                           value="{{ old('tecnologias', $proyecto->tecnologias ?? '') }}"
                           placeholder="Ej: Arduino, Laravel, MQTT">
                    <div class="adm-input-hint">Separar con comas. Ej: Arduino, React, Node.js</div>
                    @error('tecnologias')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                {{-- IMAGEN PRINCIPAL --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Imagen principal</div>

                <div class="adm-form-group">
                    <label class="adm-label">Subir imagen</label>
                    <input type="file" name="imagen_principal" class="adm-input"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewImagen(event)">
                    @if(isset($proyecto) && $proyecto->imagen_principal_url)
                        <img id="previewImg"
                             src="{{ $proyecto->imagen_principal_url }}"
                             class="adm-img-preview">
                    @else
                        <img id="previewImg" class="adm-img-preview" style="display:none;">
                    @endif
                    <div class="adm-input-hint">Formatos: JPG, PNG, WEBP · Máx 5 MB</div>
                    @error('imagen_principal')<div class="adm-error">{{ $message }}</div>@enderror
                </div>

                {{-- GALERÍA DE IMÁGENES --}}
                @if(isset($proyecto))
                <hr class="adm-divider">
                <div class="adm-section-label">Galería de imágenes</div>

                @if($proyecto->imagenes->count() > 0)
                <div class="gallery-grid" id="galleryGrid">
                    @foreach($proyecto->imagenes->sortBy('orden') as $imagen)
                    <div class="gallery-item {{ $imagen->es_principal ? 'principal' : '' }}"
                         data-id="{{ $imagen->id }}" draggable="true">
                        <img src="{{ asset('storage/' . $imagen->ruta) }}" alt="Imagen {{ $imagen->orden }}">
                        <div class="gallery-principal-badge">Principal</div>
                        <div class="gallery-item-overlay">
                            <button type="button" class="gallery-btn gallery-btn-star {{ $imagen->es_principal ? 'active' : '' }}"
                                    onclick="setPrincipal({{ $imagen->id }})" title="Marcar como principal">★</button>
                            <button type="button" class="gallery-btn gallery-btn-del"
                                    onclick="eliminarImagen({{ $imagen->id }}, this)" title="Eliminar">✕</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="adm-input-hint">No hay imágenes en la galería aún.</p>
                @endif
                @endif

                <hr class="adm-divider">
                <div class="adm-section-label">Agregar imágenes a la galería</div>

                <div class="dropzone" id="dropzone">
                    <div class="dropzone-icon">📁</div>
                    <div class="dropzone-text">
                        Arrastra imágenes aquí o <strong>haz clic para seleccionar</strong>
                    </div>
                    <div class="dropzone-hint">Máx 10 imágenes · JPG, PNG, WEBP · 5 MB c/u</div>
                    <input type="file" name="nuevas_imagenes[]" id="fileInput"
                           multiple accept="image/jpeg,image/png,image/webp"
                           style="display:none">
                </div>
                <div class="preview-grid" id="previewGrid"></div>
                @error('nuevas_imagenes')<div class="adm-error">{{ $message }}</div>@enderror
                @error('nuevas_imagenes.*')<div class="adm-error">{{ $message }}</div>@enderror

                {{-- ORDEN --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Orden y visibilidad</div>

                <div class="adm-form-row">
                    <div class="adm-form-group" style="max-width:200px">
                        <label class="adm-label" for="orden">Orden de aparición</label>
                        <input id="orden" name="orden" type="number" min="0" class="adm-input"
                               value="{{ old('orden', $proyecto->orden ?? 0) }}">
                        @error('orden')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="adm-form-group" style="display:flex;align-items:flex-end">
                        <div style="display:flex;align-items:center;gap:.8rem">
                            <input type="hidden" name="visible" value="0">
                            <input id="visible" name="visible" type="checkbox" value="1"
                                   {{ old('visible', $proyecto->visible ?? true) ? 'checked' : '' }}
                                   style="width:18px;height:18px;accent-color:var(--accent);cursor:pointer">
                            <label for="visible" style="cursor:pointer;font-size:.9rem">
                                Mostrar este proyecto públicamente
                            </label>
                        </div>
                    </div>
                </div>

                {{-- CAMPOS DE INVERSIÓN --}}
                <div id="invFields">
                    <hr class="adm-divider">
                    <div class="adm-section-label">Datos de inversión</div>

                    <div class="adm-form-row">
                        <div class="adm-form-group">
                            <label class="adm-label" for="inversion_monto_total">Monto total (S/)</label>
                            <input id="inversion_monto_total" name="inversion_monto_total" type="number" step="0.01" min="0"
                                   class="adm-input"
                                   value="{{ old('inversion_monto_total', $proyecto->inversion_monto_total ?? '') }}"
                                   placeholder="0.00">
                            @error('inversion_monto_total')<div class="adm-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="adm-form-group">
                            <label class="adm-label" for="inversion_monto_disponible">Monto disponible (S/)</label>
                            <input id="inversion_monto_disponible" name="inversion_monto_disponible" type="number" step="0.01" min="0"
                                   class="adm-input"
                                   value="{{ old('inversion_monto_disponible', $proyecto->inversion_monto_disponible ?? '') }}"
                                   placeholder="0.00">
                            @error('inversion_monto_disponible')<div class="adm-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="adm-form-row">
                        <div class="adm-form-group">
                            <label class="adm-label" for="inversion_porcentaje_financiado">Porcentaje financiado (%)</label>
                            <input id="inversion_porcentaje_financiado" name="inversion_porcentaje_financiado" type="number" min="0" max="100"
                                   class="adm-input"
                                   value="{{ old('inversion_porcentaje_financiado', $proyecto->inversion_porcentaje_financiado ?? '') }}"
                                   placeholder="0">
                            @error('inversion_porcentaje_financiado')<div class="adm-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="adm-form-group">
                            <label class="adm-label" for="inversion_roi">Retorno estimado (%)</label>
                            <input id="inversion_roi" name="inversion_roi" type="text" class="adm-input"
                                   value="{{ old('inversion_roi', $proyecto->inversion_roi ?? '') }}"
                                   placeholder="Ej: 30%">
                            @error('inversion_roi')<div class="adm-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="adm-form-row">
                        <div class="adm-form-group">
                            <label class="adm-label" for="inversion_tiempo_estimado">Tiempo de ejecución (meses)</label>
                            <input id="inversion_tiempo_estimado" name="inversion_tiempo_estimado" type="number" min="1"
                                   class="adm-input"
                                   value="{{ old('inversion_tiempo_estimado', $proyecto->inversion_tiempo_estimado ?? '') }}"
                                   placeholder="Ej: 12">
                            @error('inversion_tiempo_estimado')<div class="adm-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="adm-form-group">
                            <label class="adm-label" for="inversion_estado">Estado del proyecto *</label>
                            <select id="inversion_estado" name="inversion_estado" class="adm-select-field">
                                <option value="">Seleccionar...</option>
                                @foreach(\App\Models\Proyecto::INVERSION_ESTADOS as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('inversion_estado', $proyecto->inversion_estado ?? '') === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('inversion_estado')<div class="adm-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="adm-form-row" id="retornoFinalRow">
                        <div class="adm-form-group" style="max-width:350px">
                            <label class="adm-label" for="inversion_retorno_final">Retorno final (%)</label>
                            <input id="inversion_retorno_final" name="inversion_retorno_final" type="number" step="0.01" min="0"
                                   class="adm-input"
                                   value="{{ old('inversion_retorno_final', $proyecto->inversion_retorno_final ?? '') }}"
                                   placeholder="Ej: 30">
                            <div class="adm-input-hint">Porcentaje de retorno entregado al finalizar el proyecto.</div>
                            @error('inversion_retorno_final')<div class="adm-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="adm-form-group" style="max-width:350px">
                        <label class="adm-label" for="inversion_etiqueta">Etiqueta de inversión</label>
                        <input id="inversion_etiqueta" name="inversion_etiqueta" type="text" class="adm-input"
                               value="{{ old('inversion_etiqueta', $proyecto->inversion_etiqueta ?? '') }}"
                               placeholder="Ej: Oportunidad limitada">
                        <div class="adm-input-hint">Texto corto que se mostrará como etiqueta destacada.</div>
                        @error('inversion_etiqueta')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- WHATSAPP --}}
                <hr class="adm-divider">
                <div class="adm-section-label">Configuración WhatsApp</div>

                <div class="adm-form-row">
                    <div class="adm-form-group">
                        <label class="adm-label" for="whatsapp_numero">Número de WhatsApp</label>
                        <input id="whatsapp_numero" name="whatsapp_numero" type="text" class="adm-input"
                               value="{{ old('whatsapp_numero', $proyecto->whatsapp_numero ?? '') }}"
                               placeholder="Ej: 51999888777">
                        @error('whatsapp_numero')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label" for="whatsapp_mensaje">Mensaje predeterminado</label>
                        <input id="whatsapp_mensaje" name="whatsapp_mensaje" type="text" class="adm-input"
                               value="{{ old('whatsapp_mensaje', $proyecto->whatsapp_mensaje ?? '') }}"
                               placeholder="Mensaje automático al abrir WhatsApp">
                        @error('whatsapp_mensaje')<div class="adm-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="adm-form-actions">
                    <a href="{{ route('admin.proyectos.index') }}" class="adm-btn adm-btn-ghost">
                        Cancelar
                    </a>
                    <button type="submit" class="adm-btn adm-btn-primary">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:16px;height:16px">
                            <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                        </svg>
                        @isset($proyecto) Guardar cambios @else Crear proyecto @endisset
                    </button>
                </div>

            </div>
        </form>
    </div>

    @isset($proyecto)
    <div style="max-width: 800px; margin: 2rem auto 0;">
        <div class="adm-form-card" style="background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 1.6rem 1.7rem;">
            <div class="adm-section-label" style="font-size:.78rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.08em;margin-bottom:1.2rem;">
                📄 Contrato de inversión (PDF)
            </div>

            @if($proyecto->contrato_url)
                <div class="adm-alert adm-alert-success" style="margin-bottom:1.2rem;">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    Contrato cargado. <a href="{{ $proyecto->contrato_url }}" target="_blank" style="color:var(--accent);font-weight:700;">Ver contrato</a>
                </div>
            @else
                <p style="font-size:.82rem;color:var(--muted);margin-bottom:1.2rem;">Aún no hay contrato para este proyecto. Sube el PDF que los inversionistas podrán consultar.</p>
            @endif

            <form method="POST" action="{{ route('admin.proyectos.contrato', $proyecto) }}" enctype="multipart/form-data" style="display:flex; gap:.75rem; flex-wrap:wrap; align-items:center;">
                @csrf
                <input type="file" name="contrato_pdf" accept="application/pdf" required class="adm-input" style="flex:1; min-width:200px;">
                <button type="submit" class="adm-btn adm-btn-primary">
                    {{ $proyecto->contrato_pdf ? 'Reemplazar contrato' : 'Subir contrato' }}
                </button>
                @if($proyecto->contrato_pdf)
                <button type="submit" form="eliminarContratoForm" class="adm-btn adm-btn-danger">Eliminar</button>
                @endif
            </form>
            <form id="eliminarContratoForm" method="POST" action="{{ route('admin.proyectos.contrato.eliminar', $proyecto) }}" onsubmit="return confirm('¿Eliminar el contrato del proyecto?')">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>
    @endisset

<script>
// ── Auto-generate slug from titulo ──
function autoSlug(val) {
    const slug = document.getElementById('slug');
    const titulo = document.getElementById('titulo');
    if (slug && titulo && !slug.dataset.manual) {
        slug.value = val.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
}
document.getElementById('slug')?.addEventListener('input', function() {
    this.dataset.manual = '1';
});

// ── Preview imagen principal ──
function previewImagen(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('previewImg');
    if (!preview) return;
    if (file) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else {
        preview.src = ''; preview.style.display = 'none';
    }
}

// ── Galería: drag & drop + preview de nuevas imágenes ──
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
const previewGrid = document.getElementById('previewGrid');
let pendingFiles = [];

if (dropzone) {
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragover'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
    dropzone.addEventListener('drop', e => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        addFiles(e.dataTransfer.files);
    });
}

if (fileInput) {
    fileInput.addEventListener('change', e => { addFiles(e.target.files); e.target.value = ''; });
}

function addFiles(fileList) {
    for (const file of fileList) {
        if (pendingFiles.length >= 10) break;
        if (!file.type.match(/^image\/(jpeg|png|webp)$/)) continue;
        if (file.size > 5 * 1024 * 1024) continue;
        pendingFiles.push(file);
    }
    renderPreviews();
}

function renderPreviews() {
    if (!previewGrid) return;
    previewGrid.innerHTML = '';
    pendingFiles.forEach((file, idx) => {
        const div = document.createElement('div');
        div.className = 'preview-item';
        const reader = new FileReader();
        reader.onload = e => {
            div.innerHTML = `<img src="${e.target.result}" alt="">` +
                `<button type="button" class="preview-item-remove" onclick="removePending(${idx})">✕</button>`;
        };
        reader.readAsDataURL(file);
        previewGrid.appendChild(div);
    });
}

function removePending(idx) {
    pendingFiles.splice(idx, 1);
    renderPreviews();
}

document.getElementById('proyectoForm').addEventListener('submit', function(e) {
    if (pendingFiles.length === 0) return;
    const dt = new DataTransfer();
    pendingFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
});

// ── Galería: marcar principal ──
function setPrincipal(id) {
    fetch(`/admin/proyectos/imagenes/${id}/principal`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    }).then(r => { if (r.ok) location.reload(); });
}

// ── Galería: eliminar imagen ──
function eliminarImagen(id, btn) {
    if (!confirm('¿Eliminar esta imagen?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/proyectos/imagenes/${id}`;
    form.innerHTML = '@csrf @method('DELETE')';
    document.body.appendChild(form);
    form.submit();
}

// ── Reordenar por drag ──
const galleryGrid = document.getElementById('galleryGrid');
if (galleryGrid) {
    let draggedItem = null;
    galleryGrid.addEventListener('dragstart', e => {
        draggedItem = e.target.closest('.gallery-item');
        if (draggedItem) draggedItem.style.opacity = '0.4';
    });
    galleryGrid.addEventListener('dragend', e => {
        if (draggedItem) draggedItem.style.opacity = '1';
        draggedItem = null;
    });
    galleryGrid.addEventListener('dragover', e => { e.preventDefault(); });
    galleryGrid.addEventListener('drop', e => {
        e.preventDefault();
        const target = e.target.closest('.gallery-item');
        if (target && draggedItem && target !== draggedItem) {
            const allItems = [...galleryGrid.children];
            const fromIdx = allItems.indexOf(draggedItem);
            const toIdx = allItems.indexOf(target);
            if (fromIdx < toIdx) {
                galleryGrid.insertBefore(draggedItem, target.nextSibling);
            } else {
                galleryGrid.insertBefore(draggedItem, target);
            }
            updateOrder();
        }
    });
}

function updateOrder() {
    const items = document.querySelectorAll('#galleryGrid .gallery-item');
    items.forEach((item, idx) => {
        const id = item.dataset.id;
        let input = document.querySelector(`input[name="orden_imagenes[${id}]"]`);
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = `orden_imagenes[${id}]`;
            document.getElementById('proyectoForm').appendChild(input);
        }
        input.value = idx;
    });
}
</script>

<script>
// ── Toggle sección de inversión (sin Alpine) ──
(function () {
    const tipoSel = document.getElementById('tipo');
    const invFields = document.getElementById('invFields');
    const estadoInvSel = document.getElementById('inversion_estado');
    const retornoFinalRow = document.getElementById('retornoFinalRow');

    function syncRetornoFinal() {
        if (!retornoFinalRow) return;
        retornoFinalRow.style.display =
            tipoSel && estadoInvSel &&
            tipoSel.value === 'inversion' && estadoInvSel.value === 'completado'
                ? '' : 'none';
    }

    function syncInvFields() {
        if (invFields) {
            invFields.style.display = tipoSel && tipoSel.value === 'inversion' ? '' : 'none';
        }
        syncRetornoFinal();
    }

    if (tipoSel) tipoSel.addEventListener('change', syncInvFields);
    if (estadoInvSel) estadoInvSel.addEventListener('change', syncRetornoFinal);
    syncInvFields();
})();
</script>
@endsection
