<?php

namespace App\Http\Controllers;

use App\Models\ProyectoInversion;

class ProyectoInversionController extends Controller
{
    public function index()
    {
        $disponibles = ProyectoInversion::visibles()
            ->disponibles()
            ->orderBy('orden')
            ->latest()
            ->get();

        $completados = ProyectoInversion::visibles()
            ->completados()
            ->orderBy('orden')
            ->latest()
            ->get();

        $retornoEjemplo = 30;
        foreach ($disponibles as $proyecto) {
            $retorno = (float) $proyecto->retorno_estimado;
            if ($retorno > 0) {
                $retornoEjemplo = max($retornoEjemplo, $retorno);
            }
        }

        return view('proyectos-inversion.index', compact('disponibles', 'completados', 'retornoEjemplo'));
    }
}
