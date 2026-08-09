<?php

namespace App\Http\Controllers;

use App\Models\MensajeContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function enviar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'email' => 'required|email',
            'telefono' => 'nullable|string|max:30',
            'servicio' => 'required|string',
            'mensaje' => 'required|string',
        ]);

        MensajeContacto::create([
            'nombre' => $validated['nombre'],
            'empresa' => $validated['empresa'] ?? null,
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'servicio' => $validated['servicio'],
            'mensaje' => $validated['mensaje'],
            'estado' => 'nuevo',
        ]);

        try {

            Mail::raw(
                "Nombre: {$request->nombre}\nEmail: {$request->email}\nServicio: {$request->servicio}\nMensaje: {$request->mensaje}",
                function ($message) {
                    $message->to('TU_CORREO@gmail.com')
                            ->subject('Nuevo mensaje desde la web');
                }
            );

            return response()->json([
                'ok' => true,
                'message' => 'Mensaje enviado correctamente'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}