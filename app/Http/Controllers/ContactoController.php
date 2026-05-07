<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function enviar(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email',
            'servicio' => 'required',
            'mensaje' => 'required',
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