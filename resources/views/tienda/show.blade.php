@extends('welcome')

@section('title', $producto->nombre)

@section('content')
<div style="max-width:900px;margin:auto;padding:40px;">

    <!-- Nombre -->
    <h1 style="font-size:2rem;font-weight:bold;">
        {{ $producto->nombre }}
    </h1>

    <!-- Categoría -->
    <p style="color:#00d4ff;">
        {{ strtoupper($producto->categoria) }}
    </p>

    <hr style="margin:20px 0;">

    <!-- Descripción -->
    <p style="font-size:1rem;line-height:1.6;">
        {{ $producto->descripcion }}
    </p>

    <!-- Precio -->
    <div style="margin-top:20px;">
        <h2 style="font-size:1.8rem;">
            S/ {{ number_format($producto->precio, 2) }}
        </h2>

        @if($producto->precio_anterior)
            <span style="text-decoration:line-through;color:gray;">
                S/ {{ $producto->precio_anterior }}
            </span>
        @endif
    </div>

    <br>

    <!-- Estado -->
    <p>
        Estado:
        <strong>{{ ucfirst($producto->estado) }}</strong>
    </p>

    <hr>

    <!-- Métodos de pago -->
    <h3>Métodos de pago disponibles:</h3>
    <ul>
        @foreach($metodosPago as $metodo)
            <li>{{ $metodo->nombre }}</li>
        @endforeach
    </ul>

    <br>

    <!-- BOTÓN COMPRAR -->
    @if($producto->estado !== 'agotado')
        <a href="{{ route('pedidos.create', $producto) }}"
           style="display:inline-block;
                  padding:12px 25px;
                  background:#2563eb;
                  color:white;
                  border-radius:8px;
                  text-decoration:none;">
            Comprar ahora
        </a>
    @else
        <button disabled style="padding:12px 25px;background:gray;color:white;border-radius:8px;">
            Producto agotado
        </button>
    @endif

</div>
@endsection