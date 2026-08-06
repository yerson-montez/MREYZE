<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProyectoInversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProyectoInversionController extends Controller
{
    public function index(Request $request)
    {
        $query = ProyectoInversion::query();

        if ($buscar = $request->query('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('slug', 'like', "%{$buscar}%");
            });
        }

        if ($estado = $request->query('estado')) {
            $query->where('estado', $estado);
        }

        if ($categoria = $request->query('categoria')) {
            $query->where('categoria', $categoria);
        }

        $proyectos = $query->orderBy('orden')->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'        => ProyectoInversion::count(),
            'disponible'   => ProyectoInversion::where('estado', 'disponible')->count(),
            'en_ejecucion' => ProyectoInversion::where('estado', 'en_ejecucion')->count(),
            'completado'   => ProyectoInversion::where('estado', 'completado')->count(),
            'visibles'     => ProyectoInversion::where('visible', true)->count(),
        ];

        return view('admin.proyectos-inversion.index', compact('proyectos', 'stats'));
    }

    public function create()
    {
        return view('admin.proyectos-inversion.form');
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        if ($request->hasFile('imagen_principal')) {
            $data['imagen_principal'] = $request->file('imagen_principal')->store('proyectos-inversion', 'public');
        }

        $data['visible'] = $request->boolean('visible');
        $data['orden']   = $request->input('orden', 0);

        ProyectoInversion::create($data);

        return redirect()
            ->route('admin.proyectos-inversion.index')
            ->with('exito', 'Proyecto de inversión creado correctamente.');
    }

    public function edit(ProyectoInversion $proyectoInversion)
    {
        return view('admin.proyectos-inversion.form', compact('proyectoInversion'));
    }

    public function update(Request $request, ProyectoInversion $proyectoInversion)
    {
        $data = $this->validar($request, $proyectoInversion->id);

        if ($request->hasFile('imagen_principal')) {
            if ($proyectoInversion->imagen_principal) {
                Storage::disk('public')->delete($proyectoInversion->imagen_principal);
            }
            $data['imagen_principal'] = $request->file('imagen_principal')->store('proyectos-inversion', 'public');
        }

        $data['visible'] = $request->boolean('visible');
        $data['orden']   = $request->input('orden', 0);

        $proyectoInversion->update($data);

        return redirect()
            ->route('admin.proyectos-inversion.index')
            ->with('exito', 'Proyecto de inversión actualizado correctamente.');
    }

    public function destroy(ProyectoInversion $proyectoInversion)
    {
        $proyectoInversion->delete();

        return redirect()
            ->route('admin.proyectos-inversion.index')
            ->with('exito', 'Proyecto de inversión eliminado.');
    }

    public function toggleVisibilidad(ProyectoInversion $proyectoInversion)
    {
        $proyectoInversion->update(['visible' => ! $proyectoInversion->visible]);

        return back()->with('exito', 'Visibilidad del proyecto actualizada.');
    }

    private function validar(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nombre'               => 'required|string|max:200',
            'slug'                 => 'nullable|string|max:200|unique:proyectos_inversion,slug,' . $ignoreId,
            'categoria'            => 'required|string|in:' . implode(',', array_keys(ProyectoInversion::CATEGORIAS)),
            'descripcion_corta'    => 'nullable|string|max:500',
            'descripcion_completa' => 'nullable|string',
            'imagen_principal'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'inversion_total'      => 'required|numeric|min:0',
            'inversion_disponible' => 'required|numeric|min:0|lte:inversion_total',
            'tiempo_meses'         => 'required|integer|min:1|max:600',
            'retorno_estimado'     => 'required|numeric|min:0|max:1000',
            'retorno_final'        => 'nullable|numeric|min:0|max:1000',
            'estado'               => 'required|in:disponible,en_ejecucion,completado',
            'fecha_inicio'         => 'nullable|date',
            'fecha_fin'            => 'nullable|date|after_or_equal:fecha_inicio',
            'visible'              => 'boolean',
            'orden'                => 'integer|min:0',
        ]);
    }
}
