<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Pago;
use App\Models\MetodoPago;
use App\Http\Requests\StoreComprobanteRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class PagoController extends Controller
{
    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $yape = MetodoPago::where('clave', 'yape')->first();
        $pago = $pedido->pagos()->latest()->first();

        return view('pagos.show', compact('pedido', 'yape', 'pago'));
    }

    public function storeComprobante(Pedido $pedido, StoreComprobanteRequest $request)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($pedido->estado, ['pendiente_pago', 'pendiente_aprobacion'])) {
            return back()->withErrors(['pedido' => 'Este pedido no está pendiente de pago.']);
        }

        $data = [
            'pedido_id'   => $pedido->id,
            'user_id'     => auth()->id(),
            'monto'       => $pedido->adelanto,
            'metodo_pago' => 'yape',
            'numero_operacion' => $request->input('numero_operacion'),
        ];

        if ($request->hasFile('comprobante')) {
            $path = $request->file('comprobante')->store('comprobantes', 'public');
            $data['comprobante_pago'] = $path;
        }

        $pedido->pagos()->create($data);
        $pedido->update(['estado' => 'pendiente_aprobacion']);

        return redirect()->route('pedidos.pago', $pedido)
            ->with('success', 'Comprobante enviado. Espera la aprobación del administrador.');
    }

    public function misPagos()
    {
        $pagos = Pago::where('user_id', auth()->id())
            ->with('pedido.producto')
            ->latest()
            ->get();

        return view('pagos.mis-pagos', compact('pagos'));
    }
}
