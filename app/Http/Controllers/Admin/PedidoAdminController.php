<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoEstado;
use App\Services\BoletaService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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

        $conteos = Pedido::query()
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $totales = collect(array_keys(Pedido::ESTADOS))
            ->mapWithKeys(fn ($estado) => [$estado => (int) ($conteos[$estado] ?? 0)])
            ->all();

        $ventasHoy = Pedido::whereDate('created_at', today())->count();

        return view('admin.pedidos.index', compact('pedidos', 'totales', 'ventasHoy'));
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load(['producto', 'colaborador', 'pagos' => fn ($q) => $q->latest()]);
        return view('admin.pedidos.show', compact('pedido'));
    }

    public function destroy(Pedido $pedido): RedirectResponse
    {
        $pedido->delete();

        return redirect()
            ->route('admin.pedidos.index')
            ->with('success', 'Pedido eliminado correctamente.');
    }

    public function actualizarEstado(Request $request, Pedido $pedido, BoletaService $boletaService): RedirectResponse
    {
        $request->validate([
            'estado'      => 'required|in:pendiente,pago_confirmado,preparando,enviado,en_camino,listo_recojo,completado,cancelado',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $estadoAnterior = $pedido->estado;
        $nuevoEstado    = $request->estado;

        DB::transaction(function () use ($pedido, $estadoAnterior, $nuevoEstado, $request, $boletaService) {
            $pedido->update(['estado' => $nuevoEstado]);

            // Guardar en historial
            $pedido->estados()->create([
                'estado'      => $nuevoEstado,
                'descripcion' => $request->descripcion,
            ]);

            // Confirmación manual del pago: sincronizar pago aprobado y boleta
            if ($nuevoEstado === 'pago_confirmado' && ! $pedido->pagos()->where('estado_pago', 'aprobado')->exists()) {
                $pago = $pedido->pagos()->where('estado_pago', 'pendiente')->latest()->first();

                if ($pago) {
                    $pago->update([
                        'estado_pago'  => 'aprobado',
                        'fecha_pago'   => now(),
                        'aprobado_por' => auth()->id(),
                    ]);

                    $boletaService->generar($pedido, $pago);
                }
            }

            if ($estadoAnterior !== 'completado' && $nuevoEstado === 'completado') {
                $pedido->procesarComision();
            }
        });

        return redirect()
            ->route('admin.pedidos.show', $pedido)
            ->with('success', 'Estado actualizado a "' . $pedido->estado_label . '".');
    }
}
