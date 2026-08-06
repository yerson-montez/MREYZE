@extends('layouts.admin')

@section('title', 'Admin · Proyectos de Inversión')

@push('styles')
<style>
.adm-pinv-img {
    width: 46px; height: 46px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--border);
    display: block;
    background: rgba(0,212,255,.05);
}
.adm-pinv-emoji-fallback {
    width: 46px; height: 46px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: rgba(0,212,255,.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.adm-pinv-name {
    font-weight: 700;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.adm-pinv-slug {
    font-size: .7rem;
    color: var(--muted);
    margin-top: .1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.adm-pinv-retorno {
    font-weight: 700;
    color: #4ade80;
}
.adm-pinv-disponible    { background: rgba(34,197,94,.12);  color: #4ade80; border: 1px solid rgba(34,197,94,.3); }
.adm-pinv-en_ejecucion  { background: rgba(251,191,36,.12);  color: #fbbf24; border: 1px solid rgba(251,191,36,.3); }
.adm-pinv-completado    { background: rgba(0,212,255,.12);   color: #00d4ff; border: 1px solid rgba(0,212,255,.3); }
</style>
@endpush

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="adm-breadcrumb-link">Inicio</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">Proyectos de Inversión</span>
@endsection

@section('content')

    @if(session('exito'))
        <div class="adm-alert adm-alert-success">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            <span>{{ session('exito') }}</span>
        </div>
    @endif

    <div class="adm-page-header">
        <div>
            <h1 class="adm-page-title">Proyectos <span>de Inversión</span></h1>
            <p class="adm-page-sub">Administra los proyectos de inversión y su financiamiento.</p>
        </div>
        <a href="{{ route('admin.proyectos-inversion.create') }}" class="adm-btn adm-btn-primary">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Nuevo proyecto
        </a>
    </div>

    <div class="adm-stats">
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(0,212,255,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(0,212,255,.9)" style="width:20px;height:20px"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">{{ $stats['total'] }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">Total proyectos</div>
            </div>
        </div>
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(34,197,94,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(34,197,94,.9)" style="width:20px;height:20px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">{{ $stats['disponible'] }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">Disponibles</div>
            </div>
        </div>
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(251,191,36,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(251,191,36,.9)" style="width:20px;height:20px"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">{{ $stats['en_ejecucion'] }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">En ejecución</div>
            </div>
        </div>
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(16,185,129,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(16,185,129,.9)" style="width:20px;height:20px"><path d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">{{ $stats['completado'] }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">Completados</div>
            </div>
        </div>
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(168,85,247,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(168,85,247,.9)" style="width:20px;height:20px"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">{{ $stats['visibles'] }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">Visibles</div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.proyectos-inversion.index') }}" style="display:flex; align-items:center; gap:.75rem; margin-bottom:1.25rem; flex-wrap:wrap;" id="searchForm">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre..." class="adm-input" style="max-width:260px;">
        <select name="estado" class="adm-select-field" style="max-width:190px;" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach(\App\Models\ProyectoInversion::ESTADOS as $val => $label)
                <option value="{{ $val }}" {{ request('estado') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="categoria" class="adm-select-field" style="max-width:190px;" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach(\App\Models\ProyectoInversion::CATEGORIAS as $val => $label)
                <option value="{{ $val }}" {{ request('categoria') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="adm-btn adm-btn-primary">Buscar</button>
        @if(request()->hasAny(['buscar','estado','categoria']))
            <a href="{{ route('admin.proyectos-inversion.index') }}" class="adm-btn adm-btn-ghost">✕ Limpiar</a>
        @endif
        <span style="font-size:.78rem; color:var(--muted); margin-left:auto;">{{ $proyectos->total() }} resultado(s)</span>
    </form>

    <div class="adm-table-container">
        @if($proyectos->count())
        <table class="adm-table">
            <colgroup>
                <col><col><col><col><col><col><col>
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align:center">Imagen</th>
                    <th>Proyecto</th>
                    <th>Categoría</th>
                    <th>Retorno</th>
                    <th>Estado</th>
                    <th style="text-align:center">Visible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($proyectos as $p)
            <tr>
                <td style="text-align:center">
                    @if($p->imagen_url)
                        <img src="{{ $p->imagen_url }}"
                             class="adm-pinv-img"
                             alt="{{ $p->nombre }}"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="adm-pinv-emoji-fallback" style="display:none">📈</div>
                    @else
                        <div class="adm-pinv-emoji-fallback">📈</div>
                    @endif
                </td>

                <td>
                    <div class="adm-pinv-name">{{ $p->nombre }}</div>
                    <div class="adm-pinv-slug">{{ $p->slug }}</div>
                </td>

                <td>
                    <span class="adm-badge">{{ $p->categoria_label }}</span>
                </td>

                <td>
                    <span class="adm-pinv-retorno">{{ $p->retorno_estimado_label }}</span>
                </td>

                <td>
                    <span class="adm-badge adm-pinv-{{ $p->estado }}">
                        {{ $p->estado_label }}
                    </span>
                </td>

                <td style="text-align:center">
                    <form method="POST" action="{{ route('admin.proyectos-inversion.toggle', $p) }}">
                        @csrf @method('PATCH')
                        <label class="adm-toggle" title="{{ $p->visible ? 'Ocultar' : 'Mostrar' }}">
                            <input type="checkbox" {{ $p->visible ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <span class="adm-toggle-slider"></span>
                        </label>
                    </form>
                </td>

                <td>
                    <div class="adm-actions">
                        <a href="{{ route('admin.proyectos-inversion.edit', $p) }}"
                           class="adm-btn adm-btn-ghost" style="padding: .32rem .65rem; font-size: .76rem;">
                            <svg viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.proyectos-inversion.destroy', $p) }}"
                               onsubmit="return confirm('¿Eliminar «{{ $p->nombre }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="adm-btn adm-btn-danger" style="padding: .32rem .65rem; font-size: .76rem;">
                                <svg viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px">
                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        @if($proyectos->hasPages())
        <div class="adm-pagination">
            <span class="adm-pagination-info">
                Mostrando {{ $proyectos->firstItem() }}–{{ $proyectos->lastItem() }}
                de {{ $proyectos->total() }} proyectos
            </span>
            {{ $proyectos->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="adm-empty">
            <div class="adm-empty-icon">📈</div>
            @if(request()->hasAny(['buscar','estado','categoria']))
                <p>Sin resultados para los filtros aplicados.</p>
                <a href="{{ route('admin.proyectos-inversion.index') }}" class="adm-btn adm-btn-ghost"
                   style="margin-top:1.25rem">Limpiar filtros</a>
            @else
                <p>Aún no hay proyectos de inversión registrados.</p>
                <a href="{{ route('admin.proyectos-inversion.create') }}" class="adm-btn adm-btn-primary"
                   style="margin-top:1.25rem">+ Agregar el primero</a>
            @endif
        </div>
        @endif
    </div>

@endsection
