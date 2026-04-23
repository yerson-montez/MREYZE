<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\SolicitudColaborador;

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
            'email'              => 'required|email|unique:solicitud_colaboradores,email',
            'telefono'           => 'required|string|max:20',
            'mensaje_solicitud'  => 'nullable|string|max:1000',
        ], [
            'nombre.required'    => 'El nombre es obligatorio.',
            'email.required'     => 'El email es obligatorio.',
            'email.email'        => 'Ingresa un email válido.',
            'email.unique'       => 'Ya existe una solicitud con ese email.',
            'telefono.required'  => 'El teléfono es obligatorio.',
        ]);

        SolicitudColaborador::create([
            'nombre'            => $validated['nombre'],
            'email'             => $validated['email'],
            'telefono'          => $validated['telefono'],
            'mensaje_solicitud' => $validated['mensaje_solicitud'] ?? null,
            'estado'            => 'pendiente',
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

    public function aprobar(SolicitudColaborador $solicitud)
    {
        // Crear colaborador
        Colaborador::create([
            'nombre' => $solicitud->nombre,
            'email' => $solicitud->email,
            'telefono' => $solicitud->telefono,
            'codigo_ref' => Str::slug($solicitud->nombre) . '-' . Str::lower(Str::random(6)),
            'comision_porcentaje' => 10.00,
            'estado' => 'activo',
            'activo' => true,
        ]);

        // Actualizar estado de solicitud
        $solicitud->update([
            'estado' => 'aprobada'
        ]);

        return back()->with('success', 'Solicitud aprobada correctamente');
    }

    public function rechazar(SolicitudColaborador $solicitud)
    {
        $solicitud->update([
            'estado' => 'rechazada'
        ]);

        return back()->with('error', 'Solicitud rechazada correctamente');
    }

    public function miLink()
    {
        $colaborador = \App\Models\Colaborador::where('email', auth()->user()->email)->first();

        if (!$colaborador) {
            return redirect()->back()->with('error', 'No eres colaborador');
        }

        $pedidos = \App\Models\Pedido::where('colaborador_id', $colaborador->id)
            ->with('producto')
            ->latest()
            ->paginate(10);

        // ✅ AQUÍ CREAS LOS STATS
        $stats = [
            'total_pedidos' => $pedidos->total(),
            'total_ventas' => $pedidos->sum('total'),
        ];

        // ✅ AQUÍ LOS ENVÍAS A LA VISTA
        return view('colaboradores.mi-link', compact('colaborador', 'pedidos', 'stats'));
    }
}