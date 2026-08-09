<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ProductoImagen;
use App\Imports\ProductosImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

        $productos = $query->latest()->paginate(100)->withQueryString();

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
        $this->aplicarPrecioVenta($data);

        if ($request->hasFile('icono_imagen')) {
            $data['icono_imagen'] = $request->file('icono_imagen')
                ->store('productos', 'public');
        }

        $data['activo'] = $request->boolean('activo');
        $data['slug']   = Str::slug($data['nombre']);

        DB::transaction(function () use ($data, $request) {
            $producto = Producto::create($data);

            $this->guardarImagenes($producto, $request);

            if ($request->hasFile('icono_imagen') && $producto->imagenes()->count() === 0) {
                $producto->imagenes()->create([
                    'ruta'        => $data['icono_imagen'],
                    'orden'       => 0,
                    'es_principal' => true,
                ]);
            }
        });

        return redirect()
            ->route('admin.productos.index')
            ->with('exito', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $producto->load('imagenes');
        return view('admin.productos.form', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $this->validar($request, $producto->id);
        $this->aplicarPrecioVenta($data);

        if ($request->hasFile('icono_imagen')) {
            if ($producto->icono_imagen) {
                Storage::disk('public')->delete($producto->icono_imagen);
            }
            $data['icono_imagen'] = $request->file('icono_imagen')
                ->store('productos', 'public');
        }

        DB::transaction(function () use ($producto, $data, $request) {
            $producto->update($data);

            // Eliminar imágenes marcadas para borrar
            if ($request->has('eliminar_imagenes')) {
                foreach ($request->eliminar_imagenes as $imagenId) {
                    $imagen = ProductoImagen::find($imagenId);
                    if ($imagen && $imagen->producto_id === $producto->id) {
                        Storage::disk('public')->delete($imagen->ruta);
                        $imagen->delete();
                    }
                }
            }

            // Subir nuevas imágenes
            $this->guardarImagenes($producto, $request);

            // Actualizar orden
            if ($request->has('orden_imagenes')) {
                foreach ($request->orden_imagenes as $orden => $imagenId) {
                    ProductoImagen::where('id', $imagenId)
                        ->where('producto_id', $producto->id)
                        ->update(['orden' => $orden]);
                }
            }

            // Cambiar imagen principal
            if ($request->filled('imagen_principal')) {
                $producto->imagenes()->update(['es_principal' => false]);
                ProductoImagen::where('id', $request->imagen_principal)
                    ->where('producto_id', $producto->id)
                    ->update(['es_principal' => true]);
            }

            // Si no hay ninguna imagen principal y existen imágenes, marcar la primera
            if ($producto->imagenes()->count() > 0 && !$producto->imagenes()->where('es_principal', true)->exists()) {
                $producto->imagenes()->orderBy('orden')->first()->update(['es_principal' => true]);
            }
        });

        return redirect()->route('admin.productos.index')
            ->with('exito', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        DB::transaction(function () use ($producto) {
            foreach ($producto->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen->ruta);
            }
            $producto->imagenes()->delete();

            if ($producto->icono_imagen) {
                Storage::disk('public')->delete($producto->icono_imagen);
            }

            $producto->delete();
        });

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

    public function setPrincipal(ProductoImagen $imagen)
    {
        $imagen->producto->imagenes()->update(['es_principal' => false]);
        $imagen->update(['es_principal' => true]);

        return response()->json(['ok' => true]);
    }

    public function eliminarImagen(ProductoImagen $imagen)
    {
        Storage::disk('public')->delete($imagen->ruta);

        if ($imagen->es_principal) {
            $imagen->delete();
            $primera = $imagen->producto->imagenes()->orderBy('orden')->first();
            if ($primera) {
                $primera->update(['es_principal' => true]);
            }
        } else {
            $imagen->delete();
        }

        return back()->with('exito', 'Imagen eliminada.');
    }

    private function guardarImagenes(Producto $producto, Request $request): void
    {
        if (!$request->hasFile('nuevas_imagenes')) {
            return;
        }

        $ordenMax = (int) $producto->imagenes()->max('orden');

        foreach ($request->file('nuevas_imagenes') as $index => $archivo) {
            $ruta = $archivo->store('productos', 'public');
            $producto->imagenes()->create([
                'ruta'        => $ruta,
                'orden'       => $ordenMax + $index + 1,
                'es_principal' => false,
            ]);
        }
    }

    /**
     * Si se registró el precio de distribuidor, calcula el precio de venta
     * aplicando el margen automático (20%).
     */
    private function aplicarPrecioVenta(array &$data): void
    {
        if (!empty($data['precio_distribuidor'])) {
            $data['precio'] = Producto::calcularPrecioVenta((float) $data['precio_distribuidor']);
        }
    }

            private function validar(Request $request, $id = null)
    {
        return $request->validate([
            'nombre'              => 'required|string|max:120',
            'descripcion'         => 'nullable|string|max:500',
            'precio'              => 'required|numeric|min:0',
            'precio_distribuidor' => 'nullable|numeric|min:0',
            'precio_anterior'     => 'nullable|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'categoria'       => 'required|in:iot,software,kits',
            'estado'          => 'required|in:nuevo,disponible,agotado',
            'icono_imagen'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'icono_color'     => 'nullable|string|max:80',
            'activo'          => 'boolean',
            'nuevas_imagenes' => 'nullable|array|max:10',
        ]);
    }


    /**
     * Importar productos desde Excel
     */
    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv'
            ]
        ]);

        try {

            Excel::import(
                new ProductosImport,
                $request->file('archivo')
            );

            return redirect()
                ->route('admin.productos.index')
                ->with('exito', 'Productos importados correctamente.');

        } catch (\Exception $e) {

            return back()
                ->with('error', 'Error al importar: '.$e->getMessage());
        }
    }
}