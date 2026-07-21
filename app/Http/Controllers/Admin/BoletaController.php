<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boleta;
use Barryvdh\DomPDF\Facade\Pdf;

class BoletaController extends Controller
{
    public function show(Boleta $boleta)
    {
        $boleta->load(['detalles', 'pedido']);

        return view('boletas.show', compact('boleta'));
    }

    public function pdf(Boleta $boleta)
    {
        $boleta->load(['detalles', 'pedido']);

        $pdf = Pdf::loadView('boletas.pdf', compact('boleta'));

        $filename = $boleta->numero_boleta . '.pdf';

        return $pdf->download($filename);
    }
}
