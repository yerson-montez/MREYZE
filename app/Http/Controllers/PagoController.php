<?php

namespace App\Http\Controllers;

use App\Enums\EstadoValidacionOcr;
use App\Http\Requests\StoreComprobanteRequest;
use App\Jobs\ProcesarComprobanteJob;
use App\Models\Boleta;
use App\Models\MetodoPago;
use App\Models\Pago;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoController extends Controller
{
    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        $yape = MetodoPago::where('clave', 'yape')->first();
        $pago = $pedido->pagos()->latest()->first();
        $boleta = $pedido->boletas()->first();

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
            'pendiente'     => 'pendiente',
            default         => 'error',
        };

        return view('pagos.show', compact('pedido', 'yape', 'pago', 'boleta', 'currentEstado', 'currentOcr'));
    }

    public function storeComprobante(Pedido $pedido, StoreComprobanteRequest $request)
    {
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($pedido->estado, ['pendiente'])) {
            return back()->withErrors(['pedido' => 'Este pedido no está pendiente de pago.']);
        }

        $data = [
            'pedido_id'     => $pedido->id,
            'user_id'       => auth()->id(),
            'monto'         => $pedido->adelanto,
            'metodo_pago'   => 'yape',
            'numero_operacion' => $request->input('numero_operacion'),
            'ocr_resultado' => EstadoValidacionOcr::Pendiente->value,
        ];

        if ($request->hasFile('comprobante')) {
            $path = $request->file('comprobante')->store('comprobantes', 'public');
            $data['comprobante_pago'] = $path;
        }

        $pago = $pedido->pagos()->create($data);
        $pedido->update(['estado' => 'pendiente']);

        ProcesarComprobanteJob::dispatch($pago);

        return redirect()->route('pedidos.pago', $pedido)
            ->with('success', 'Comprobante enviado. El pago será verificado automáticamente.');
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
            'pendiente'     => 'pendiente',
            default         => 'error',
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
