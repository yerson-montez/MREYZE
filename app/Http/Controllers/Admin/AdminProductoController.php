<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductoController extends Controller
{
    // ── Panel principal ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('cat')) {
            $query->where('categoria', $request->cat);
        }
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $productos = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'      => Producto::count(),
            'activos'    => Producto::where('activo', true)->count(),
            'agotados'   => Producto::where('estado', 'agotado')->count(),
            'ingresos'   => Producto::where('activo', true)->sum('precio'),
        ];

        return view('admin.productos.index', compact('productos', 'stats'));
    }

    // ── Crear ────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.productos.form');
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['slug'] = Str::slug($data['nombre']) . '-' . Str::random(4);

        Producto::create($data);

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto creado correctamente.');
    }

    // ── Editar ───────────────────────────────────────────────────────
    public function edit(Producto $producto)
    {
        return view('admin.productos.form', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $this->validar($request, $producto->id);
        $producto->update($data);

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto actualizado correctamente.');
    }

    // ── Eliminar ─────────────────────────────────────────────────────
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto eliminado.');
    }

    // ── Toggle activo ────────────────────────────────────────────────
    public function toggleActivo(Producto $producto)
    {
        $producto->update(['activo' => !$producto->activo]);
        return back()->with('exito', 'Estado del producto actualizado.');
    }

    // ── Actualizar stock rápido ──────────────────────────────────────
    public function actualizarStock(Request $request, Producto $producto)
    {
        $request->validate(['stock' => 'required|integer|min:0']);

        $nuevoStock = $request->stock;
        $estado = $nuevoStock === 0 ? 'agotado' : ($producto->estado === 'agotado' ? 'disponible' : $producto->estado);

        $producto->update(['stock' => $nuevoStock, 'estado' => $estado]);

        return back()->with('exito', 'Stock actualizado.');
    }

    // ── Validación compartida ────────────────────────────────────────
    private function validar(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nombre'          => 'required|string|max:120',
            'descripcion'     => 'required|string|max:500',
            'precio'          => 'required|numeric|min:0',
            'precio_anterior' => 'nullable|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'categoria'       => 'required|in:iot,software,kits',
            'estado'          => 'required|in:nuevo,disponible,agotado',
            'icono_emoji'     => 'nullable|string|max:10',
            'icono_color'     => 'nullable|string|max:80',
            'activo'          => 'boolean',
        ]);
    }
}
