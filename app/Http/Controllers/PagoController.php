<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function pagar(Request $request)
    {
        // aquí luego irá Mercado Pago
        return redirect()->route('checkout');
    }
}