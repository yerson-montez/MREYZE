
@extends('welcome')

@section('title', 'Tienda — MREYZE')

@section('content')

{{-- resources/views/sections/tienda.blade.php --}}
{{-- Uso: @include('sections.tienda') --}}

<style>
    /* ═══════════════════════════════════════
       TIENDA SECTION
    ═══════════════════════════════════════ */
    .tienda {
        padding: 7rem 2rem;
        background: var(--ice-800);
        position: relative;
        overflow: hidden;
    }

    .tienda::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(74,144,217,0.3), transparent);
    }

    .tienda-bg {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 90% 10%, rgba(0,212,255,0.07) 0%, transparent 50%),
            radial-gradient(ellipse at 10% 90%, rgba(30,77,153,0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .tienda-container {
        max-width: 1280px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .tienda-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 3rem;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .tienda-ver-mas {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.3rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--accent);
        border: 1px solid rgba(0,212,255,0.3);
        text-decoration: none;
        transition: all 0.25s;
        white-space: nowrap;
    }
    .tienda-ver-mas svg { width: 15px; height: 15px; transition: transform 0.2s; }
    .tienda-ver-mas:hover { background: rgba(0,212,255,0.1); border-color: rgba(0,212,255,0.5); }
    .tienda-ver-mas:hover svg { transform: translateX(4px); }

    /* Categorías */
    .tienda-cats {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
    }

    .cat-btn {
        padding: 0.4rem 1.1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid rgba(74,144,217,0.2);
        background: transparent;
        color: var(--ice-300);
        transition: all 0.22s;
    }
    .cat-btn:hover { border-color: rgba(74,144,217,0.5); color: var(--ice-100); }
    .cat-btn.active { background: rgba(37,99,199,0.3); border-color: rgba(0,212,255,0.5); color: var(--accent); }

    /* Grid de productos */
    .tienda-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }

    /* Tarjeta de producto */
    .producto-card {
        background: rgba(10,22,40,0.7);
        border: 1px solid rgba(74,144,217,0.15);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.35s;
        position: relative;
    }

    .producto-card:hover {
        border-color: rgba(0,212,255,0.35);
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(0,30,90,0.5), 0 0 0 1px rgba(0,212,255,0.08);
    }

    /* Imagen del producto */
    .producto-img {
        height: 160px;
        background: rgba(13,31,60,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .producto-img-bg {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        opacity: 0.05;
        transition: all 0.4s;
    }

    .producto-card:hover .producto-img-bg { opacity: 0.1; transform: scale(1.1); }

    .producto-img-icon {
        position: relative;
        z-index: 2;
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s;
    }

    .producto-img-icon svg { width: 28px; height: 28px; }
    .producto-card:hover .producto-img-icon { transform: scale(1.1) rotate(-5deg); }

    /* Badge de stock */
    .producto-stock {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .producto-stock.disponible { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #4ade80; }
    .producto-stock.agotado   { background: rgba(239,68,68,0.15);  border: 1px solid rgba(239,68,68,0.3);  color: #f87171; }
    .producto-stock.nuevo     { background: rgba(0,212,255,0.15);   border: 1px solid rgba(0,212,255,0.3);   color: var(--accent); }

    /* Badge descuento */
    .producto-descuento {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        background: rgba(251,191,36,0.2);
        border: 1px solid rgba(251,191,36,0.4);
        color: #fbbf24;
    }

    /* Cuerpo */
    .producto-body {
        padding: 1.1rem;
    }

    .producto-cat {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.4rem;
    }

    .producto-nombre {
        font-family: 'Exo 2', sans-serif;
        font-size: 0.9rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.4rem;
        line-height: 1.3;
    }

    .producto-desc {
        font-size: 0.75rem;
        color: var(--ice-300);
        line-height: 1.55;
        margin-bottom: 1rem;
    }

    /* Precio y botón */
    .producto-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .producto-precio {
        display: flex;
        flex-direction: column;
    }

    .producto-precio-actual {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
    }

    .producto-precio-actual span { color: var(--accent); font-size: 0.75rem; }

    .producto-precio-anterior {
        font-size: 0.72rem;
        color: var(--ice-300);
        text-decoration: line-through;
    }

    .producto-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 0.5rem 0.9rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--ice-500), var(--ice-400));
        border: none;
        cursor: pointer;
        transition: all 0.25s;
        text-decoration: none;
        white-space: nowrap;
    }
    .producto-btn svg { width: 13px; height: 13px; }
    .producto-btn:hover {
        background: linear-gradient(135deg, var(--ice-400), var(--glow));
        box-shadow: 0 4px 14px rgba(59,130,246,0.45);
        transform: translateY(-1px);
        color: #fff;
    }
    .producto-btn:disabled, .producto-btn.agotado-btn {
        background: rgba(74,144,217,0.15);
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
        color: var(--ice-300);
    }

    /* Banner promo */
    .tienda-banner {
        margin-top: 3rem;
        padding: 2rem 2.5rem;
        background: linear-gradient(135deg, rgba(30,77,153,0.3), rgba(0,212,255,0.08));
        border: 1px solid rgba(0,212,255,0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
        position: relative;
        overflow: hidden;
    }

    .tienda-banner::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(0,212,255,0.05);
        border: 1px solid rgba(0,212,255,0.1);
    }

    .tienda-banner-text {}
    .tienda-banner-title {
        font-family: 'Exo 2', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.3rem;
    }
    .tienda-banner-desc { font-size: 0.85rem; color: var(--ice-200); }
    .tienda-banner-desc strong { color: var(--accent); }

    @media (max-width: 1100px) { .tienda-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  { .tienda-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px)  { .tienda-grid { grid-template-columns: 1fr; } }
</style>

{{--
    tiendaCliente.blade.php  ·  sección pública
    Los productos ahora vienen del controlador: compact('productos')
--}}

<section class="tienda" id="tienda">
    <div class="tienda-bg"></div>
    <div class="tienda-container">

        {{-- Header --}}
        <div class="tienda-header reveal">
            <div>
                <div class="section-label">Productos</div>
                <h2 class="section-title">Nuestra <span>Tienda</span></h2>
            </div>
            <div style="display:flex;align-items:center;gap:1rem">

                {{-- Botón admin (solo para admins autenticados) --}}
                @auth
                    @if(auth()->user()->esAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       style="display:inline-flex;align-items:center;gap:.4rem;
                              padding:.45rem 1rem;border-radius:8px;font-size:.8rem;font-weight:600;
                              background:rgba(0,212,255,.1);color:#00d4ff;
                              border:1px solid rgba(0,212,255,.3);text-decoration:none;
                              transition:all .18s"
                       onmouseover="this.style.background='rgba(0,212,255,.2)'"
                       onmouseout="this.style.background='rgba(0,212,255,.1)'">
                        <svg viewBox="0 0 24 24" fill="currentColor" style="width:14px;height:14px">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        Panel admin
                    </a>
                    @endif
                @endauth

                <a href="{{ url('/tienda') }}" class="tienda-ver-mas">
                    Ver catálogo completo
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>
                </a>
            </div>
        </div>

        {{-- Categorías --}}
        <div class="tienda-cats reveal">
            <button class="cat-btn active" onclick="filtrarCat('todos', this)">Todos</button>
            <button class="cat-btn" onclick="filtrarCat('iot', this)">IoT / Electrónica</button>
            <button class="cat-btn" onclick="filtrarCat('software', this)">Software</button>
            <button class="cat-btn" onclick="filtrarCat('kits', this)">Kits</button>
        </div>

        {{-- Productos dinámicos desde DB --}}
        <div class="tienda-grid">

            @forelse($productos as $p)
            <div class="producto-card reveal" data-cat="{{ $p->categoria }}">
                <div class="producto-img">
                    <div class="producto-img-bg">{{ $p->icono_emoji }}</div>
                    <div class="producto-img-icon" style="background:{{ $p->icono_color }};border:1px solid {{ str_replace('0.12','0.3',$p->icono_color) }}">
                        {{-- Ícono SVG genérico según categoría --}}
                        @if($p->categoria === 'iot')
                            <svg viewBox="0 0 24 24" fill="rgba(0,212,255,0.9)"><path d="M1 9l2 2c4.97-4.97 13.03-4.97 18 0l2-2C16.93 2.93 7.08 2.93 1 9zm8 8l3 3 3-3c-1.65-1.66-4.34-1.66-6 0zm-4-4l2 2c2.76-2.76 7.24-2.76 10 0l2-2C15.14 9.14 8.87 9.14 5 13z"/></svg>
                        @elseif($p->categoria === 'software')
                            <svg viewBox="0 0 24 24" fill="rgba(45,212,191,0.9)"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="rgba(122,184,245,0.9)"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/></svg>
                        @endif
                    </div>

                    {{-- Badge estado --}}
                    <span class="producto-stock {{ $p->estado }}">{{ ucfirst($p->estado) }}</span>

                    {{-- Badge descuento --}}
                    @if($p->tieneDescuento())
                    <span class="producto-descuento">-{{ $p->porcentaje_descuento }}%</span>
                    @endif
                </div>

                <div class="producto-body">
                    <div class="producto-cat">{{ strtoupper($p->categoria) }}</div>
                    <div class="producto-nombre">{{ $p->nombre }}</div>
                    <p class="producto-desc">{{ $p->descripcion }}</p>
                    <div class="producto-footer">
                        <div class="producto-precio">
                            <div class="producto-precio-actual">
                                S/ {{ number_format($p->precio, 0) }}<span>.{{ substr(number_format($p->precio, 2), -2) }}</span>
                            </div>
                            @if($p->precio_anterior)
                            <div class="producto-precio-anterior">S/ {{ number_format($p->precio_anterior, 2) }}</div>
                            @endif
                        </div>

                        @if($p->estado !== 'agotado')
                        <a href="{{ route('pedidos.create', $p->slug) }}" class="producto-btn">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            Comprar
                        </a>
                        @else
                        <button class="producto-btn agotado-btn" disabled>Agotado</button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#7a9ab8">
                No hay productos disponibles en este momento.
            </div>
            @endforelse

        </div>

        {{-- Banner promo --}}
        <div class="tienda-banner reveal">
            <div class="tienda-banner-text">
                <div class="tienda-banner-title">🎯 ¿Necesitas algo específico?</div>
                <p class="tienda-banner-desc">Fabricamos dispositivos electrónicos y desarrollamos software <strong>completamente a medida</strong>. Cuéntanos tu necesidad.</p>
            </div>
            <a href="{{ url('/contacto') }}" class="hero-btn-primary" style="display:inline-flex;flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:17px;height:17px"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                Pedir cotización
            </a>
        </div>

    </div>
</section>

<script>
function filtrarCat(cat, btn) {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.producto-card').forEach(card => {
        card.style.display = (cat === 'todos' || card.dataset.cat === cat) ? '' : 'none';
    });
}
</script>
@endsection 