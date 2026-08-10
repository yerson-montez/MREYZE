<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use App\Models\Producto;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $productosHome = Producto::where('activo', true)
            ->where('estado', 'disponible')
            ->inRandomOrder()
            ->with('imagenes')
            ->take(4)
            ->get();

        $metodosPago = MetodoPago::where('activo', true)->get();

        return view('sections.inicio', compact(
            'productosHome',
            'metodosPago'
        ));
    }
}
