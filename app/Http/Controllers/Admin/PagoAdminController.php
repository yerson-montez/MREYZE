<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Http\Requests\AprobarPagoRequest;
use App\Services\BoletaService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function aprobar(Pago $pago, AprobarPagoRequest $request, BoletaService $boletaService): RedirectResponse
    {
        $accion = $request->input('accion');

        try {
            DB::transaction(function () use ($pago, $accion, $request, $boletaService) {
                $nuevoEstado = $accion === 'aprobar' ? 'aprobado' : 'rechazado';

                $pago->update([
                    'estado_pago'         => $nuevoEstado,
                    'observaciones_admin' => $request->input('observaciones_admin'),
                    'fecha_pago'          => $accion === 'aprobar' ? now() : null,
                    'aprobado_por'        => auth()->id(),
                ]);

                if ($accion === 'aprobar') {
                    $pago->pedido->update(['estado' => 'pagado_parcial']);

                    $boleta = $boletaService->generar($pago->pedido, $pago);
                    if (! $boleta) {
                        throw new \RuntimeException('No se pudo generar la boleta automáticamente. Revise los logs para más detalles.');
                    }
                }
            });

            $mensaje = $accion === 'aprobar'
                ? 'Pago aprobado y boleta generada correctamente.'
                : 'Pago rechazado.';

            return redirect()->route('admin.pagos.index')->with('success', $mensaje);
        } catch (\Throwable $e) {
            Log::error('Error al aprobar pago', [
                'pago_id' => $pago->id,
                'accion'  => $accion,
                'error'   => $e->getMessage(),
                'class'   => get_class($e),
            ]);

            return redirect()
                ->route('admin.pagos.show', $pago)
                ->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }
}
