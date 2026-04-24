@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">

    <h1 class="text-2xl font-semibold mb-6">Mis pedidos</h1>

    @foreach($pedidos as $pedido)
        <div class="bg-white border rounded-xl p-5 mb-4">

            <div class="flex justify-between mb-2">
                <h2 class="font-semibold">Pedido #{{ $pedido->id }}</h2>

                <span class="text-sm px-3 py-1 rounded-full
                    {{ $pedido->estado == 'pendiente' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                    {{ $pedido->estado }}
                </span>
            </div>

            <p class="text-sm text-gray-500 mb-2">
                Producto: {{ $pedido->producto->nombre }}
            </p>

            <div class="grid grid-cols-3 text-center text-sm mb-4">
                <div>
                    <p>Total</p>
                    <strong>S/ {{ number_format($pedido->total, 2) }}</strong>
                </div>

                <div>
                    <p>Adelanto</p>
                    <strong class="text-green-600">
                        S/ {{ number_format($pedido->adelanto, 2) }}
                    </strong>
                </div>

                <div>
                    <p>Saldo</p>
                    <strong class="text-amber-600">
                        S/ {{ number_format($pedido->saldo, 2) }}
                    </strong>
                </div>
            </div>

            @if(!$pedido->pagado)
                <form action="{{ route('pedidos.pagar', $pedido) }}" method="POST">
                    @csrf
                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg">
                        Pagar adelanto
                    </button>
                </form>
            @else
                <p class="text-green-600 text-center font-medium">
                    ✔ Pagado
                </p>
            @endif

        </div>
    @endforeach

</div>
@endsection