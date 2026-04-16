<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Colaborador;

class CapturarReferido
{
    // 30 días en minutos
    const DURACION = 60 * 24 * 30;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $codigo = $request->query('ref');

            // Solo guarda la cookie si el código existe y está activo
            $existe = Colaborador::where('codigo_ref', $codigo)
                                 ->where('activo', true)
                                 ->exists();

            if ($existe) {
                cookie()->queue(
                    cookie('ref_colaborador', $codigo, self::DURACION, '/', null, false, false)
                );
            }
        }

        return $next($request);
    }
}