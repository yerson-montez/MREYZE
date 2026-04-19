<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\SolicitudColaborador;

class AdminController extends Controller
{
    public function dashboard()
    {
        // KPIs
        $totalProductos     = Producto::count();
        $productosActivos   = Producto::where('estado', 'disponible')->count();
        $productosAgotados  = Producto::where('estado', 'agotado')->count();
        $valorCatalogo      = Producto::sum('precio');

        // Últimos productos
        $ultimosProductos = Producto::latest()->take(5)->get();

        // Últimas solicitudes
        $ultimasSolicitudes = SolicitudColaborador::latest()->take(5)->get();

        // Categorías
        $catIot  = Producto::where('categoria', 'iot')->count();
        $catSoft = Producto::where('categoria', 'software')->count();
        $catKits = Producto::where('categoria', 'kits')->count();

        return view('admin.dashboard', compact(
            'totalProductos',
            'productosActivos',
            'productosAgotados',
            'valorCatalogo',
            'ultimosProductos',
            'ultimasSolicitudes',
            'catIot',
            'catSoft',
            'catKits'
        ));
    }
}