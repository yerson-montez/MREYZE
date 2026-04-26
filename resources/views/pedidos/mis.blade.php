@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">

    <h1 class="text-2xl font-semibold mb-6">Mis pedidos</h1>

    {{-- RESUMEN TOTAL --}}
    @php
        $totalBruto   = $pedidos->sum('total');
        $totalPagado  = $pedidos->sum('adelanto');
        $totalSaldo   = $pedidos->sum('saldo');
        $countActivos = $pedidos->where('pagado', false)->count();
    @endphp

    <div class="bg-gray-50 border rounded-xl p-5 mb-6">
        <p class="text-sm text-gray-500 mb-3">Resumen total — {{ $countActivos }} pedido(s) pendientes</p>
        <div class="flex justify-between text-sm mb-1">
            <span>Total bruto</span>
            <span>S/ {{ number_format($totalBruto, 2) }}</span>
        </div>
        <div class="flex justify-between text-sm mb-1">
            <span>Adelantos pagados</span>
            <span class="text-green-600">− S/ {{ number_format($totalPagado, 2) }}</span>
        </div>
        <div class="flex justify-between font-semibold text-base border-t pt-2 mt-2">
            <span>Saldo pendiente</span>
            <span class="text-amber-600">S/ {{ number_format($totalSaldo, 2) }}</span>
        </div>
    </div>

    {{-- LISTA DE PEDIDOS --}}
    @foreach($pedidos as $pedido)
        <div class="bg-white border rounded-xl p-5 mb-4">

            <div class="flex justify-between mb-2">
                <h2 class="font-semibold">Pedido #{{ $pedido->id }}</h2>
                <span class="text-sm px-3 py-1 rounded-full
                    {{ $pedido->estado == 'pendiente' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                    {{ $pedido->estado }}
                </span>
            </div>

            <p class="text-sm text-gray-500 mb-3">
                Producto: {{ $pedido->producto->nombre }}
            </p>

            <div class="grid grid-cols-3 text-center text-sm mb-4">
                <div>
                    <p class="text-gray-500">Total</p>
                    <strong>S/ {{ number_format($pedido->total, 2) }}</strong>
                </div>
                <div>
                    <p class="text-gray-500">Adelanto</p>
                    <strong class="text-green-600">S/ {{ number_format($pedido->adelanto, 2) }}</strong>
                </div>
                <div>
                    <p class="text-gray-500">Saldo</p>
                    <strong class="text-amber-600">S/ {{ number_format($pedido->saldo, 2) }}</strong>
                </div>
            </div>

            @if(!$pedido->pagado)
                {{-- Botón que abre el modal de pago --}}
                <button
                    onclick="abrirModalPago({{ $pedido->id }}, '{{ number_format($pedido->saldo, 2) }}')"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Pagar adelanto — S/ {{ number_format($pedido->saldo, 2) }}
                </button>
            @else
                <p class="text-green-600 text-center font-medium">✔ Pagado</p>
            @endif

        </div>
    @endforeach
</div>

{{-- ===== MODAL DE PAGO ===== --}}
<div id="modal-pago" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 p-6 shadow-xl">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Pagar adelanto</h3>
            <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>

        <p class="text-center text-gray-600 mb-4">
            Monto: <strong id="modal-monto" class="text-blue-700"></strong>
        </p>

        {{-- Tabs --}}
        <div class="flex gap-2 mb-5">
            <button onclick="setTab('yape')" id="tab-btn-yape"
                class="flex-1 py-2 rounded-lg border text-sm font-medium transition tab-activo">
                Yape
            </button>
            <button onclick="setTab('tarjeta')" id="tab-btn-tarjeta"
                class="flex-1 py-2 rounded-lg border text-sm font-medium transition tab-inactivo">
                Tarjeta
            </button>
        </div>

        {{-- TAB YAPE --}}
        <div id="panel-yape">
            <div class="text-center">
                <img src="{{ asset('images/yape-qr.png') }}" alt="QR Yape"
                     class="w-40 h-40 mx-auto rounded-xl border mb-3">
                <p class="text-gray-500 text-sm">O transfiere al número</p>
                <p class="text-2xl font-bold text-purple-700 my-1">+51 999 888 777</p>
                <p class="text-gray-500 text-sm mb-3">Tienda Online S.A.C.</p>
                <p class="text-xs text-gray-400">
                    Envía el voucher por WhatsApp o súbelo aquí:
                </p>
            </div>

            <form id="form-yape" action="{{ route('pedidos.confirmar-yape') }}" method="POST"
                  enctype="multipart/form-data" class="mt-3">
                @csrf
                <input type="hidden" name="pedido_id" id="yape-pedido-id">
                <input type="file" name="voucher" accept="image/*"
                       class="w-full border rounded-lg p-2 text-sm mb-3">
                <button type="submit"
                    class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                    Enviar voucher
                </button>
            </form>
        </div>

        {{-- TAB TARJETA (Culqi) --}}
        <div id="panel-tarjeta" class="hidden">
            <form id="form-culqi" action="{{ route('pedidos.pagar-culqi') }}" method="POST">
                @csrf
                <input type="hidden" name="pedido_id" id="culqi-pedido-id">
                <input type="hidden" name="culqi_token" id="culqi-token">

                <div class="mb-3">
                    <label class="text-xs text-gray-500 block mb-1">Número de tarjeta</label>
                    <input type="text" id="card-number" placeholder="4111 1111 1111 1111" maxlength="19"
                           class="w-full border rounded-lg p-2 text-sm" oninput="formatCard(this)">
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Vencimiento</label>
                        <input type="text" id="card-exp" placeholder="MM/AA" maxlength="5"
                               class="w-full border rounded-lg p-2 text-sm" oninput="formatExp(this)">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">CVV</label>
                        <input type="text" id="card-cvv" placeholder="123" maxlength="4"
                               class="w-full border rounded-lg p-2 text-sm">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="text-xs text-gray-500 block mb-1">Nombre en tarjeta</label>
                    <input type="text" id="card-name" placeholder="Juan Pérez"
                           class="w-full border rounded-lg p-2 text-sm">
                </div>

                <button type="button" onclick="procesarCulqi()"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Pagar con tarjeta
                </button>
            </form>
        </div>

    </div>
</div>

{{-- Culqi JS --}}
<script src="https://checkout.culqi.com/js/v4"></script>
<script>
    const CULQI_PUBLIC_KEY = '{{ config("services.culqi.public_key") }}';
    let pedidoActualId = null;

    // Abrir modal
    function abrirModalPago(pedidoId, monto) {
        pedidoActualId = pedidoId;
        document.getElementById('modal-monto').textContent = 'S/ ' + monto;
        document.getElementById('yape-pedido-id').value = pedidoId;
        document.getElementById('culqi-pedido-id').value = pedidoId;
        document.getElementById('modal-pago').classList.remove('hidden');
        document.getElementById('modal-pago').classList.add('flex');
        setTab('yape');
    }

    function cerrarModal() {
        document.getElementById('modal-pago').classList.add('hidden');
        document.getElementById('modal-pago').classList.remove('flex');
    }

    // Tabs
    function setTab(tab) {
        const esYape = tab === 'yape';
        document.getElementById('panel-yape').classList.toggle('hidden', !esYape);
        document.getElementById('panel-tarjeta').classList.toggle('hidden', esYape);
        document.getElementById('tab-btn-yape').className =
            'flex-1 py-2 rounded-lg border text-sm font-medium transition ' +
            (esYape ? 'bg-purple-600 text-white border-purple-600' : 'border-gray-300 text-gray-500');
        document.getElementById('tab-btn-tarjeta').className =
            'flex-1 py-2 rounded-lg border text-sm font-medium transition ' +
            (!esYape ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 text-gray-500');
    }

    // Formatear tarjeta
    function formatCard(el) {
        el.value = el.value.replace(/\D/g,'').replace(/(.{4})/g,'$1 ').trim().slice(0,19);
    }
    function formatExp(el) {
        el.value = el.value.replace(/\D/g,'').replace(/^(\d{2})(\d)/,'$1/$2').slice(0,5);
    }

    // Procesar Culqi
    function procesarCulqi() {
        const [mes, anio] = document.getElementById('card-exp').value.split('/');
        Culqi.setKey(CULQI_PUBLIC_KEY);
        Culqi.createToken({
            card_number: document.getElementById('card-number').value.replace(/\s/g,''),
            cvv: document.getElementById('card-cvv').value,
            expiration_month: mes,
            expiration_year: '20' + anio,
            email: '{{ auth()->user()->email }}'
        });
    }

    // Callback de Culqi
    window.culqi = function () {
        if (Culqi.token) {
            document.getElementById('culqi-token').value = Culqi.token.id;
            document.getElementById('form-culqi').submit();
        } else {
            alert('Error: ' + Culqi.error.user_message);
        }
    };

    // Cerrar al hacer click fuera
    document.getElementById('modal-pago').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
</script>
@endsection