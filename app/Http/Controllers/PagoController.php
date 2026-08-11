<?php

namespace App\Http\Controllers;

use App\Enums\EstadoValidacionOcr;
use App\Http\Requests\StoreComprobanteRequest;
use App\Jobs\ProcesarComprobanteJob;
use App\Models\Boleta;
use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Pedido;
use App\Services\TesseractOcrService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PagoController extends Controller
{
    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        // Pedido pagado al 100%: ya no se muestra la pantalla de pago,
        // únicamente la boleta/ticket de pago.
        if ((float) $pedido->total_pagado >= (float) $pedido->total && $pedido->boletas()->exists()) {
            return redirect()->route('pedidos.boleta', $pedido);
        }

        $metodosPago = MetodoPago::activos()->get();
        $yape = $metodosPago->firstWhere('clave', 'yape') ?? MetodoPago::where('clave', 'yape')->first();
        $pago = $pedido->pagos()->latest()->first();
        $boleta = $pedido->boletas()->first();

        $totalPagado    = $pedido->total_pagado;
        $saldoPendiente = round(max((float) $pedido->total - $totalPagado, 0), 2);
        $montoPago      = $totalPagado > 0 ? $saldoPendiente : (float) $pedido->adelanto;
        $tipoPago       = $totalPagado > 0 ? 'saldo' : 'adelanto';

        $pagoEnRevision = $pago && in_array($pago->ocr_resultado, [
            'pendiente',
            'procesando',
            'validado',
            'error_ocr',
            'ocr_fallido',
            'rechazado',
            'confianza_baja',
            'sospechoso',
        ]);

        $puedePagarSaldo = in_array($pedido->estado, ['pendiente', 'pago_confirmado'])
            && $totalPagado > 0
            && $saldoPendiente > 0
            && ! $pagoEnRevision;

        $currentEstado = match ($pedido->estado) {
            'pago_confirmado' => 'aprobado',
            'preparando',
            'enviado',
            'en_camino',
            'listo_recojo'  => 'procesando',
            'completado'    => 'completado',
            'cancelado'     => 'cancelado',
            default         => 'pendiente',
        };

        $currentOcr = match ($pago?->ocr_resultado) {
            'validado'      => 'completado',
            'procesando'    => 'procesando',
            default         => 'pendiente',
        };

        return view('pagos.show', compact(
            'pedido',
            'yape',
            'pago',
            'boleta',
            'currentEstado',
            'currentOcr',
            'metodosPago',
            'saldoPendiente',
            'montoPago',
            'tipoPago',
            'puedePagarSaldo',
        ));
    }

    public function storeComprobante(Pedido $pedido, StoreComprobanteRequest $request)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($pedido->estado, ['pendiente', 'pago_confirmado'])) {
            return back()->withErrors(['pedido' => 'Este pedido ya no admite pagos en su estado actual.']);
        }

        $totalPagado    = $pedido->total_pagado;
        $saldoPendiente = round(max((float) $pedido->total - $totalPagado, 0), 2);

        if ($saldoPendiente <= 0) {
            return back()->withErrors(['pedido' => 'Este pedido no tiene saldo pendiente.']);
        }

        $montoPago = $totalPagado > 0 ? $saldoPendiente : (float) $pedido->adelanto;

        $metodoClave = $request->input('metodo_pago');

        if ($metodoClave) {
            $metodo = MetodoPago::activos()->where('clave', $metodoClave)->first();
            if (! $metodo) {
                return back()->withErrors(['metodo_pago' => 'El método de pago seleccionado no es válido.']);
            }
        } else {
            // Comportamiento original del adelanto: método por defecto.
            $metodoClave = 'yape';
        }

        $data = [
            'pedido_id'         => $pedido->id,
            'user_id'           => auth()->id(),
            'monto'             => $montoPago,
            'metodo_pago'       => $metodoClave,
            'numero_operacion'  => $request->input('numero_operacion'),
            'ocr_resultado'     => EstadoValidacionOcr::Pendiente->value,
        ];

        $comprobanteSubido = $request->hasFile('comprobante');

        if ($comprobanteSubido) {
            $path = $request->file('comprobante')->store('comprobantes', 'public');
            $data['comprobante_pago'] = $path;
        }

        $pago = $pedido->pagos()->create($data);

        if ($comprobanteSubido) {
            $pedido->update(['estado' => 'pendiente']);

            ProcesarComprobanteJob::dispatch($pago);

            $mensaje = $totalPagado > 0
                ? 'Comprobante del saldo enviado. El pago será verificado automáticamente.'
                : 'Comprobante enviado. El pago será verificado automáticamente.';
        } else {
            $mensaje = $totalPagado > 0
                ? 'Comprobante del saldo registrado. El pago quedará pendiente de verificación.'
                : 'Pago registrado. Quedará pendiente de verificación.';
        }

        return redirect()->route('pedidos.pago', $pedido)->with('success', $mensaje);
    }

    public function leerComprobante(Request $request)
    {
        if (! $request->hasFile('comprobante')) {
            return response()->json(['error' => 'No se recibió ningún archivo.'], 422);
        }

        $path = $request->file('comprobante')->store('comprobantes/tmp', 'public');

        try {
            $resultado = app(TesseractOcrService::class)->extraerTexto($path);

            if ($resultado === null) {
                return response()->json(['codigo' => null, 'monto' => null, 'fecha' => null]);
            }

            return response()->json([
                'codigo' => $resultado->codigoOperacion,
                'monto'  => $resultado->monto,
                'fecha'  => $resultado->fecha,
            ]);
        } finally {
            Storage::disk('public')->delete($path);
        }
    }

    public function status(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $pago = $pedido->pagos()->latest()->first();
        $boleta = $pedido->boletas()->first();

        $ocrLabel = null;
        if ($pago?->ocr_resultado) {
            $ocrLabel = EstadoValidacionOcr::tryFrom($pago->ocr_resultado)?->label();
        }

        return response()->json([
            'pedido_estado'       => $pedido->estado,
            'pedido_estado_label' => $pedido->estado_label,
            'pedido_estado_color' => $pedido->estado_color,
            'ocr_resultado'       => $pago?->ocr_resultado,
            'ocr_label'           => $ocrLabel,
            'ocr_datos'           => $pago?->ocr_datos,
            'numero_operacion'    => $pago?->numero_operacion,
            'pago_monto'          => $pago?->monto,
            'pago_creado'         => $pago?->created_at?->format('d/m/Y H:i'),
            'boleta'              => $boleta ? [
                'id'             => $boleta->id,
                'numero'         => $boleta->numero_boleta,
                'total'          => $boleta->total,
                'show_url'       => route('pedidos.boleta', $pedido),
                'pdf_url'        => route('pedidos.boleta.pdf', $pedido),
            ] : null,
        ]);
    }

    public function estado(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $pago = $pedido->pagos()->latest()->first();
        $boleta = $pedido->boletas()->first();

        $estado = match ($pedido->estado) {
            'pago_confirmado' => 'aprobado',
            'preparando',
            'enviado',
            'en_camino',
            'listo_recojo' => 'procesando',
            'completado'   => 'completado',
            'cancelado'    => 'cancelado',
            default        => 'pendiente',
        };

        $ocrRaw = $pago?->ocr_resultado;
        $estadoOcr = match ($ocrRaw) {
            'validado'      => 'completado',
            'procesando'    => 'procesando',
            default         => 'pendiente',
        };

        return response()->json([
            'estado'     => $estado,
            'estado_raw' => $pedido->estado,
            'estado_ocr' => $estadoOcr,
            'boleta'     => $boleta ? [
                'existe'   => true,
                'id'       => $boleta->id,
                'numero'   => $boleta->numero_boleta,
                'serie'    => $boleta->serie,
                'fecha'    => $boleta->fecha_emision?->format('d/m/Y'),
                'total'    => $boleta->total,
                'show_url' => route('pedidos.boleta', $pedido),
                'pdf_url'  => route('pedidos.boleta.pdf', $pedido),
            ] : ['existe' => false, 'id' => null],
        ]);
    }

    public function showBoleta(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $boleta = $pedido->boletas()->firstOrFail();
        $boleta->load(['detalles', 'pedido']);

        return view('pagos.boleta', compact('boleta', 'pedido'));
    }

    public function boletaPdf(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $boleta = $pedido->boletas()->firstOrFail();
        $boleta->load(['detalles', 'pedido']);

        $pdf = Pdf::loadView('boletas.pdf', compact('boleta'));

        return $pdf->download($boleta->numero_boleta . '.pdf');
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
