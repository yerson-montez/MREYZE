<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodoPago;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MetodoPagoController extends Controller
{
    public function index(): View
    {
        $metodos = MetodoPago::orderBy('orden')->get();
        return view('admin.metodos-pago.index', compact('metodos'));
    }

    public function update(Request $request, MetodoPago $metodoPago): RedirectResponse
    {
        $data = $request->validate([
            'nombre'          => 'required|string|max:80',
            'emoji'           => 'nullable|string|max:10',
            'activo'          => 'boolean',
            'datos'           => 'nullable|array',
            'datos.numero'    => 'nullable|string|max:60',
            'datos.alias'     => 'nullable|string|max:60',
            'datos.titular'   => 'nullable|string|max:80',
            'datos.cci'       => 'nullable|string|max:40',
            'datos.qr'        => 'nullable|string|max:255',
        ]);

        $data['activo'] = $request->boolean('activo');

        $metodoPago->update($data);

        return redirect()->route('admin.metodos-pago.index')
            ->with('success', "Método \"{$metodoPago->nombre}\" actualizado.");
    }

    public function toggle(MetodoPago $metodoPago): JsonResponse
    {
        $metodoPago->update(['activo' => !$metodoPago->activo]);
        return response()->json(['activo' => $metodoPago->activo]);
    }
}