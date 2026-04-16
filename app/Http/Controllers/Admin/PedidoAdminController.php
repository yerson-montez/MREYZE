<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PedidoAdminController extends Controller
{
    public function index(Request $request): View
    {
        $pedidos = Pedido::with(['producto', 'colaborador'])
            ->when($request->estado, fn ($q, $e) => $q->where('estado', $e))
            ->when($request->search, function ($q, $s) {
                $q->where('cliente_nombre', 'like', "%{$s}%")
                  ->orWhere('cliente_telefono', 'like', "%{$s}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totales = [
            'pendientes'  => Pedido::where('estado', 'pendiente')->count(),
            'confirmados' => Pedido::where('estado', 'confirmado')->count(),
            'entregados'  => Pedido::where('estado', 'entregado')->count(),
            'cancelados'  => Pedido::where('estado', 'cancelado')->count(),
        ];

        $ventasHoy = Pedido::whereDate('created_at', today())->count();

        return view('admin.pedidos.index', compact('pedidos', 'totales', 'ventasHoy'));
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load(['producto', 'colaborador']);
        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Actualiza el estado y dispara comisión si aplica.
     */
    public function actualizarEstado(Request $request, Pedido $pedido): RedirectResponse
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,entregado,cancelado',
        ]);

        $estadoAnterior = $pedido->estado;
        $pedido->update(['estado' => $request->estado]);

        // Comisión solo al pasar a "entregado" por primera vez
        if ($estadoAnterior !== 'entregado' && $request->estado === 'entregado') {
            $pedido->procesarComision();
        }

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with('success', 'Estado actualizado a "' . ucfirst($request->estado) . '".');
    }
}