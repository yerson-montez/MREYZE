<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\MetodoPago;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    // Página principal de la tienda
    public function index(Request $request)
    {
        $cat = $request->query('cat', 'todos');

        $query = Producto::activos();
        if ($cat !== 'todos') {
            $query->categoria($cat);
        }

        $productos = $query->paginate(99999999999);
        $metodosPago = MetodoPago::activos()->get();

        return view('tienda.index', compact('productos'));
    }

    // Detalle de un producto
    public function show(Producto $producto)
    {
        abort_if(!$producto->activo, 404);
        $metodosPago = MetodoPago::activos()->get();
        return view('tienda.show', compact('producto', 'metodosPago'));
    }


    public function create($slug)
    {
        $producto = Producto::where('slug', $slug)->firstOrFail();

        return view('pedidos.create', compact('producto'));
    }
}