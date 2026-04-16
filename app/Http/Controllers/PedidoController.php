<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PedidoController extends Controller
{
    /**
     * Formulario de pedido para un producto.
     */
    public function create(Producto $producto): View
    {
        abort_if(! $producto->activo ?? false, 404); // si tienes campo activo

        $adelanto = round($producto->precio * 0.5, 2);
        $saldo    = $producto->precio - $adelanto;

        return view('pedidos.create', compact('producto', 'adelanto', 'saldo'));
    }

    /**
     * Almacena el nuevo pedido.
     */
    public function store(Request $request, Producto $producto): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_nombre'    => 'required|string|max:255',
            'cliente_telefono'  => 'required|string|max:20',
            'cliente_direccion' => 'required|string|max:500',
        ]);

        // Detectar colaborador desde cookie
        $colaboradorId = null;
        $codigoRef = $request->cookie('ref_colaborador');

        if ($codigoRef) {
            $colaborador   = Colaborador::where('codigo_ref', $codigoRef)
                                        ->where('activo', true)
                                        ->first();
            $colaboradorId = $colaborador?->id;
        }

        $pedido = new Pedido(array_merge($validated, [
            'producto_id'    => $producto->id,
            'colaborador_id' => $colaboradorId,
            'total'          => $producto->precio,
        ]));

        $pedido->calcularPagos();
        $pedido->save();

        return redirect()
            ->route('pedidos.confirmacion', $pedido)
            ->with('success', '¡Pedido realizado con éxito!');
    }

    /**
     * Confirmación del pedido para el cliente.
     */
    public function confirmacion(Pedido $pedido): View
    {
        return view('pedidos.confirmacion', compact('pedido'));
    }
}