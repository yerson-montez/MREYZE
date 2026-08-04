<section class="py-10 bg-gray-100">
    <div class="container mx-auto px-4">

        <h2 class="text-3xl font-bold text-center mb-8">
            🛒 Tienda MReyze
        </h2>

        @if(isset($productos) && $productos->count() > 0)

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

                @foreach($productos as $producto)

                    <div class="bg-white rounded-lg shadow p-4">

                        {{-- IMAGEN DEL PRODUCTO --}}
                        @if(!empty($producto->icono_imagen))

                            <img 
                                src="{{ $producto->icono_imagen }}"
                                class="w-full h-40 object-cover rounded"
                                alt="{{ $producto->nombre }}"
                            >

                        @else

                            <div class="w-full h-40 bg-gray-200 rounded flex items-center justify-center">
                                Sin imagen
                            </div>

                        @endif


                        {{-- NOMBRE --}}
                        <h3 class="font-bold text-lg mt-3">
                            {{ $producto->nombre }}
                        </h3>


                        {{-- DESCRIPCIÓN --}}
                        <p class="text-gray-600 text-sm mt-2">
                            {{ $producto->descripcion }}
                        </p>


                        {{-- PRECIO --}}
                        <p class="text-xl font-bold text-green-600 mt-3">
                            S/ {{ number_format($producto->precio, 2) }}
                        </p>


                        {{-- STOCK --}}
                        <p class="text-sm mt-2">
                            Stock:
                            <span class="font-bold">
                                {{ $producto->stock }}
                            </span>
                        </p>


                        {{-- BOTÓN --}}
                        <a href="{{ route('tienda') }}"
                           class="mt-4 block bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded">
                            Ver producto
                        </a>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-center text-gray-500">
                Aún no hay productos disponibles.
            </p>

        @endif

    </div>
</section>