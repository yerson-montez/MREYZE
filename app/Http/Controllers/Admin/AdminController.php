<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\SolicitudColaborador;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProductos     = Producto::count();
        $productosActivos   = Producto::where('estado', 'disponible')->count();
        $productosAgotados  = Producto::where('estado', 'agotado')->count();
        $valorCatalogo      = Producto::sum('precio');

        $ultimosProductos = Producto::latest()->take(5)->get();
        $ultimasSolicitudes = SolicitudColaborador::latest()->take(5)->get();

        $catIot  = Producto::where('categoria', 'iot')->count();
        $catSoft = Producto::where('categoria', 'software')->count();
        $catKits = Producto::where('categoria', 'kits')->count();

        $pagosPendientes  = Pago::where('estado_pago', 'pendiente')->count();
        $pagosAprobados   = Pago::where('estado_pago', 'aprobado')->count();
        $pagosRechazados  = Pago::where('estado_pago', 'rechazado')->count();
        $totalPedidos     = Pedido::count();
        $pedidosPendientes = Pedido::where('estado', 'pendiente_pago')->count();

        return view('admin.dashboard', compact(
            'totalProductos',
            'productosActivos',
            'productosAgotados',
            'valorCatalogo',
            'ultimosProductos',
            'ultimasSolicitudes',
            'catIot',
            'catSoft',
            'catKits',
            'pagosPendientes',
            'pagosAprobados',
            'pagosRechazados',
            'totalPedidos',
            'pedidosPendientes'
        ));
    }
}