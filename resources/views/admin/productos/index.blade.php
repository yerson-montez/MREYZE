@extends('layouts.admin')

@section('title', 'Admin · Productos')

@push('styles')
<style>
/* ── Estilos específicos de listado de productos ── */

.adm-prod-img {
    width: 46px; height: 46px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--border);
    display: block;
    background: rgba(0,212,255,.05);
}
.adm-prod-emoji-fallback {
    width: 46px; height: 46px;
    border-radius: 10px;
    border: 1px solid var(--border);
    background: rgba(0,212,255,.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.adm-prod-name {
    font-weight: 700;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.adm-prod-slug {
    font-size: .7rem;
    color: var(--muted);
    margin-top: .1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Toggle Switch */
.adm-toggle { position: relative; display: inline-block; width: 40px; height: 22px; cursor: pointer; }
.adm-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
.adm-toggle-slider {
    position: absolute; inset: 0;
    background: rgba(255,255,255,.08);
    border: 1px solid var(--border);
    border-radius: 22px;
    transition: .25s;
}
.adm-toggle-slider::before {
    content: '';
    position: absolute;
    width: 15px; height: 15px;
    left: 3px; top: 2.5px;
    background: var(--muted);
    border-radius: 50%;
    transition: .25s;
}
.adm-toggle input:checked + .adm-toggle-slider { background: rgba(0,212,255,.2); border-color: rgba(0,212,255,.4); }
.adm-toggle input:checked + .adm-toggle-slider::before { transform: translateX(17px); background: var(--accent); }

/* Stock Form */
.adm-stock-form { display: flex; align-items: center; gap: .35rem; }
.adm-stock-input {
    width: 58px;
    background: rgba(0,212,255,.07);
    border: 1px solid var(--border);
    border-radius: 7px;
    color: var(--text);
    padding: .3rem .45rem;
    font-family: inherit;
    font-size: .82rem;
    text-align: center;
    outline: none;
    transition: border-color .2s;
}
.adm-stock-input:focus { border-color: rgba(0,212,255,.5); }

/* Acciones */
.adm-actions { display: flex; gap: .35rem; align-items: center; flex-wrap: nowrap; }

/* Control de columnas responsivo */
.adm-table colgroup col:nth-child(1) { width: 70px;  }
.adm-table colgroup col:nth-child(2) { width: auto;  }
.adm-table colgroup col:nth-child(3) { width: 110px; }
.adm-table colgroup col:nth-child(4) { width: 100px; }
.adm-table colgroup col:nth-child(5) { width: 80px;  }
.adm-table colgroup col:nth-child(6) { width: 90px;  }
.adm-table colgroup col:nth-child(7) { width: 130px; }
.adm-table colgroup col:nth-child(8) { width: 100px; }
.adm-table colgroup col:nth-child(9) { width: 72px;  }
.adm-table colgroup col:nth-child(10) { width: 160px; }

/* Badges específicos de productos */
.adm-badge-iot      { background: rgba(0,212,255,.12);   color:#00d4ff; border:1px solid rgba(0,212,255,.25); }
.adm-badge-software { background: rgba(20,184,166,.12);  color:#2dd4bf; border:1px solid rgba(20,184,166,.25); }
.adm-badge-kits     { background: rgba(122,184,245,.12); color:#7ab8f5; border:1px solid rgba(122,184,245,.25); }
.adm-estado-nuevo       { background: rgba(245,158,11,.12); color:#f59e0b; border:1px solid rgba(245,158,11,.3); }
.adm-estado-disponible  { background: rgba(0,212,255,.12);  color:#00d4ff; border:1px solid rgba(0,212,255,.3); }
.adm-estado-agotado     { background: rgba(244,63,94,.12);  color:#f43f5e; border:1px solid rgba(244,63,94,.3); }

@media (max-width: 768px) {
    .adm-table colgroup col:nth-child(4),
    .adm-table colgroup col:nth-child(5) { display: none; }
    .adm-table th:nth-child(4),
    .adm-table td:nth-child(4),
    .adm-table th:nth-child(5),
    .adm-table td:nth-child(5) { display: none; }
}
</style>
@endpush

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="adm-breadcrumb-link">Inicio</a>
    <span class="adm-breadcrumb-sep">/</span>
    <span class="adm-breadcrumb-current">Productos</span>
@endsection

@section('content')

    {{-- Alerta éxito --}}
    @if(session('exito'))
    <div class="adm-alert adm-alert-success">
        <svg viewBox="0 0 24 24" fill="currentColor" style="width:17px;height:17px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        {{ session('exito') }}
    </div>
    @endif

    {{-- Header de página --}}
    <div class="adm-page-header">
        <div>
            <div class="adm-page-sub">Gestión · Tienda</div>
            <h1 class="adm-page-title">Productos de la <span>Tienda</span></h1>
        <div style="display:flex; gap:.5rem;">

    <a href="{{ route('admin.productos.importar') }}" class="adm-btn adm-btn-ghost">
        📥 Importar Excel
    </a>

    <a href="{{ route('admin.productos.create') }}" class="adm-btn adm-btn-primary">
        <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
        </svg>
        Nuevo producto
    </a>

    @if($stats['total'] > 0)
    <form method="POST" action="{{ route('admin.productos.eliminarTodos') }}"
          onsubmit="return confirm('¿Estás seguro de que deseas eliminar todos los productos?');">
        @csrf
        <button type="submit" class="adm-btn adm-btn-danger">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px">
                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
            </svg>
            Eliminar todos los productos
        </button>
    </form>
    @endif

</div>

    {{-- Stats --}}
    <div class="adm-stats">
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(0,212,255,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(0,212,255,.9)" style="width:20px;height:20px"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">{{ $stats['total'] }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">Total productos</div>
            </div>
        </div>
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(16,185,129,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(16,185,129,.9)" style="width:20px;height:20px"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">{{ $stats['activos'] }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">Activos en tienda</div>
            </div>
        </div>
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(244,63,94,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(244,63,94,.9)" style="width:20px;height:20px"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">{{ $stats['agotados'] }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">Agotados</div>
            </div>
        </div>
        <div class="adm-card" style="display:flex; align-items:center; gap:0.9rem; padding: 1.1rem 1.3rem;">
            <div style="width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; background:rgba(245,158,11,.12)">
                <svg viewBox="0 0 24 24" fill="rgba(245,158,11,.9)" style="width:20px;height:20px"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
            </div>
            <div>
                <div style="font-size: 1.55rem; font-weight: 800; line-height: 1; color: var(--text);">S/ {{ number_format($stats['ingresos'], 0) }}</div>
                <div style="font-size: .75rem; color: var(--muted); margin-top: .25rem;">Valor catálogo</div>
            </div>
        </div>
    </div>

    {{-- Toolbar / Buscador --}}
    <form method="GET" action="{{ route('admin.productos.index') }}" style="display:flex; align-items:center; gap:.75rem; margin-bottom:1.25rem; flex-wrap:wrap;" id="searchForm">
        <div style="flex:1; min-width:220px; position:relative; display:flex; align-items:center;">
            <svg viewBox="0 0 24 24" fill="currentColor" style="position:absolute; left:.85rem; width:15px; height:15px; color:var(--muted); pointer-events:none;">
                <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <input type="text" name="buscar" id="buscarInput"
                   value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre o slug..."
                   style="padding-left: 2.4rem;"
                   class="adm-input"
                   autocomplete="off">
        </div>
        <select name="cat" class="adm-select" style="width: auto;" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            <option value="iot"      {{ request('cat')==='iot'      ? 'selected' : '' }}>IoT / Electrónica</option>
            <option value="software" {{ request('cat')==='software' ? 'selected' : '' }}>Software</option>
            <option value="kits"     {{ request('cat')==='kits'     ? 'selected' : '' }}>Kits</option>
        </select>
        <select name="estado" class="adm-select" style="width: auto;" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="disponible" {{ request('estado')==='disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="nuevo"      {{ request('estado')==='nuevo'      ? 'selected' : '' }}>Nuevo</option>
            <option value="agotado"    {{ request('estado')==='agotado'    ? 'selected' : '' }}>Agotado</option>
        </select>
        <button type="submit" class="adm-btn adm-btn-ghost">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:15px;height:15px"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
            Buscar
        </button>
        @if(request()->hasAny(['buscar','cat','estado']))
        <a href="{{ route('admin.productos.index') }}" class="adm-btn adm-btn-ghost">✕ Limpiar</a>
        @endif
    </form>

    {{-- Tags de filtros activos + contador --}}
    @if(request()->hasAny(['buscar','cat','estado']))
    <div style="display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; margin-bottom:1rem;">
        @if(request('buscar'))
        <a href="{{ route('admin.productos.index', array_merge(request()->except('buscar','page'))) }}"
           style="display:inline-flex; align-items:center; gap:.35rem; background:rgba(0,212,255,.1); border:1px solid rgba(0,212,255,.25); color:var(--accent); border-radius:20px; padding:.2rem .7rem; font-size:.72rem; font-weight:600; text-decoration:none;">🔍 "{{ request('buscar') }}" ✕</a>
        @endif
        @if(request('cat'))
        <a href="{{ route('admin.productos.index', array_merge(request()->except('cat','page'))) }}"
           style="display:inline-flex; align-items:center; gap:.35rem; background:rgba(0,212,255,.1); border:1px solid rgba(0,212,255,.25); color:var(--accent); border-radius:20px; padding:.2rem .7rem; font-size:.72rem; font-weight:600; text-decoration:none;">📂 {{ ucfirst(request('cat')) }} ✕</a>
        @endif
        @if(request('estado'))
        <a href="{{ route('admin.productos.index', array_merge(request()->except('estado','page'))) }}"
           style="display:inline-flex; align-items:center; gap:.35rem; background:rgba(0,212,255,.1); border:1px solid rgba(0,212,255,.25); color:var(--accent); border-radius:20px; padding:.2rem .7rem; font-size:.72rem; font-weight:600; text-decoration:none;">🏷 {{ ucfirst(request('estado')) }} ✕</a>
        @endif
        <span style="font-size:.78rem; color:var(--muted); margin-left:auto;">{{ $productos->total() }} resultado(s)</span>
    </div>
    @endif

    {{-- Tabla --}}
    <div class="adm-table-container">
        @if($productos->count())
        <table class="adm-table">
            <colgroup>
                <col><col><col><col><col><col><col><col><col><col>
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align:center">Imagen</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Distribuidor</th>
                    <th>Margen</th>
                    <th>Venta</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th style="text-align:center">Visible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($productos as $p)
            <tr>
                {{-- 1. Imagen --}}
                <td style="text-align:center">
                    @php $imgUrl = $p->imagen_principal_url; @endphp
                    @if($imgUrl)
                        <img src="{{ $imgUrl }}"
                             class="adm-prod-img"
                             alt="{{ $p->nombre }}"
                             loading="lazy"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="adm-prod-emoji-fallback" style="display:none">
                            <svg viewBox="0 0 24 24" fill="currentColor" style="width:20px;height:20px;opacity:0.3"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                        </div>
                    @else
                        <div class="adm-prod-emoji-fallback">
                            <svg viewBox="0 0 24 24" fill="currentColor" style="width:20px;height:20px;opacity:0.3"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                        </div>
                    @endif
                </td>

                {{-- 2. Nombre + slug --}}
                <td>
                    <div class="adm-prod-name">{{ $p->nombre }}</div>
                    <div class="adm-prod-slug">{{ $p->slug }}</div>
                </td>

                {{-- 3. Categoría --}}
                <td>
                    <span class="adm-badge adm-badge-{{ $p->categoria }}">
                        {{ ucfirst($p->categoria) }}
                    </span>
                </td>

                {{-- 4. Precios (Distribuidor / Margen / Venta) --}}
                <td>
                    <div style="font-size:.73rem;color:var(--muted)">Dist: S/ {{ number_format($p->precio_distribuidor_efectivo,2) }}</div>
                    <div style="font-size:.73rem;color:var(--accent)">Margen: {{ $p->margen_aplicado ?? 0 }}%</div>
                    <div style="font-weight:700;color:var(--text);margin-top:.1rem">S/ {{ number_format($p->precio,2) }}</div>
                    @if($p->precio_anterior)
                    <div style="font-size:.73rem;color:var(--muted);text-decoration:line-through">
                        Antes: S/ {{ number_format($p->precio_anterior,2) }}
                    </div>
                    @endif
                </td>

                {{-- 5. Stock --}}
                <td>
                    <form method="POST" action="{{ route('admin.productos.stock', $p) }}"
                          class="adm-stock-form">
                        @csrf @method('PATCH')
                        <input type="number" name="stock" value="{{ $p->stock }}"
                               min="0" class="adm-stock-input">
                        <button type="submit" class="adm-btn adm-btn-ghost" style="padding: .32rem .6rem; font-size: .76rem;" title="Guardar">✓</button>
                    </form>
                </td>

                {{-- 6. Estado --}}
                <td>
                    <span class="adm-badge adm-estado-{{ $p->estado }}">
                        {{ ucfirst($p->estado) }}
                    </span>
                </td>

                {{-- 7. Toggle visible --}}
                <td style="text-align:center">
                    <form method="POST" action="{{ route('admin.productos.toggle', $p) }}">
                        @csrf @method('PATCH')
                        <label class="adm-toggle" title="{{ $p->activo ? 'Ocultar' : 'Mostrar' }}">
                            <input type="checkbox" {{ $p->activo ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <span class="adm-toggle-slider"></span>
                        </label>
                    </form>
                </td>

                {{-- 8. Acciones --}}
                <td>
                    <div class="adm-actions">
                        <a href="{{ route('admin.productos.edit', $p) }}"
                           class="adm-btn adm-btn-ghost" style="padding: .32rem .65rem; font-size: .76rem;">
                            <svg viewBox="0 0 24 24" fill="currentColor" style="width:12px;height:12px">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.productos.destroy', $p) }}"
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

        {{-- Paginación --}}
        @if($productos->hasPages())
        <div class="adm-pagination">
            <span class="adm-pagination-info">
                Mostrando {{ $productos->firstItem() }}–{{ $productos->lastItem() }}
                de {{ $productos->total() }} productos
            </span>
            {{ $productos->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="adm-empty">
            <div class="adm-empty-icon">📦</div>
            @if(request()->hasAny(['buscar','cat','estado']))
                <p>Sin resultados para los filtros aplicados.</p>
                <a href="{{ route('admin.productos.index') }}" class="adm-btn adm-btn-ghost"
                   style="margin-top:1.25rem">Limpiar filtros</a>
            @else
                <p>Aún no hay productos registrados.</p>
                <a href="{{ route('admin.productos.create') }}" class="adm-btn adm-btn-primary"
                   style="margin-top:1.25rem">+ Agregar el primero</a>
            @endif
        </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    document.getElementById('buscarInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('searchForm').submit();
        }
    });
</script>
@endpush