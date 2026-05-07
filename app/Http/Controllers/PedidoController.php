<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class PedidoController extends Controller
{
    public function create(Producto $producto)
    {
        $adelanto = round($producto->precio * 0.50, 2);
        $saldo    = round($producto->precio - $adelanto, 2);

        return view('pedidos.create', compact('producto', 'adelanto', 'saldo'));
    }

    public function store(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'cliente_nombre'    => 'required|string|max:255',
            'cliente_telefono'  => 'required|string|max:20',
            'cliente_email'     => 'required|email|max:255',
            'departamento'      => 'required|string|max:100',
            'provincia'         => 'required|string|max:100',
            'distrito'          => 'required|string|max:100',
            'cliente_direccion' => 'required|string|max:500',
        ]);

        $total    = $producto->precio;
        $adelanto = round($total * 0.50, 2);
        $saldo    = round($total - $adelanto, 2);

        // Dirección completa en un solo campo (tu migración tiene cliente_direccion)
        $direccionCompleta = $datos['departamento'] . ', '
                           . $datos['provincia'] . ', '
                           . $datos['distrito'] . ' — '
                           . $datos['cliente_direccion'];

        $pedido = Pedido::create([
            'producto_id'      => $producto->id,
            'user_id'          => auth()->id(),
            'cliente_nombre'   => $datos['cliente_nombre'],
            'cliente_telefono' => $datos['cliente_telefono'],
            'cliente_direccion'=> $direccionCompleta,
            'total'            => $total,      // ← tu migración usa "total"
            'adelanto'         => $adelanto,
            'saldo'            => $saldo,
            'estado'           => 'pendiente',
        ]);

        // Mercado Pago
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $client = new PreferenceClient();

        $preference = $client->create([
            'items' => [[
                'id'          => (string) $pedido->id,
                'title'       => 'Adelanto — ' . $producto->nombre,
                'description' => 'Pedido #' . $pedido->id,
                'quantity'    => 1,
                'unit_price'  => (float) $adelanto,
                'currency_id' => 'PEN',
            ]],
            'payer' => [
                'name'  => $datos['cliente_nombre'],
                'email' => $datos['cliente_email'],
                'phone' => ['number' => $datos['cliente_telefono']],
            ],
            'back_urls' => [
                'success' => route('pedidos.exitoso'),
                'failure' => route('pedidos.fallido'),
                'pending' => route('pedidos.pendiente'),
            ],
            'auto_return'        => 'approved',
            'external_reference' => (string) $pedido->id,
        ]);

        return redirect($preference->sandbox_init_point);
        // Producción: $preference->init_point
    }

    public function exitoso(Request $request)
    {
        $pedidoId = $request->query('external_reference');
        if ($pedidoId) {
            Pedido::where('id', $pedidoId)->update(['estado' => 'confirmado']); // tu enum usa 'confirmado'
        }
        return view('pedidos.exitoso', ['pedidoId' => $pedidoId]);
    }

    public function fallido()
    {
        return view('pedidos.fallido');
    }

    public function pendiente()
    {
        return view('pedidos.pendiente');
    }

    public function misPedidos()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->with('producto')
            ->latest()
            ->get();
        return view('pedidos.mis-pedidos', compact('pedidos'));
    }

    public function confirmacion(Pedido $pedido)
    {
        return view('pedidos.confirmacion', compact('pedido'));
    }

    public function pagar(Request $request, Pedido $pedido) {}
    public function confirmarYape(Request $request) {}
    public function pagarCulqi(Request $request) {}
}