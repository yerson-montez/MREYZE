<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use App\Http\Requests\AprobarPagoRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PagoAdminController extends Controller
{
    public function index(): View
    {
        $pagos = Pago::with(['pedido.producto', 'user'])
            ->latest()
            ->paginate(20);

        $pendientes = Pago::where('estado_pago', 'pendiente')->count();

        return view('admin.pagos.index', compact('pagos', 'pendientes'));
    }

    public function show(Pago $pago): View
    {
        $pago->load(['pedido.producto', 'user', 'aprobador']);
        return view('admin.pagos.show', compact('pago'));
    }

    public function aprobar(Pago $pago, AprobarPagoRequest $request): RedirectResponse
    {
        $accion = $request->input('accion');
        $nuevoEstado = $accion === 'aprobar' ? 'aprobado' : 'rechazado';

        $pago->update([
            'estado_pago'         => $nuevoEstado,
            'observaciones_admin' => $request->input('observaciones_admin'),
            'fecha_pago'          => $accion === 'aprobar' ? now() : null,
            'aprobado_por'        => auth()->id(),
        ]);

        if ($accion === 'aprobar') {
            $pago->pedido->update(['estado' => 'pagado_parcial']);
        }

        $mensaje = $accion === 'aprobar'
            ? 'Pago aprobado correctamente.'
            : 'Pago rechazado.';

        return redirect()->route('admin.pagos.index')->with('success', $mensaje);
    }
}
