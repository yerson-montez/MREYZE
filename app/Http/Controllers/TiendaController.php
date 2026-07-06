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
        $cat    = $request->query('cat', 'todos');
        $search = $request->query('search');

        $query = Producto::activos();

        if ($cat !== 'todos') {
            $query->categoria($cat);
        }

        if ($search) {
            $term = '%' . $search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'LIKE', $term)
                  ->orWhere('descripcion', 'LIKE', $term)
                  ->orWhere('categoria', 'LIKE', $term);
            });
        }

        // AJAX: devolver JSON con HTML renderizado
        if ($request->ajax() || $request->wantsJson() || $request->query('ajax')) {
            $productos = $query->get();
            $html = view('tienda._productos', compact('productos'))->render();
            return response()->json([
                'html'  => $html,
                'count' => $productos->count(),
            ]);
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