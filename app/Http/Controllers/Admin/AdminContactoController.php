<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MensajeContacto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminContactoController extends Controller
{
    public function index(): View
    {
        $mensajes = MensajeContacto::latest()->paginate(20);

        return view('admin.contacto.index', compact('mensajes'));
    }

    public function show(MensajeContacto $mensaje): View
    {
        if ($mensaje->estado === 'nuevo') {
            $mensaje->update(['estado' => 'leido']);
        }

        return view('admin.contacto.show', compact('mensaje'));
    }

    public function marcarLeido(MensajeContacto $mensaje): RedirectResponse
    {
        $mensaje->update(['estado' => 'leido']);

        return back()->with('success', 'Mensaje marcado como leído.');
    }

    public function destroy(MensajeContacto $mensaje): RedirectResponse
    {
        $mensaje->delete();

        return redirect()->route('admin.contacto.index')
            ->with('success', 'Mensaje eliminado correctamente.');
    }
}
