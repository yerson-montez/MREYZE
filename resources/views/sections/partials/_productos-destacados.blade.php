{{-- resources/views/sections/partials/_productos-destacados.blade.php --}}
{{-- Sección "Productos destacados" para la parte inferior de Inicio. --}}
{{-- Recibe: $productosHome (colección de Producto activos, con 'imagenes' precargadas). --}}

<section class="destacados" id="destacados">
    <div class="destacados-container">

        <div class="destacados-header reveal">
            <div>
                <div class="section-label">Catálogo</div>
                <h2 class="section-title">Productos <span>destacados</span></h2>
                <p class="section-desc">Descubre algunos productos disponibles en nuestra tienda.</p>
            </div>
            <a href="{{ route('tienda.index') }}" class="destacados-ver-mas">
                Ver catálogo completo
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
            </a>
        </div>

        <div class="destacados-grid">
            @forelse($productosHome as $p)

                @php
                    $urlRuta = fn ($ruta) => str_starts_with($ruta, 'http') ? $ruta : asset('storage/' . $ruta);
                    $img = $p->imagenes->first();
                    $imgUrl = $img ? $urlRuta($img->ruta) : ($p->icono_imagen ? $urlRuta($p->icono_imagen) : null);
                @endphp

                <a href="{{ route('tienda.show', $p->slug) }}"
                   class="destacado-card reveal"
                   style="text-decoration:none;color:inherit;display:flex;flex-direction:column">

                    <div class="destacado-img">
                        @if($imgUrl)
                            <img src="{{ $imgUrl }}"
                                 alt="{{ $p->nombre }}"
                                 loading="lazy">
                        @else
                            <span style="font-size:12px;color:#7a9ab8">Sin imagen</span>
                        @endif

                        <span class="destacado-stock {{ $p->estado }}">{{ ucfirst($p->estado) }}</span>

                        @if($p->tieneDescuento())
                            <span class="destacado-descuento">-{{ $p->porcentaje_descuento }}%</span>
                        @endif
                    </div>

                    <div class="destacado-body">
                        <div class="destacado-cat">{{ strtoupper($p->categoria) }}</div>
                        <div class="destacado-nombre">{{ $p->nombre }}</div>

                        <div class="destacado-precio">
                            <span class="destacado-precio-actual">
                                @php($pf = number_format($p->precio, 2, '.', ''))
                                @php($parts = explode('.', $pf))
                                S/ {{ $parts[0] }}<span>.{{ $parts[1] }}</span>
                            </span>
                            @if($p->tieneDescuento())
                                <span class="destacado-precio-anterior">S/ {{ number_format($p->precio_anterior, 2) }}</span>
                            @endif
                        </div>

                        <span class="destacado-btn">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            Ver producto
                        </span>
                    </div>
                </a>

            @empty
                <div class="destacados-empty">
                    No hay productos disponibles en este momento.
                </div>
            @endforelse
        </div>

    </div>
</section>

@once
@push('styles')
<style>
    /* ═══════════════════════════════════════
       PRODUCTOS DESTACADOS — Inicio
    ═══════════════════════════════════════ */
    .destacados {
        position: relative;
        z-index: 1;
        padding: 6rem 2rem;
        background: var(--ice-800);
        border-top: 1px solid rgba(74, 144, 217, 0.15);
        overflow: hidden;
    }
    .destacados::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 90% 10%, rgba(0, 212, 255, 0.07) 0%, transparent 50%),
            radial-gradient(ellipse at 10% 90%, rgba(30, 77, 153, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    .destacados-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .destacados-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .destacados-ver-mas {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.3rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--accent);
        border: 1px solid rgba(0, 212, 255, 0.3);
        text-decoration: none;
        transition: all 0.25s;
        white-space: nowrap;
    }
    .destacados-ver-mas svg { width: 15px; height: 15px; transition: transform 0.2s; }
    .destacados-ver-mas:hover { background: rgba(0, 212, 255, 0.1); border-color: rgba(0, 212, 255, 0.5); color: #fff; }
    .destacados-ver-mas:hover svg { transform: translateX(4px); }

    .destacados-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr));
        gap: 1.25rem;
    }

    /* Tarjeta de producto */
    .destacado-card {
        background: rgba(10, 22, 40, 0.7);
        border: 1px solid rgba(74, 144, 217, 0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.35s;
        position: relative;
        height: 100%;
    }
    .destacado-card:hover {
        border-color: rgba(0, 212, 255, 0.35);
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(0, 30, 90, 0.5), 0 0 0 1px rgba(0, 212, 255, 0.08);
    }

    .destacado-img {
        position: relative;
        aspect-ratio: 4 / 3;
        background: rgba(13, 31, 60, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .destacado-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s;
    }
    .destacado-card:hover .destacado-img img { transform: scale(1.06); }

    .destacado-stock {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 20px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .destacado-stock.disponible { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
    .destacado-stock.agotado   { background: rgba(239,68,68,0.15);  border: 1px solid rgba(239,68,68,0.3);  color: #f87171; }
    .destacado-stock.nuevo     { background: rgba(0,212,255,0.15);   border: 1px solid rgba(0,212,255,0.3);   color: var(--accent); }

    .destacado-descuento {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        background: rgba(251, 191, 36, 0.2);
        border: 1px solid rgba(251, 191, 36, 0.4);
        color: #fbbf24;
    }

    .destacado-body {
        padding: 1.1rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        flex: 1;
    }
    .destacado-cat {
        font-size: 0.66rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--accent);
    }
    .destacado-nombre {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.92rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.4em;
    }
    .destacado-precio {
        margin-top: auto;
        display: flex;
        align-items: baseline;
        gap: 8px;
        flex-wrap: wrap;
    }
    .destacado-precio-actual {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
    }
    .destacado-precio-actual span { color: var(--accent); font-size: 0.75rem; }
    .destacado-precio-anterior {
        font-size: 0.72rem;
        color: var(--ice-300);
        text-decoration: line-through;
    }

    .destacado-btn {
        margin-top: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 44px;
        padding: 0.55rem 0.9rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--ice-500), var(--ice-400));
        border: 1px solid rgba(74, 144, 217, 0.4);
        transition: all 0.25s;
        white-space: nowrap;
    }
    .destacado-btn svg { width: 14px; height: 14px; }
    .destacado-card:hover .destacado-btn {
        background: linear-gradient(135deg, var(--ice-400), var(--glow));
        box-shadow: 0 4px 14px rgba(59, 130, 246, 0.45);
    }

    .destacados-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
        color: #7a9ab8;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .destacados-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 700px) {
        .destacados { padding: 4rem 1rem; }
        .destacados-header { flex-direction: column; align-items: flex-start; gap: 1rem; margin-bottom: 2rem; }
        .destacados-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem; }
        .destacado-card:hover { transform: none; }
        .destacado-body { padding: 0.55rem; gap: 0.3rem; }
        .destacado-cat { font-size: 0.55rem; letter-spacing: 0.06em; }
        .destacado-nombre { font-size: 0.68rem; min-height: 1.8em; }
        .destacado-precio { gap: 4px; }
        .destacado-precio-actual { font-size: 0.78rem; }
        .destacado-precio-actual span { font-size: 0.58rem; }
        .destacado-precio-anterior { font-size: 0.55rem; }
        .destacado-btn { min-height: 44px; font-size: 0.68rem; padding: 0.45rem 0.25rem; gap: 3px; }
        .destacado-btn svg { width: 11px; height: 11px; }
        .destacado-stock { top: 5px; left: 5px; font-size: 0.5rem; padding: 2px 5px; }
        .destacado-descuento { top: 5px; right: 5px; font-size: 0.55rem; padding: 2px 4px; border-radius: 4px; }
    }
    @media (max-width: 420px) {
        .destacados-grid { grid-template-columns: 1fr; gap: 0.75rem; }
        .destacados-ver-mas { padding: 0.45rem 0.9rem; font-size: 0.75rem; }
        .destacado-body { padding: 0.45rem; gap: 0.25rem; }
        .destacado-cat { font-size: 0.5rem; }
        .destacado-nombre { font-size: 0.6rem; min-height: 1.6em; }
        .destacado-precio { gap: 3px; }
        .destacado-precio-actual { font-size: 0.72rem; }
        .destacado-precio-actual span { font-size: 0.55rem; }
        .destacado-precio-anterior { font-size: 0.52rem; }
        .destacado-btn { min-height: 44px; font-size: 0.68rem; padding: 0.45rem 0.2rem; gap: 2px; }
        .destacado-btn svg { width: 10px; height: 10px; }
        .destacado-stock { top: 4px; left: 4px; font-size: 0.48rem; padding: 2px 4px; }
        .destacado-descuento { top: 4px; right: 4px; font-size: 0.52rem; padding: 2px 3px; border-radius: 4px; }
    }
</style>
@endpush
@endonce
