<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\SolicitudTarjeta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TarjetaDebitoController extends Controller
{
    public function index()
    {
        $stats = [
            'proyectos_disponibles' => Proyecto::where('tipo', 'inversion')
                ->where('visible', true)
                ->where('inversion_estado', 'disponible')
                ->count(),
            'proyectos_totales' => Proyecto::count(),
            'usuarios' => User::count(),
        ];

        return view('sections.tarjeta-debito', compact('stats'));
    }

    public function solicitar()
    {
        $tiposDocumento = SolicitudTarjeta::TIPOS_DOCUMENTO;
        $estadosCiviles = SolicitudTarjeta::ESTADOS_CIVILES;
        $tiposSeguro    = SolicitudTarjeta::TIPOS_SEGURO;

        return view('sections.tarjeta-debito-solicitar', compact(
            'tiposDocumento',
            'estadosCiviles',
            'tiposSeguro'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Datos del cliente
            'nombres'              => ['required', 'string', 'max:120'],
            'apellidos'            => ['required', 'string', 'max:120'],
            'tipo_documento'       => ['required', 'string', 'in:' . implode(',', array_keys(SolicitudTarjeta::TIPOS_DOCUMENTO))],
            'numero_documento'     => ['required', 'string', 'max:20'],
            'fecha_nacimiento'     => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'celular'              => ['required', 'string', 'max:15'],
            'email'                => ['required', 'email', 'max:255'],
            'password'             => ['required', 'string', 'min:8', 'confirmed'],
            'estado_civil'         => ['required', 'string', 'in:' . implode(',', array_keys(SolicitudTarjeta::ESTADOS_CIVILES))],
            'nombre_padre'         => ['required', 'string', 'max:120'],
            'nombre_madre'         => ['required', 'string', 'max:120'],
            'emergencia_parentesco'=> ['required', 'string', 'max:120'],
            'emergencia_telefono'  => ['required', 'string', 'max:15'],

            // Dirección
            'departamento'         => ['required', 'string', 'max:120'],
            'provincia'            => ['required', 'string', 'max:120'],
            'distrito'             => ['required', 'string', 'max:120'],
            'direccion_calle'      => ['required', 'string', 'max:255'],
            'direccion_numero'     => ['required', 'string', 'max:120'],

            // Información adicional
            'ocupacion'            => ['required', 'string', 'max:120'],
            'empresa'              => ['required', 'string', 'max:255'],
            'ingresos_aproximados' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'antiguedad_laboral'   => ['required', 'string', 'max:120'],
            'direccion_laboral'    => ['required', 'string', 'max:255'],
            'desea_seguro'         => ['required', 'boolean'],
            'tipo_seguro'          => [
                'nullable',
                'string',
                'in:' . implode(',', array_keys(SolicitudTarjeta::TIPOS_SEGURO)),
                Rule::requiredIf(fn () => $request->boolean('desea_seguro')),
            ],

            // Confirmación
            'acepta_terminos'      => ['required', 'accepted'],
            'datos_correctos'      => ['required', 'accepted'],
        ]);

        $solicitud = SolicitudTarjeta::create([
            'nombres'              => $validated['nombres'],
            'apellidos'            => $validated['apellidos'],
            'nombre'               => trim($validated['nombres'] . ' ' . $validated['apellidos']),
            'tipo_documento'       => $validated['tipo_documento'],
            'numero_documento'     => $validated['numero_documento'],
            'fecha_nacimiento'     => $validated['fecha_nacimiento'],
            'celular'              => $validated['celular'],
            'email'                => $validated['email'],
            'password'             => Hash::make($validated['password']),
            'estado_civil'         => $validated['estado_civil'],
            'nombre_padre'         => $validated['nombre_padre'],
            'nombre_madre'         => $validated['nombre_madre'],
            'emergencia_parentesco'=> $validated['emergencia_parentesco'],
            'emergencia_telefono'  => $validated['emergencia_telefono'],

            'departamento'         => $validated['departamento'],
            'provincia'            => $validated['provincia'],
            'distrito'             => $validated['distrito'],
            'direccion_calle'      => $validated['direccion_calle'],
            'direccion_numero'     => $validated['direccion_numero'],

            'ocupacion'            => $validated['ocupacion'],
            'empresa'              => $validated['empresa'],
            'ingresos_aproximados' => $validated['ingresos_aproximados'],
            'antiguedad_laboral'   => $validated['antiguedad_laboral'],
            'direccion_laboral'    => $validated['direccion_laboral'],
            'desea_seguro'         => $request->boolean('desea_seguro'),
            'tipo_seguro'          => $validated['tipo_seguro'] ?? null,

            'acepta_terminos'      => true,
            'datos_correctos'      => true,

            'estado'               => 'pendiente',
        ]);

        return redirect()
            ->route('tarjeta.debito.gracias')
            ->with('solicitudId', $solicitud->id);
    }

    public function gracias()
    {
        return view('sections.tarjeta-debito-gracias');
    }
}
