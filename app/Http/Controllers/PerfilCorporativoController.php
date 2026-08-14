<?php

namespace App\Http\Controllers;

use App\Models\PerfilCorporativo;
use Illuminate\View\View;

class PerfilCorporativoController extends Controller
{
    public function show(): View
    {
        $perfil = PerfilCorporativo::first();

        return view('sections.perfil-corporativo', compact('perfil'));
    }
}
