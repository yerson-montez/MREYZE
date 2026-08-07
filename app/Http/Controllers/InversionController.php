<?php

namespace App\Http\Controllers;

use App\Enums\EstadoValidacionOcr;
use App\Jobs\ProcesarComprobanteJob;
use App\Models\Inversion;
use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Proyecto;
use App\Rules\ComprobanteValido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InversionController extends Controller
{
    public function create(Proyecto $proyecto)
    {
        abort_unless($proyecto->visible && $proyecto->tipo === 'inversion', 404);

        $proyecto->load(['imagenes' => fn ($q) => $q->orderBy('orden')]);

        $metodosPago = MetodoPago::activos()->get();

        $activos  = Proyecto::inversion()->visibles()->where('id', '!=', $proyecto->id)
            ->where('inversion_estado', 'disponible')->latest()->get();
        $completados = Proyecto::inversion()->visibles()->where('id', '!=', $proyecto->id)
            ->where('inversion_estado', 'completado')->latest()->get();

        return view('inversiones.create', compact('proyecto', 'metodosPago', 'activos', 'completados'));
    }

    public function store(Request $request, Proyecto $proyecto)
    {
        abort_unless($proyecto->visible && $proyecto->tipo === 'inversion', 404);

        $data = $request->validate([
            'nombre'             => 'required|string|max:200',
            'email'              => 'required|email|max:200',
            'telefono'           => 'nullable|string|max:30',
            'dni'                => 'nullable|string|max:12',
            'monto'              => 'required|numeric|min:50',
            'metodo_pago_id'     => 'required|exists:metodos_pago,id',
            'numero_operacion'   => 'nullable|string|max:100',
            'comprobante'        => ['nullable', 'file', new ComprobanteValido],
        ], [
            'monto.min' => 'El monto mínimo para invertir es S/ 50.00.',
        ]);

        $metodo = MetodoPago::activos()->whereKey($data['metodo_pago_id'])->first();
        if (! $metodo) {
            return back()->withErrors(['metodo_pago_id' => 'El método de pago seleccionado no está disponible.'])->withInput();
        }

        if ($proyecto->inversion_estado !== 'disponible') {
            return back()->withErrors(['monto' => 'Este proyecto ya no acepta nuevas inversiones.'])->withInput();
        }

        $disponible = (float) $proyecto->inversion_monto_disponible;
        if ((float) $data['monto'] > $disponible) {
            return back()->withErrors([
                'monto' => 'El monto supera el disponible (' . number_format($disponible, 2) . '). Ingresa un monto menor.',
            ])->withInput();
        }

        if ($request->hasFile('comprobante')) {
            $data['comprobante'] = $request->file('comprobante')->store('inversiones', 'public');
        }

        $data['proyecto_id'] = $proyecto->id;
        $data['user_id'] = $request->user()?->id;

        $inversion = null;
        $pago = null;

        DB::transaction(function () use ($data, $metodo, &$inversion, &$pago) {
            $inversion = Inversion::create($data);

            $pago = Pago::create([
                'inversion_id'     => $inversion->id,
                'user_id'          => $data['user_id'],
                'monto'            => $data['monto'],
                'metodo_pago'      => $metodo->clave,
                'numero_operacion' => $data['numero_operacion'] ?? null,
                'comprobante_pago' => $data['comprobante'] ?? null,
                'ocr_resultado'    => isset($data['comprobante'])
                    ? EstadoValidacionOcr::Pendiente->value
                    : null,
                'estado_pago'      => 'pendiente',
            ]);
        });

        if ($pago && $pago->comprobante_pago) {
            ProcesarComprobanteJob::dispatch($pago);
        }

        return redirect()->route('inversiones.gracias', $proyecto)
            ->with('inversion', $inversion->id);
    }

    public function gracias(Proyecto $proyecto, Request $request)
    {
        abort_unless($proyecto->visible && $proyecto->tipo === 'inversion', 404);

        $inversionId = $request->session()->pull('inversion');
        $inversion = $inversionId
            ? Inversion::where('id', $inversionId)->where('proyecto_id', $proyecto->id)->with('ultimoPago')->first()
            : null;

        if (! $inversion) {
            return redirect()->route('proyectos.show', $proyecto);
        }

        return view('inversiones.gracias', compact('inversion'));
    }

    public function misInversiones()
    {
        $user = auth()->user();

        // Vincular al usuario las inversiones registradas como invitado
        // con su mismo email (relación usuario-inversión).
        Inversion::whereNull('user_id')
            ->whereRaw('LOWER(email) = ?', [mb_strtolower($user->email)])
            ->update(['user_id' => $user->id]);

        $inversiones = Inversion::with('proyecto', 'metodoPago', 'ultimoPago')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('inversiones.mis', compact('inversiones'));
    }

    public function descargarContrato(Inversion $inversion)
    {
        $user = auth()->user();

        if ($inversion->user_id !== $user->id && strtolower($inversion->email ?? '') !== strtolower($user->email)) {
            abort(403);
        }

        if (! $inversion->contrato_pdf || ! Storage::disk('public')->exists($inversion->contrato_pdf)) {
            return back()->withErrors(['inversion' => 'No hay contrato disponible.']);
        }

        return Storage::disk('public')->download($inversion->contrato_pdf);
    }
}
