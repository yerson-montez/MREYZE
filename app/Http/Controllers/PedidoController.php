<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;

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

        $direccionCompleta = $datos['departamento'] . ', '
                           . $datos['provincia'] . ', '
                           . $datos['distrito'] . ' — '
                           . $datos['cliente_direccion'];

        $pedido = Pedido::create([
            'producto_id'      => $producto->id,
            'user_id'          => auth()->id(),
            'cliente_nombre'   => $datos['cliente_nombre'],
            'cliente_telefono' => $datos['cliente_telefono'],
            'cliente_email'    => $datos['cliente_email'],
            'cliente_direccion'=> $direccionCompleta,
            'total'            => $total,
            'adelanto'         => $adelanto,
            'saldo'            => $saldo,
            'estado'           => 'pendiente',
        ]);

        return redirect()->route('pedidos.pago', $pedido);
    }

    public function misPedidos()
    {
        $pedidos = Pedido::where('user_id', auth()->id())
            ->with([
                'producto',
                'pagos'  => fn ($q) => $q->latest(),
                'boletas',
            ])
            ->latest()
            ->get();

        return view('pedidos.mis', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $pedido->load([
            'producto',
            'pagos' => fn ($q) => $q->latest(),
            'estados',
            'boletas',
        ]);

        return view('pedidos.show', compact('pedido'));
    }

    public function cancelar(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($pedido->estado, ['pendiente', 'pago_confirmado'])) {
            return back()->withErrors(['pedido' => 'Este pedido ya no puede ser cancelado.']);
        }

        $pedido->update(['estado' => 'cancelado']);

        $pedido->estados()->create([
            'estado'      => 'cancelado',
            'descripcion' => 'Cancelado por el cliente.',
        ]);

        return redirect()->route('pedidos.mis')
            ->with('success', 'Tu pedido fue cancelado correctamente.');
    }
}
