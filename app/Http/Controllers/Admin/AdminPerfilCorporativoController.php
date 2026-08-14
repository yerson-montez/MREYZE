<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerfilCorporativo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminPerfilCorporativoController extends Controller
{
    public function index(): View
    {
        $perfil = PerfilCorporativo::first();

        return view('admin.perfil-corporativo.index', compact('perfil'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'archivo' => ['required', 'file', 'mimes:pdf,jpeg,jpg,png', 'max:10240'],
        ]);

        $archivo = $data['archivo'];

        $perfil = PerfilCorporativo::first();

        if ($perfil && $perfil->ruta) {
            Storage::disk('public')->delete($perfil->ruta);
        }

        $ruta = $archivo->store('perfil-corporativo', 'public');

        if ($perfil) {
            $perfil->update([
                'ruta'            => $ruta,
                'nombre_original' => $archivo->getClientOriginalName(),
                'tipo_mime'       => $archivo->getMimeType(),
            ]);
        } else {
            PerfilCorporativo::create([
                'ruta'            => $ruta,
                'nombre_original' => $archivo->getClientOriginalName(),
                'tipo_mime'       => $archivo->getMimeType(),
            ]);
        }

        return redirect()
            ->route('admin.perfil-corporativo.index')
            ->with('success', 'Perfil corporativo actualizado correctamente.');
    }
}
