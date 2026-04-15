<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductoController extends Controller
{
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
            'total'    => Producto::count(),
            'activos'  => Producto::where('activo', true)->count(),
            'agotados' => Producto::where('estado', 'agotado')->count(),
            'ingresos' => Producto::where('activo', true)->sum('precio'),
        ];

        return view('admin.productos.index', compact('productos', 'stats'));
    }

    public function create()
    {
        return view('admin.productos.form');
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        if ($request->hasFile('icono_imagen')) {
            $data['icono_imagen'] = $request->file('icono_imagen')
                ->store('productos', 'public');
        }

        $data['activo'] = $request->boolean('activo');
        $data['slug']   = Str::slug($data['nombre']);

        Producto::create($data);

        return redirect()
            ->route('admin.productos.index')
            ->with('exito', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.form', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $this->validar($request, $producto->id);

        // ── Subida de imagen (también en update) ──────────────────────
        if ($request->hasFile('icono_imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->icono_imagen) {
                Storage::disk('public')->delete($producto->icono_imagen);
            }
            $data['icono_imagen'] = $request->file('icono_imagen')
                ->store('productos', 'public');
        }
        // Si NO se sube imagen nueva, conservamos la existente (no tocamos el campo)

        $producto->update($data);

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        // Eliminar imagen del storage al borrar producto
        if ($producto->icono_imagen) {
            Storage::disk('public')->delete($producto->icono_imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto eliminado.');
    }

    public function toggleActivo(Producto $producto)
    {
        $producto->update(['activo' => !$producto->activo]);
        return back()->with('exito', 'Estado del producto actualizado.');
    }

    public function actualizarStock(Request $request, Producto $producto)
    {
        $request->validate(['stock' => 'required|integer|min:0']);

        $nuevoStock = $request->stock;
        $estado = $nuevoStock === 0
            ? 'agotado'
            : ($producto->estado === 'agotado' ? 'disponible' : $producto->estado);

        $producto->update(['stock' => $nuevoStock, 'estado' => $estado]);

        return back()->with('exito', 'Stock actualizado.');
    }

    private function validar(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nombre'          => 'required|string|max:120',
            'descripcion'     => 'nullable|string|max:500',
            'precio'          => 'required|numeric|min:0',
            'precio_anterior' => 'nullable|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'categoria'       => 'required|in:iot,software,kits',
            'estado'          => 'required|in:nuevo,disponible,agotado',
            'icono_imagen'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'icono_color'     => 'nullable|string|max:80',
            'activo'          => 'boolean',
        ]);
    }
}