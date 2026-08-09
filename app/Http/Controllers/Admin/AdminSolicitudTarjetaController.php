<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudTarjeta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSolicitudTarjetaController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudTarjeta::latest()->get();

        return view('admin.solicitudes-tarjeta.index', compact('solicitudes'));
    }

    public function show(SolicitudTarjeta $solicitud)
    {
        return view('admin.solicitudes-tarjeta.show', compact('solicitud'));
    }

    public function cambiarEstado(Request $request, SolicitudTarjeta $solicitud)
    {
        $request->validate([
            'estado' => ['required', 'string', 'in:pendiente,en_revision,aprobada,rechazada'],
        ]);

        $solicitud->update(['estado' => $request->estado]);

        $mensaje = 'Solicitud actualizada a «' . SolicitudTarjeta::ESTADOS[$request->estado] . '».';

        if ($request->estado === 'aprobada') {
            $resultado = $this->aperturarCuenta($solicitud);
            if ($resultado !== null) {
                $mensaje .= ' ' . $resultado;
            }
        }

        return back()->with('success', $mensaje);
    }

    public function destroy(SolicitudTarjeta $solicitud)
    {
        $solicitud->delete();

        return redirect()
            ->route('admin.solicitudes-tarjeta.index')
            ->with('success', 'Solicitud eliminada correctamente.');
    }

    private function aperturarCuenta(SolicitudTarjeta $solicitud): ?string
    {
        $email = $solicitud->email;
        if (!$email) {
            return 'No se creó la cuenta: la solicitud no tiene correo.';
        }

        $usuario = User::where('email', $email)->first();

        if ($usuario) {
            return 'La cuenta de correo ya estaba registrada.';
        }

        User::create([
            'name'     => $solicitud->nombreCompleto(),
            'email'    => $email,
            'password' => $solicitud->password ?? \Illuminate\Support\Facades\Hash::make(Str::random(16)),
            'role'     => 'cliente',
        ]);

        return 'Se aperturó la cuenta del cliente.';
    }
}
