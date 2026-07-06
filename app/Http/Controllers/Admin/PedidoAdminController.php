<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoEstado;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PedidoAdminController extends Controller
{
    public function index(Request $request): View
    {
        $pedidos = Pedido::with(['producto', 'colaborador', 'ultimoPago'])
            ->when($request->estado, fn ($q, $e) => $q->where('estado', $e))
            ->when($request->search, function ($q, $s) {
                $q->where('cliente_nombre', 'like', "%{$s}%")
                  ->orWhere('cliente_telefono', 'like', "%{$s}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totales = [
            'pendiente_pago'       => Pedido::where('estado', 'pendiente_pago')->count(),
            'pendiente_aprobacion' => Pedido::where('estado', 'pendiente_aprobacion')->count(),
            'pagado_parcial'       => Pedido::where('estado', 'pagado_parcial')->count(),
            'preparando'           => Pedido::where('estado', 'preparando')->count(),
            'en_proceso_envio'     => Pedido::where('estado', 'en_proceso_envio')->count(),
            'enviado'              => Pedido::where('estado', 'enviado')->count(),
            'entregado'            => Pedido::where('estado', 'entregado')->count(),
            'cancelado'            => Pedido::where('estado', 'cancelado')->count(),
        ];

        $ventasHoy = Pedido::whereDate('created_at', today())->count();

        return view('admin.pedidos.index', compact('pedidos', 'totales', 'ventasHoy'));
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load(['producto', 'colaborador', 'pagos' => fn ($q) => $q->latest()]);
        return view('admin.pedidos.show', compact('pedido'));
    }

    public function actualizarEstado(Request $request, Pedido $pedido): RedirectResponse
    {
        $request->validate([
            'estado'      => 'required|in:pendiente_pago,pendiente_aprobacion,pagado_parcial,preparando,en_proceso_envio,enviado,entregado,cancelado',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $estadoAnterior = $pedido->estado;
        $nuevoEstado    = $request->estado;

        $pedido->update(['estado' => $nuevoEstado]);

        // Guardar en historial
        $pedido->estados()->create([
            'estado'      => $nuevoEstado,
            'descripcion' => $request->descripcion,
        ]);

        if ($estadoAnterior !== 'entregado' && $nuevoEstado === 'entregado') {
            $pedido->procesarComision();
        }

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with('success', 'Estado actualizado a "' . $pedido->estado_label . '".');
    }
}
