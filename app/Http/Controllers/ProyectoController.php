<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index()
    {
        $base = Proyecto::visibles()
            ->with(['imagenes' => fn ($q) => $q->orderBy('orden')])
            ->orderBy('orden')
            ->latest();

        $portafolio = (clone $base)->portafolio()->get();
        $inversion  = (clone $base)->inversion()->get();

        $categorias = Proyecto::visibles()
            ->distinct()
            ->pluck('categoria')
            ->filter()
            ->values();

        return view('proyectos.index', compact('portafolio', 'inversion', 'categorias'));
    }

    public function show(Proyecto $proyecto)
    {
        abort_unless($proyecto->visible, 404);

        $proyecto->load([
            'imagenes' => fn ($q) => $q->orderBy('orden'),
        ]);

        $relacionados = Proyecto::visibles()
            ->where('categoria', $proyecto->categoria)
            ->where('id', '!=', $proyecto->id)
            ->with(['imagenes' => fn ($q) => $q->orderBy('orden')])
            ->limit(3)
            ->get();

        return view('proyectos.show', compact('proyecto', 'relacionados'));
    }
}
