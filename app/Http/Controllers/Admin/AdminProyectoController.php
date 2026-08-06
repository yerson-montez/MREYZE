<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\ProyectoImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProyectoController extends Controller
{
    public function index(Request $request)
    {
        $query = Proyecto::query();

        if ($request->filled('buscar')) {
            $query->where('titulo', 'like', '%' . $request->buscar . '%');
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $proyectos = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'      => Proyecto::count(),
            'portafolio' => Proyecto::where('tipo', 'portafolio')->count(),
            'inversion'  => Proyecto::where('tipo', 'inversion')->count(),
            'visibles'   => Proyecto::where('visible', true)->count(),
        ];

        return view('admin.proyectos.index', compact('proyectos', 'stats'));
    }

    public function create()
    {
        return view('admin.proyectos.form');
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        $data = $this->limpiarInversionSiPortafolio($data, $request->input('tipo'));

        if ($request->hasFile('imagen_principal')) {
            $data['imagen_principal'] = $request->file('imagen_principal')
                ->store('proyectos', 'public');
        }

        $data['visible'] = $request->boolean('visible');

        DB::transaction(function () use ($data, $request) {
            $proyecto = Proyecto::create($data);

            $this->guardarImagenes($proyecto, $request);

            if ($request->hasFile('imagen_principal') && $proyecto->imagenes()->count() === 0) {
                $proyecto->imagenes()->create([
                    'ruta'         => $data['imagen_principal'],
                    'orden'        => 0,
                    'es_principal' => true,
                ]);
            }
        });

        return redirect()
            ->route('admin.proyectos.index')
            ->with('exito', 'Proyecto creado correctamente.');
    }

    public function edit(Proyecto $proyecto)
    {
        $proyecto->load('imagenes');
        return view('admin.proyectos.form', compact('proyecto'));
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $data = $this->validar($request, $proyecto->id);

        $data = $this->limpiarInversionSiPortafolio($data, $request->input('tipo'));

        if ($request->hasFile('imagen_principal')) {
            if ($proyecto->imagen_principal) {
                Storage::disk('public')->delete($proyecto->imagen_principal);
            }
            $data['imagen_principal'] = $request->file('imagen_principal')
                ->store('proyectos', 'public');
        }

        DB::transaction(function () use ($proyecto, $data, $request) {
            $proyecto->update($data);

            if ($request->has('eliminar_imagenes')) {
                foreach ($request->eliminar_imagenes as $imagenId) {
                    $imagen = ProyectoImagen::find($imagenId);
                    if ($imagen && $imagen->proyecto_id === $proyecto->id) {
                        Storage::disk('public')->delete($imagen->ruta);
                        $imagen->delete();
                    }
                }
            }

            $this->guardarImagenes($proyecto, $request);

            if ($request->has('orden_imagenes')) {
                foreach ($request->orden_imagenes as $orden => $imagenId) {
                    ProyectoImagen::where('id', $imagenId)
                        ->where('proyecto_id', $proyecto->id)
                        ->update(['orden' => $orden]);
                }
            }

            if ($proyecto->imagenes()->count() > 0 && !$proyecto->imagenes()->where('es_principal', true)->exists()) {
                $proyecto->imagenes()->orderBy('orden')->first()->update(['es_principal' => true]);
            }
        });

        return redirect()->route('admin.proyectos.index')
            ->with('exito', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Proyecto $proyecto)
    {
        DB::transaction(function () use ($proyecto) {
            foreach ($proyecto->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen->ruta);
            }
            $proyecto->imagenes()->delete();

            if ($proyecto->imagen_principal) {
                Storage::disk('public')->delete($proyecto->imagen_principal);
            }

            $proyecto->delete();
        });

        return redirect()->route('admin.proyectos.index')
            ->with('exito', 'Proyecto eliminado.');
    }

    public function toggleVisibilidad(Proyecto $proyecto)
    {
        $proyecto->update(['visible' => !$proyecto->visible]);
        return back()->with('exito', 'Visibilidad del proyecto actualizada.');
    }

    public function subirContrato(Request $request, Proyecto $proyecto)
    {
        $data = $request->validate([
            'contrato_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($proyecto->contrato_pdf) {
            Storage::disk('public')->delete($proyecto->contrato_pdf);
        }

        $proyecto->update([
            'contrato_pdf' => $request->file('contrato_pdf')->store('contratos', 'public'),
        ]);

        return back()->with('exito', 'Contrato actualizado correctamente.');
    }

    public function eliminarContrato(Proyecto $proyecto)
    {
        if ($proyecto->contrato_pdf) {
            Storage::disk('public')->delete($proyecto->contrato_pdf);
            $proyecto->update(['contrato_pdf' => null]);
        }

        return back()->with('exito', 'Contrato eliminado.');
    }

    public function setPrincipal(ProyectoImagen $imagen)
    {
        $imagen->proyecto->imagenes()->update(['es_principal' => false]);
        $imagen->update(['es_principal' => true]);

        return response()->json(['ok' => true]);
    }

    public function eliminarImagen(ProyectoImagen $imagen)
    {
        Storage::disk('public')->delete($imagen->ruta);

        if ($imagen->es_principal) {
            $imagen->delete();
            $primera = $imagen->proyecto->imagenes()->orderBy('orden')->first();
            if ($primera) {
                $primera->update(['es_principal' => true]);
            }
        } else {
            $imagen->delete();
        }

        return back()->with('exito', 'Imagen eliminada.');
    }

    private function guardarImagenes(Proyecto $proyecto, Request $request): void
    {
        if (!$request->hasFile('nuevas_imagenes')) {
            return;
        }

        $ordenMax = (int) $proyecto->imagenes()->max('orden');

        foreach ($request->file('nuevas_imagenes') as $index => $archivo) {
            $ruta = $archivo->store('proyectos', 'public');
            $proyecto->imagenes()->create([
                'ruta'         => $ruta,
                'orden'        => $ordenMax + $index + 1,
                'es_principal' => false,
            ]);
        }
    }

    private function limpiarInversionSiPortafolio(array $data, ?string $tipo): array
    {
        if ($tipo !== 'inversion') {
            foreach (['inversion_monto_total', 'inversion_monto_disponible', 'inversion_porcentaje_financiado', 'inversion_tiempo_estimado', 'inversion_roi', 'inversion_retorno_final', 'inversion_estado', 'inversion_etiqueta'] as $campo) {
                $data[$campo] = null;
            }
        }
        return $data;
    }

    private function validar(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'titulo'       => 'required|string|max:200',
            'slug'         => 'nullable|string|max:200|unique:proyectos,slug,' . $ignoreId,
            'descripcion_corta'     => 'nullable|string|max:500',
            'descripcion_completa'  => 'nullable|string',
            'categoria'    => 'required|in:automatizacion,iot,software,otro',
            'estado'       => 'required|in:en_curso,completado,proximamente,abierto,cerrado',
            'tipo'         => 'required|in:portafolio,inversion',
            'imagen_principal'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tecnologias'  => 'nullable|string|max:500',
            'cliente'      => 'nullable|string|max:200',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date',
            'visible'      => 'boolean',
            'orden'        => 'integer|min:0',
            'inversion_monto_total'          => 'nullable|numeric|min:0',
            'inversion_monto_disponible'     => 'nullable|numeric|min:0',
            'inversion_porcentaje_financiado' => 'nullable|integer|min:0|max:100',
            'inversion_tiempo_estimado'      => 'nullable|string|max:100',
            'inversion_roi'      => 'nullable|string|max:50',
            'inversion_retorno_final' => 'nullable|numeric|min:0|max:1000',
            'inversion_estado'   => 'nullable|in:disponible,en_ejecucion,completado',
            'inversion_etiqueta' => 'nullable|string|max:100',
            'whatsapp_numero'    => 'nullable|string|max:20',
            'whatsapp_mensaje'   => 'nullable|string|max:500',
            'nuevas_imagenes'       => 'nullable|array|max:10',
            'nuevas_imagenes.*'     => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }
}
