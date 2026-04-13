<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductoRequest;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::orderBy('orden')->orderByDesc('created_at')->paginate(20);
        return view('admin.productos.index', compact('productos'));
    }

    public function create(): View
    {
        return view('admin.productos.create');
    }

    public function store(ProductoRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Subir imagen si viene
        if ($request->file('imagen') instanceof UploadedFile) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $data['activo'] = $request->boolean('activo', true);

        Producto::create($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto): View
    {
        return view('admin.productos.edit', compact('producto'));
    }

    public function update(ProductoRequest $request, Producto $producto): RedirectResponse
    {
        $data = $request->validated();

        // Reemplazar imagen si viene nueva
        if ($request->hasFile('imagen')) {
            // Borrar imagen anterior
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $data['activo'] = $request->boolean('activo');

        $producto->update($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado.');
    }

    // Cambiar orden con drag-and-drop (AJAX)
    public function reordenar(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'orden' => 'required|array',
            'orden.*' => 'integer|exists:productos,id',
        ]);

        foreach ($request->orden as $index => $id) {
            Producto::where('id', $id)->update(['orden' => $index]);
        }

        return response()->json(['ok' => true]);
    }
}