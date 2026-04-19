<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class SolicitudColaboradorController extends Controller
{
    public function show(): View
    {
        return view('colaboradores.solicitud');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre'             => 'required|string|max:255',
            'email'              => 'required|email|unique:colaboradores,email',
            'telefono'           => 'required|string|max:20',
            'mensaje_solicitud'  => 'nullable|string|max:1000',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'email.required'     => 'El email es obligatorio.',
            'email.email'        => 'Ingresa un email válido.',
            'email.unique'       => 'Ya existe una solicitud con ese email.',
            'telefono.required'  => 'El teléfono es obligatorio.',
        ]);

        Colaborador::create([
            'nombre'            => $validated['nombre'],
            'email'             => $validated['email'],
            'telefono'          => $validated['telefono'],
            'mensaje_solicitud' => $validated['mensaje_solicitud'] ?? null,
            'codigo_ref'        => Str::slug($validated['nombre']) . '-' . Str::lower(Str::random(6)),
            'comision_porcentaje' => 10.00, // default, admin puede cambiar después
            'estado'            => 'pendiente',
            'activo'            => false,
        ]);

        return redirect()
            ->route('colaboradores.solicitud.gracias')
            ->with('success', '¡Solicitud enviada! Te contactaremos pronto.');
    }

    public function gracias(): View
    {
        return view('colaboradores.gracias');
    }


    public function index()
    {
            $solicitudes = \App\Models\SolicitudColaborador::latest()->get();

        return view('admin.colaboradores.solicitudes', compact('solicitudes'));
    }
}