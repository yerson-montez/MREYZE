@forelse($productos as $p)
<a href="{{ route('tienda.show', $p->slug) }}"
   class="producto-card reveal"
   data-cat="{{ $p->categoria }}"
   style="text-decoration:none;color:inherit;display:block">

    <div class="producto-img">
    @php $imgUrl = $p->imagen_principal_url; @endphp
    @if($imgUrl)
        <img src="{{ $imgUrl }}"
             alt="{{ $p->nombre }}"
             class="producto-img-full"
             loading="lazy">
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

    <div class="producto-body">
        <div class="producto-cat">{{ strtoupper($p->categoria) }}</div>
        <div class="producto-nombre">{{ $p->nombre }}</div>
        <p class="producto-desc">{{ Str::limit($p->descripcion, 80) }}</p>
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
