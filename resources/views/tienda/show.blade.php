@extends('welcome')

@section('title', $producto->nombre . ' — MREYZE')

@push('styles')
<style>
/* ── Estilos de tarjetas de producto (reusados por _productos.blade.php) ── */
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
    width: 100%;
    height: 100%;
    position: relative;
    opacity: 1;
}
.producto-img-full {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
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
.producto-stock.nuevo     { background: rgba(0,212,255,0.15);   border: 1px solid rgba(0,212,255,0.3);   color: #00d4ff; }
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
.producto-body { padding: 1.1rem; }
.producto-cat {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #00d4ff;
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
.producto-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}
.producto-precio { display: flex; flex-direction: column; }
.producto-precio-actual {
    font-family: 'Exo 2', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #fff;
}
.producto-precio-actual span { color: #00d4ff; font-size: 0.75rem; }
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
</style>
@endpush

@section('content')
<section class="py-10 px-4 md:px-8" style="background:var(--ice-800);min-height:calc(100vh - 70px);position:relative">
    <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(74,144,217,0.3),transparent)"></div>

    <div class="max-w-6xl mx-auto relative z-10" x-data="productoDetalle()">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm mb-8 flex-wrap" style="color:var(--ice-300)">
            <a href="{{ url('/') }}" class="hover:text-cyan-400 transition-colors no-underline" style="color:inherit">Inicio</a>
            <span class="opacity-40">/</span>
            <a href="{{ url('/tienda') }}" class="hover:text-cyan-400 transition-colors no-underline" style="color:inherit">Tienda</a>
            <span class="opacity-40">/</span>
            <a href="{{ url('/tienda?cat=' . $producto->categoria) }}" class="hover:text-cyan-400 transition-colors no-underline" style="color:inherit">{{ ucfirst($producto->categoria) }}</a>
            <span class="opacity-40">/</span>
            <span class="font-medium" style="color:#fff">{{ $producto->nombre }}</span>
        </nav>

        {{-- Grid: Galeria + Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">

            {{-- GALERIA --}}
            <div class="lg:sticky lg:top-24">
                @php
                    $imgs = $producto->imagenes->sortBy('orden');
                    if ($imgs->isEmpty() && $producto->icono_imagen) {
                        $imgs = collect([(object)[
                            'ruta' => $producto->icono_imagen,
                            'es_principal' => true,
                            'orden' => 0,
                        ]]);
                    }
                    $imgPrincipal = $imgs->firstWhere('es_principal') ?? $imgs->first();
                    $mainSrc = $imgPrincipal 
    ? (str_starts_with($imgPrincipal->ruta, 'http') 
        ? $imgPrincipal->ruta 
        : asset('storage/' . $imgPrincipal->ruta))
    : null;

$fallbackSrc = $producto->icono_imagen 
    ? (str_starts_with($producto->icono_imagen, 'http') 
        ? $producto->icono_imagen 
        : asset('storage/' . $producto->icono_imagen))
    : null;
                    $displaySrc = $mainSrc ?? $fallbackSrc;
                @endphp

                <div class="relative rounded-2xl overflow-hidden cursor-crosshair group"
                     style="aspect-ratio:1;background:rgba(13,31,60,0.8);border:1px solid rgba(74,144,217,0.15)"
                     @click="abrirLightbox()">

                    @if($displaySrc)
                        <img :src="imagenActual"
                             alt="{{ addslashes($producto->nombre) }}"
                             class="w-full h-full block transition-transform duration-300"
                             style="object-fit:contain"
                             loading="eager">
                    @else
                        <div class="flex items-center justify-center w-full h-full" style="color:var(--ice-300)">
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto mb-3 opacity-30" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                </svg>
                                <p class="text-sm">Este producto aun no tiene imagenes.</p>
                            </div>
                        </div>
                    @endif

                    <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
                        @if($producto->tieneDescuento())
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold uppercase backdrop-blur-sm"
                                  style="background:rgba(251,191,36,0.25);border:1px solid rgba(251,191,36,0.5);color:#fbbf24">
                                -{{ $producto->porcentaje_descuento }}%
                            </span>
                        @endif
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase backdrop-blur-sm"
                              @if($producto->estado === 'disponible') style="background:rgba(34,197,94,0.2);border:1px solid rgba(34,197,94,0.4);color:#4ade80"
                              @elseif($producto->estado === 'agotado') style="background:rgba(239,68,68,0.2);border:1px solid rgba(239,68,68,0.4);color:#f87171"
                              @else style="background:rgba(0,212,255,0.2);border:1px solid rgba(0,212,255,0.4);color:#00d4ff" @endif>
                            {{ ucfirst($producto->estado) }}
                        </span>
                    </div>

                   @if($displaySrc)
<div class="absolute bottom-3 right-3 w-20 h-20 rounded-xl overflow-hidden z-10"
     style="background:rgba(0,0,0,0.5);border:2px solid rgba(0,212,255,0.5)">
    
    <img src="{{ $displaySrc }}"
         alt="{{ $producto->nombre }}"
         style="width:100%;height:100%;object-fit:cover;">
         
</div>
@endif
                </div>

                @if($imgs->count() >= 1)
                <div class="flex gap-2.5 mt-3 overflow-x-auto pb-1">
                    @foreach($imgs as $idx => $img)
                    <button type="button"
                            class="flex-shrink-0 w-16 h-16 md:w-[70px] md:h-[70px] rounded-xl overflow-hidden border-2 transition-all duration-200 cursor-pointer p-0"
                            :class="imagenActual === '{{ asset('storage/' . $img->ruta) }}'
                                ? 'border-cyan-400 opacity-100'
                                : 'border-transparent opacity-50 hover:opacity-75'"
                            :style="imagenActual === '{{ asset('storage/' . $img->ruta) }}'
                                ? 'box-shadow:0 0 12px rgba(0,212,255,0.25)'
                                : ''"
                            @click="seleccionar({{ $idx }}, '{{ asset('storage/' . $img->ruta) }}')">
                        @php
    $rutaImagen = str_starts_with($img->ruta, 'http')
        ? $img->ruta
        : asset('storage/' . $img->ruta);
@endphp

<div @click="seleccionar({{ $idx }}, '{{ $rutaImagen }}')">
    <img src="{{ $rutaImagen }}"
         alt="Imagen {{ $idx + 1 }}"
         class="w-full h-full object-cover"
         loading="lazy">
</div>
                    </button>
                    @endforeach
                </div>
                <p class="text-center text-xs mt-2" style="color:var(--ice-300)">
                    <span x-text="indiceActual + 1">1</span> / {{ $imgs->count() }} imagenes
                </p>
                @endif
            </div>

            {{-- INFORMACION --}}
            <div class="flex flex-col gap-5">
                <span class="text-xs font-bold tracking-widest uppercase" style="color:var(--accent)">
                    {{ strtoupper($producto->categoria) }}
                </span>

                <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold leading-tight" style="color:#fff;font-family:'Exo 2',sans-serif">
                    {{ $producto->nombre }}
                </h1>

                @if($producto->descripcion)
                    <p class="text-sm md:text-base leading-relaxed" style="color:var(--ice-200)">
                        {{ $producto->descripcion }}
                    </p>
                @endif

                <div class="flex items-baseline gap-4 flex-wrap">
                    <span class="text-3xl md:text-4xl font-bold" style="color:#fff;font-family:'Exo 2',sans-serif">
                        S/<span class="text-lg md:text-xl" style="color:var(--accent)">{{ number_format($producto->precio, 2) }}</span>
                    </span>
                    @if($producto->precio_anterior)
                        <span class="text-base line-through" style="color:var(--ice-300)">S/ {{ number_format($producto->precio_anterior, 2) }}</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-sm font-bold"
                              style="background:rgba(251,191,36,0.15);border:1px solid rgba(251,191,36,0.35);color:#fbbf24">
                            -{{ $producto->porcentaje_descuento }}% OFF
                        </span>
                    @endif
                </div>

                <hr style="border:none;border-top:1px solid rgba(74,144,217,0.12)">

                <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full flex-shrink-0"
                              @if($producto->estado === 'disponible') style="background:#4ade80;box-shadow:0 0 8px rgba(74,222,128,0.4)"
                              @elseif($producto->estado === 'agotado') style="background:#f87171;box-shadow:0 0 8px rgba(248,113,113,0.4)"
                              @else style="background:#00d4ff;box-shadow:0 0 8px rgba(0,212,255,0.4)" @endif></span>
                        <span style="color:var(--ice-300)">Estado:</span>
                        <span class="font-semibold" style="color:#fff">{{ ucfirst($producto->estado) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span style="color:var(--ice-300)">Stock:</span>
                        <span class="font-semibold" style="color:#fff">{{ $producto->stock }} unidades</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span style="color:var(--ice-300)">Categoria:</span>
                        <span class="font-semibold" style="color:#fff">{{ ucfirst($producto->categoria) }}</span>
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid rgba(74,144,217,0.12)">

                @if(isset($metodosPago) && $metodosPago->count())
                <div>
                    <div class="text-xs font-bold tracking-widest uppercase mb-2.5" style="color:var(--accent)">
                        Metodos de pago
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($metodosPago as $metodo)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm"
                                  style="color:var(--ice-200);background:rgba(74,144,217,0.08);border:1px solid rgba(74,144,217,0.12)">
                                @if($metodo->icono)
                                    <img src="{{ asset('storage/' . $metodo->icono) }}" alt="{{ $metodo->nombre }}" class="w-[18px] h-[18px] object-contain">
                                @endif
                                {{ $metodo->nombre }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex flex-col gap-3">
                    @if($producto->estado !== 'agotado')
                        <a href="{{ route('pedidos.create', $producto->slug) }}"
                           class="flex items-center justify-center gap-2.5 py-3.5 px-8 rounded-xl text-base font-bold text-white no-underline transition-all duration-300"
                           style="background:linear-gradient(135deg,var(--ice-500),var(--ice-400));border:1px solid rgba(74,144,217,0.4);box-shadow:0 6px 24px rgba(30,77,153,0.5)">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            Comprar ahora
                        </a>
                    @else
                        <div class="flex items-center justify-center gap-2.5 py-3.5 px-8 rounded-xl text-base font-bold cursor-not-allowed"
                             style="color:var(--ice-300);background:rgba(74,144,217,0.1);border:1px solid rgba(74,144,217,0.15)">
                            <svg class="w-5 h-5 opacity-50" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                            Producto agotado
                        </div>
                    @endif

                    <div class="flex gap-2.5">
                        <a href="https://wa.me/?text={{ urlencode($producto->nombre . ' - S/ ' . number_format($producto->precio, 2) . ' | ' . route('tienda.show', $producto->slug)) }}"
                           target="_blank"
                           class="flex-1 flex items-center justify-center gap-2.5 py-3 px-6 rounded-xl text-sm font-semibold text-white no-underline transition-all duration-250"
                           style="background:rgba(37,211,102,0.15);border:1px solid rgba(37,211,102,0.35)">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>

                        <button type="button"
                                class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-medium cursor-pointer transition-all duration-200"
                                style="color:var(--ice-300);background:transparent;border:1px solid rgba(74,144,217,0.15)"
                                onclick="compartirProducto()">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                            Compartir
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($relacionados) && $relacionados->count())
        <div class="mt-16 pt-12" style="border-top:1px solid rgba(74,144,217,0.12)">
            <div class="section-label">Te puede interesar</div>
            <h2 class="section-title" style="font-size:clamp(1.3rem,2.5vw,1.8rem)">Productos <span>relacionados</span></h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-6">
                @foreach($relacionados as $r)
                    @include('tienda._productos', ['productos' => collect([$r])])
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- LIGHTBOX --}}
<div x-show="lightboxAbierto"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
     style="background:rgba(0,0,0,0.92);backdrop-filter:blur(12px)"
     @click.self="cerrarLightbox()"
     @keydown.escape.window="cerrarLightbox()"
     @keydown.left.window="anterior()"
     @keydown.right.window="siguiente()"
     x-cloak>

    <button @click="cerrarLightbox()"
            class="absolute top-4 right-4 p-2 rounded-full transition-colors z-50"
            style="background:rgba(255,255,255,0.1);color:#fff">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
    </button>

    @if($imgs->count() > 1)
    <button @click="anterior()"
            class="absolute left-2 md:left-4 p-2 rounded-full transition-colors z-50"
            style="background:rgba(255,255,255,0.1);color:#fff">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
    </button>
    <button @click="siguiente()"
            class="absolute right-2 md:right-4 p-2 rounded-full transition-colors z-50"
            style="background:rgba(255,255,255,0.1);color:#fff">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
    </button>
    @endif

    <img :src="imagenActual"
         :alt="nombreProducto"
         class="max-w-full max-h-[85vh] object-contain rounded-lg select-none"
         @click.stop>

    @if($imgs->count() > 1)
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 px-3 py-2 rounded-full"
         style="background:rgba(0,0,0,0.6);backdrop-filter:blur(8px)">
        @foreach($imgs as $idx => $img)
        <button @click="seleccionar({{ $idx }}, '{{ asset('storage/' . $img->ruta) }}')"
                class="w-2.5 h-2.5 rounded-full transition-all duration-200 p-0 border-0 cursor-pointer"
                :class="indiceActual === {{ $idx }} ? 'scale-125' : 'opacity-50 hover:opacity-75'"
                :style="indiceActual === {{ $idx }} ? 'background:#00d4ff;box-shadow:0 0 8px rgba(0,212,255,0.5)' : 'background:#fff'">
        </button>
        @endforeach
    </div>
    <p class="absolute bottom-14 left-1/2 -translate-x-1/2 text-xs text-white/60">
        <span x-text="indiceActual + 1"></span> / <span x-text="todasLasImagenes.length"></span>
    </p>
    @endif
</div>

@endsection

@push('scripts')
<script>
function productoDetalle() {
    return {
        imagenActual: @js($displaySrc),
        indiceActual: 0,
        lightboxAbierto: false,
        nombreProducto: @js($producto->nombre),
        todasLasImagenes: @js($imgs->values()->map(fn($img) => asset('storage/' . $img->ruta))->values()->all()),

        seleccionar(idx, src) {
            this.imagenActual = src;
            this.indiceActual = idx;
        },

        abrirLightbox() {
            if (!this.imagenActual) return;
            this.lightboxAbierto = true;
            document.body.style.overflow = 'hidden';
        },

        cerrarLightbox() {
            this.lightboxAbierto = false;
            document.body.style.overflow = '';
        },

        anterior() {
            if (this.todasLasImagenes.length <= 1) return;
            this.indiceActual = this.indiceActual > 0
                ? this.indiceActual - 1
                : this.todasLasImagenes.length - 1;
            this.imagenActual = this.todasLasImagenes[this.indiceActual];
        },

        siguiente() {
            if (this.todasLasImagenes.length <= 1) return;
            this.indiceActual = this.indiceActual < this.todasLasImagenes.length - 1
                ? this.indiceActual + 1
                : 0;
            this.imagenActual = this.todasLasImagenes[this.indiceActual];
        }
    };
}

function compartirProducto() {
    const url = @js(route('tienda.show', $producto->slug));
    const title = @js($producto->nombre);
    if (navigator.share) {
        navigator.share({ title: title, url: url });
    } else if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            alert('Enlace copiado al portapapeles');
        });
    } else {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url), '_blank', 'width=600,height=400');
    }
}
</script>
@endpush