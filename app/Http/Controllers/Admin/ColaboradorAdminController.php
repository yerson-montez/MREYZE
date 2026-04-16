<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ColaboradorAdminController extends Controller
{
    public function index(): View
    {
        $colaboradores = Colaborador::withCount('pedidos')
            ->withSum('pedidosEntregados', 'total')
            ->latest()
            ->paginate(20);

        return view('admin.colaboradores.index', compact('colaboradores'));
    }

    public function create(): View
    {
        return view('admin.colaboradores.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre'              => 'required|string|max:255',
            'email'               => 'nullable|email|unique:colaboradores,email',
            'telefono'            => 'nullable|string|max:20',
            'comision_porcentaje' => 'required|numeric|min:0|max:100',
        ]);

        // Genera código único: slug del nombre + 6 chars random
        $validated['codigo_ref'] = Str::slug($validated['nombre']) . '-' . Str::lower(Str::random(6));

        Colaborador::create($validated);

        return redirect()
            ->route('admin.colaboradores.index')
            ->with('success', 'Colaborador creado correctamente.');
    }

    public function show(Colaborador $colaborador): View
    {
        $pedidos = $colaborador->pedidos()
            ->with('producto')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_ventas'       => $colaborador->pedidosEntregados()->sum('total'),
            'total_pedidos'      => $colaborador->pedidos()->count(),
            'comision_pendiente' => $colaborador->pedidos()
                ->where('estado', 'entregado')
                ->where('comision_pagada', false)
                ->sum('comision_generada'),
        ];

        return view('admin.colaboradores.show', compact('colaborador', 'pedidos', 'stats'));
    }

    public function edit(Colaborador $colaborador): View
    {
        return view('admin.colaboradores.edit', compact('colaborador'));
    }

    public function update(Request $request, Colaborador $colaborador): RedirectResponse
    {
        $validated = $request->validate([
            'nombre'              => 'required|string|max:255',
            'email'               => 'nullable|email|unique:colaboradores,email,' . $colaborador->id,
            'telefono'            => 'nullable|string|max:20',
            'comision_porcentaje' => 'required|numeric|min:0|max:100',
            'activo'              => 'boolean',
        ]);

        $colaborador->update($validated);

        return redirect()
            ->route('admin.colaboradores.show', $colaborador)
            ->with('success', 'Colaborador actualizado correctamente.');
    }
}