<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inversion;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminInversionController extends Controller
{
    public function index(Request $request)
    {
        $query = Inversion::with('proyecto', 'ultimoPago')->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('proyecto_id')) {
            $query->where('proyecto_id', $request->proyecto_id);
        }
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%")
                    ->orWhere('numero_operacion', 'like', "%{$buscar}%");
            });
        }

        $inversiones = $query->paginate(15)->withQueryString();

        $proyectos = Proyecto::where('tipo', 'inversion')->latest()->get();

        $stats = [
            'pendiente' => Inversion::where('estado', 'pendiente')->count(),
            'aprobado'  => Inversion::where('estado', 'aprobado')->sum('monto'),
            'rechazado' => Inversion::where('estado', 'rechazado')->count(),
            'total'     => Inversion::count(),
        ];

        return view('admin.inversiones.index', compact('inversiones', 'proyectos', 'stats'));
    }

    public function show(Inversion $inversion)
    {
        $inversion->load('proyecto', 'metodoPago', 'user', 'ultimoPago');
        return view('admin.inversiones.show', compact('inversion'));
    }

    public function aprobar(Inversion $inversion)
    {
        if ($inversion->estado !== 'pendiente') {
            return back()->withErrors(['inversion' => 'Solo se pueden aprobar inversiones pendientes.']);
        }

        $proyecto = $inversion->proyecto;
        $disponible = (float) $proyecto->inversion_monto_disponible;
        $monto = (float) $inversion->monto;

        if ($monto > $disponible) {
            return back()->withErrors([
                'inversion' => 'El monto de la inversión (' . number_format($monto, 2) . ') supera el disponible (' . number_format($disponible, 2) . ').',
            ]);
        }

        DB::transaction(function () use ($inversion, $proyecto, $disponible, $monto) {
            $this->actualizarFinanciamiento($proyecto, $disponible - $monto);
            $inversion->update(['estado' => 'aprobado']);

            $pago = $inversion->ultimoPago;
            if ($pago && $pago->estado_pago === 'pendiente') {
                $pago->update([
                    'estado_pago'  => 'aprobado',
                    'fecha_pago'   => now(),
                    'aprobado_por' => auth()->id(),
                ]);
            }
        });

        return back()->with('exito', 'Inversión aprobada. El financiamiento del proyecto se actualizó automáticamente.');
    }

    public function rechazar(Request $request, Inversion $inversion)
    {
        if (! in_array($inversion->estado, ['pendiente', 'aprobado'])) {
            return back()->withErrors(['inversion' => 'Esta inversión ya no puede cambiar de estado.']);
        }

        $notas = $request->input('notas_admin');

        DB::transaction(function () use ($inversion, $notas) {
            $proyecto = $inversion->proyecto;

            if ($inversion->estado === 'aprobado') {
                $nuevoDisponible = (float) $proyecto->inversion_monto_disponible + (float) $inversion->monto;
                $this->actualizarFinanciamiento($proyecto, $nuevoDisponible);
            }

            $inversion->update([
                'estado'      => 'rechazado',
                'notas_admin' => $notas ?: null,
            ]);

            $pago = $inversion->ultimoPago;
            if ($pago && in_array($pago->estado_pago, ['pendiente', 'aprobado'])) {
                $pago->update([
                    'estado_pago'         => 'rechazado',
                    'observaciones_admin' => $notas ?: $pago->observaciones_admin,
                    'aprobado_por'        => auth()->id(),
                ]);
            }
        });

        return back()->with('exito', 'Inversión rechazada.');
    }

    public function descargarComprobante(Inversion $inversion)
    {
        if (! $inversion->comprobante || ! Storage::disk('public')->exists($inversion->comprobante)) {
            return back()->withErrors(['inversion' => 'No hay comprobante adjunto.']);
        }

        return Storage::disk('public')->download($inversion->comprobante);
    }

    public function subirContrato(Request $request, Inversion $inversion)
    {
        if ($inversion->estado !== 'aprobado') {
            return back()->withErrors(['inversion' => 'El contrato solo puede subirse cuando la inversión está aprobada.']);
        }

        $request->validate([
            'contrato_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($inversion->contrato_pdf) {
            Storage::disk('public')->delete($inversion->contrato_pdf);
        }

        $inversion->update([
            'contrato_pdf' => $request->file('contrato_pdf')->store('inversiones/contratos', 'public'),
        ]);

        return back()->with('exito', 'Contrato de inversión subido correctamente.');
    }

    public function descargarContrato(Inversion $inversion)
    {
        if (! $inversion->contrato_pdf || ! Storage::disk('public')->exists($inversion->contrato_pdf)) {
            return back()->withErrors(['inversion' => 'No hay contrato adjunto.']);
        }

        return Storage::disk('public')->download($inversion->contrato_pdf);
    }

    private function actualizarFinanciamiento(Proyecto $proyecto, float $nuevoDisponible): void
    {
        $nuevoDisponible = max(0, $nuevoDisponible);

        $porcentaje = 0;
        $total = (float) $proyecto->inversion_monto_total;
        if ($total > 0) {
            $financiado = $total - $nuevoDisponible;
            $porcentaje = (int) round(min(100, ($financiado / $total) * 100));
        }

        $proyecto->update([
            'inversion_monto_disponible'     => number_format($nuevoDisponible, 2, '.', ''),
            'inversion_porcentaje_financiado' => $porcentaje,
        ]);
    }
}
