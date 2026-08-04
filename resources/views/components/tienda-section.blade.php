{{-- resources/views/components/tienda-section.blade.php --}}

<section class="tienda" id="tienda">
    <div class="tienda-bg"></div>
    <div class="tienda-container">

        {{-- Header --}}
        <div class="tienda-header reveal">
            <div>
                <div class="section-label">Productos</div>
                <h2 class="section-title">Nuestra <span>Tienda</span></h2>
            </div>

            <a href="{{ route('tienda.index') }}" class="tienda-ver-mas">
                Ver catálogo completo
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z"/>
                </svg>
            </a>
        </div>

        {{-- Categorías --}}
        <div class="tienda-cats reveal">
            <button class="cat-btn active" onclick="filtrarCat('todos', this)">Todos</button>
            <button class="cat-btn" onclick="filtrarCat('iot', this)">IoT / Electrónica</button>
            <button class="cat-btn" onclick="filtrarCat('software', this)">Software</button>
            <button class="cat-btn" onclick="filtrarCat('kits', this)">Kits</button>
        </div>

        {{-- Productos --}}
        <div class="tienda-grid">

            @forelse($productosHome as $index => $p)

            <div class="producto-card reveal {{ $index > 0 ? 'reveal-delay-'.$index : '' }}"
                 data-cat="{{ $p->categoria }}">

                <div class="producto-img" style="height:190px;position:relative;overflow:hidden;">

                    <div class="producto-img-bg"
                         style="position:absolute;inset:0;z-index:1;">
                        {{ $p->emoji }}
                    </div>

                    @if($p->icono_imagen)

                        @php
                            $imagen = str_starts_with($p->icono_imagen, 'http')
                                ? $p->icono_imagen
                                : asset('storage/'.$p->icono_imagen);
                        @endphp

                        <img
                            src="{{ $imagen }}"
                            alt="{{ $p->nombre }}"
                            style="
                                display:block;
                                width:100%;
                                height:100%;
                                object-fit:cover;
                                position:absolute;
                                inset:0;
                                z-index:5;
                            ">

                    @endif

                    <span class="producto-stock {{ $p->estado }}">
                        {{ ucfirst($p->estado) }}
                    </span>

                    @if($p->descuento_porcentaje)
                        <span class="producto-descuento">
                            -{{ $p->descuento_porcentaje }}%
                        </span>
                    @endif

                </div>

                <div class="producto-body">

                    <div class="producto-cat">
                        {{ ucfirst($p->categoria) }}
                    </div>

                    <div class="producto-nombre">
                        {{ $p->nombre }}
                    </div>

                    <p class="producto-desc">
                        {{ $p->descripcion }}
                    </p>

                    <div class="producto-footer">

                        <div class="producto-precio">

                            <div class="producto-precio-actual">
                                S/ {{ number_format($p->precio,0) }}
                                <span>.{{ substr(number_format($p->precio,2),-2) }}</span>
                            </div>

                            @if($p->precio_anterior)
                                <div class="producto-precio-anterior">
                                    S/ {{ number_format($p->precio_anterior,2) }}
                                </div>
                            @endif

                        </div>

                        @if($p->estado === 'agotado')

                            <button class="producto-btn agotado-btn" disabled>
                                Agotado
                            </button>

                        @else

                            <a href="{{ route('tienda.show', $p->slug) }}"
                               class="producto-btn">

                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 16.1 6.9 18 9 18h12v-2H9.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                                </svg>

                                Comprar

                            </a>

                        @endif

                    </div>

                </div>

            </div>

            @empty

            <p style="color:#888;grid-column:1/-1;text-align:center;padding:3rem">
                No hay productos disponibles aún.
            </p>

            @endforelse

        </div>

        {{-- Métodos de pago --}}
        @if($metodosPago->count())

        <div class="metodos-pago-section reveal">

            <div class="metodos-pago-title">
                💳 Medios de pago aceptados
            </div>

            <div class="metodos-pago-grid">

                @foreach($metodosPago as $mp)

                <div class="metodo-pago-chip" title="{{ $mp->nombre }}">
                    <span class="metodo-chip-emoji">{{ $mp->emoji }}</span>
                    <span>{{ $mp->nombre }}</span>
                </div>

                @endforeach

            </div>

        </div>

        @endif

        {{-- Banner --}}
        <div class="tienda-banner reveal">

            <div class="tienda-banner-text">
                <div class="tienda-banner-title">
                    🎯 ¿Necesitas algo específico?
                </div>

                <p class="tienda-banner-desc">
                    Fabricamos dispositivos electrónicos y desarrollamos software
                    <strong>completamente a medida</strong>.
                </p>
            </div>

            <a href="{{ url('/contacto') }}"
               class="hero-btn-primary"
               style="display:inline-flex;flex-shrink:0;">

                <svg viewBox="0 0 24 24" fill="currentColor" style="width:17px;height:17px">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>

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
        card.style.display =
            (cat === 'todos' || card.dataset.cat === cat) ? '' : 'none';
    });
}
</script>