@forelse($productos as $p)
<a href="{{ route('tienda.show', $p->slug) }}"
   class="producto-card reveal"
   data-cat="{{ $p->categoria }}"
   style="text-decoration:none;color:inherit;display:block">

    <div class="producto-img">

    @php
        $imagenes = $p->imagenes;
        $imgUrl = $p->imagen_principal_url;
        $urlRuta = fn ($ruta) => str_starts_with($ruta, 'http') ? $ruta : asset('storage/' . $ruta);
    @endphp

    @if($imagenes->count())
        <img src="{{ $urlRuta($imagenes->first()->ruta) }}"
             alt="{{ $p->nombre }}"
             class="producto-img-full"
             loading="lazy">
    @elseif($imgUrl)
        <img src="{{ $imgUrl }}"
             alt="{{ $p->nombre }}"
             class="producto-img-full"
             loading="lazy"
             data-original-src="{{ $imgUrl }}">
    @else
        <div class="producto-img-bg">
            <span style="font-size:12px;color:#7a9ab8">Sin imagen</span>
        </div>
    @endif

    <span class="producto-stock {{ $p->estado }}">{{ ucfirst($p->estado) }}</span>

    @if($p->tieneDescuento())
    <span class="producto-descuento">-{{ $p->porcentaje_descuento }}%</span>
    @endif
    </div>

    @if($p->imagenes->count() > 1)
    <div class="producto-thumbs">
        @foreach($p->imagenes->sortBy('orden')->take(4) as $thumb)
        <img src="{{ $urlRuta($thumb->ruta) }}"
             class="producto-thumb"
             alt=""
             loading="lazy"
             onmouseover="catalogHover(this)"
             onmouseout="catalogUnhover(this)"
             onclick="event.preventDefault();event.stopPropagation();abrirProductoLightbox(this, '{{ addslashes($p->nombre) }}')">
        @endforeach
    </div>
    @endif

    <div class="producto-body">
        <div class="producto-cat">{{ strtoupper($p->categoria) }}</div>
        <div class="producto-nombre">{{ $p->nombre }}</div>
        <p class="producto-desc">{{ Str::limit($p->descripcion, 80) }}</p>
        <div class="producto-footer">
                <div class="producto-precio">
                    <div class="producto-precio-actual">
                        @php($precioFmt = number_format($p->precio, 2))
                        S/ {{ explode('.', $precioFmt)[0] }}<span>.{{ explode('.', $precioFmt)[1] }}</span>
                    </div>
                @if($p->precio_anterior)
                <div class="producto-precio-anterior">S/ {{ number_format($p->precio_anterior, 2) }}</div>
                @endif
                <div class="producto-pago-info">
                    <strong>💳 Paga solo el 50% ahora</strong>
                    <span>50% al confirmar · 50% al recibir</span>
                </div>
            </div>

            @if($p->estado !== 'agotado')
            <span class="producto-btn" onclick="event.preventDefault();event.stopPropagation();window.location='{{ route('pedidos.create', $p->slug) }}'">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                Comprar
            </span>
            @else
            <span class="producto-btn agotado-btn">Agotado</span>
            @endif
        </div>
    </div>
</a>
@empty
    <div class="tienda-empty" style="grid-column:1/-1;text-align:center;padding:3rem;color:#7a9ab8">
        {{ $emptyMessage ?? 'No se encontraron productos.' }}
    </div>
@endforelse

@once
@push('styles')
<style>
.producto-pago-info {
    display: grid;
    gap: .12rem;
    margin-top: .38rem;
    line-height: 1.3;
}
.producto-pago-info strong {
    color: #8deaff;
    font-size: .68rem;
    font-weight: 650;
}
.producto-pago-info span {
    color: #a9bfd3;
    font-size: .61rem;
}
@media (max-width: 520px) {
    .producto-pago-info strong { font-size: .6rem; }
    .producto-pago-info span { font-size: .54rem; }
}
.producto-thumbs {
    display: flex;
    gap: 4px;
    padding: 6px 8px;
    background: rgba(13,31,60,0.5);
    border-top: 1px solid rgba(74,144,217,0.08);
    overflow-x: auto;
}
.producto-thumb {
    width: 38px;
    height: 38px;
    border-radius: 6px;
    object-fit: cover;
    cursor: pointer;
    border: 1px solid rgba(74,144,217,0.15);
    transition: all 0.2s;
    opacity: 0.75;
    flex-shrink: 0;
}
.producto-thumb:hover {
    opacity: 1;
    border-color: rgba(0,212,255,0.4);
    box-shadow: 0 0 8px rgba(0,212,255,0.15);
}

/* ── Visor de imagen (miniaturas) ── */
.producto-lightbox {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 10000;
    align-items: center;
    justify-content: center;
    background: rgba(0,8,20,0.92);
    backdrop-filter: blur(12px);
    cursor: zoom-out;
    animation: prodLbFade 0.25s ease;
}
.producto-lightbox.open { display: flex; }
@keyframes prodLbFade { from { opacity: 0; } to { opacity: 1; } }
.producto-lightbox img {
    max-width: 88vw;
    max-height: 85vh;
    object-fit: contain;
    border-radius: 12px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.7), 0 0 0 1px rgba(0,212,255,0.15);
    animation: prodLbScale 0.3s cubic-bezier(0.34,1.56,0.64,1);
}
@keyframes prodLbScale { from { transform: scale(0.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.producto-lightbox-close {
    position: absolute;
    top: 1.2rem;
    right: 1.2rem;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}
.producto-lightbox-close:hover { background: rgba(0,212,255,0.18); transform: scale(1.1); }

.producto-card { border-radius: 1rem; border-color: rgba(130, 190, 240, .14); background: rgba(8, 25, 41, .84); }
.producto-img { height: auto; aspect-ratio: 4 / 3; }
.producto-img-full { object-fit: cover; }
.producto-body { display: flex; flex-direction: column; min-height: 10rem; }
.producto-footer { margin-top: auto; }
.producto-btn { min-height: 44px; justify-content: center; }
.producto-lightbox-close { width: 44px; height: 44px; }

@media (max-width: 520px) {
    .producto-body { min-height: 9rem; padding: .8rem; }
    .producto-desc { display: none; }
    .producto-footer { flex-direction: column; align-items: stretch; }
    .producto-btn { width: 100%; }
}
</style>
@endpush
@endonce

@once
<script>
function catalogHover(el) {
    var card = el.closest('.producto-card');
    if (!card) return;
    var mainImg = card.querySelector('.producto-img-full');
    if (!mainImg) return;
    if (!mainImg.hasAttribute('data-original-src')) {
        mainImg.setAttribute('data-original-src', mainImg.src);
    }
    mainImg.src = el.src;
}
function catalogUnhover(el) {
    var card = el.closest('.producto-card');
    if (!card) return;
    var mainImg = card.querySelector('.producto-img-full');
    if (!mainImg) return;
    var original = mainImg.getAttribute('data-original-src');
    if (original) {
        mainImg.src = original;
        mainImg.removeAttribute('data-original-src');
    }
}

/* ── Visor de imagen al hacer clic en una miniatura ── */
function productoLightboxEl() {
    var lb = document.getElementById('productoLightbox');
    if (lb) return lb;
    lb = document.createElement('div');
    lb.id = 'productoLightbox';
    lb.className = 'producto-lightbox';
    lb.addEventListener('click', function (e) { if (e.target === lb) cerrarProductoLightbox(); });
    var close = document.createElement('button');
    close.type = 'button';
    close.className = 'producto-lightbox-close';
    close.innerHTML = '&times;';
    close.setAttribute('aria-label', 'Cerrar');
    close.addEventListener('click', cerrarProductoLightbox);
    var img = document.createElement('img');
    img.id = 'productoLightboxImg';
    img.alt = '';
    lb.appendChild(close);
    lb.appendChild(img);
    document.body.appendChild(lb);
    return lb;
}
function abrirProductoLightbox(img, nombre) {
    var src = img.currentSrc || img.src;
    if (!src) return;
    var lb = productoLightboxEl();
    document.getElementById('productoLightboxImg').src = src;
    document.getElementById('productoLightboxImg').alt = nombre || '';
    lb.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function cerrarProductoLightbox() {
    var lb = document.getElementById('productoLightbox');
    if (lb) lb.classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') cerrarProductoLightbox(); });
</script>
@endonce
