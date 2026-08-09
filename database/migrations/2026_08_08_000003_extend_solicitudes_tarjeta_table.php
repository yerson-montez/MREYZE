<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_tarjeta', function (Blueprint $table) {
            // ── Datos del cliente ──
            $table->string('nombres')->nullable()->after('nombre');
            $table->string('apellidos')->nullable()->after('nombres');
            $table->string('tipo_documento')->nullable()->after('apellidos');
            $table->string('numero_documento')->nullable()->after('tipo_documento');
            $table->date('fecha_nacimiento')->nullable()->after('numero_documento');
            $table->string('celular')->nullable()->after('email');
            $table->string('password')->nullable()->after('celular');
            $table->string('estado_civil')->nullable()->after('password');
            $table->string('nombre_padre')->nullable()->after('estado_civil');
            $table->string('nombre_madre')->nullable()->after('nombre_padre');
            $table->string('emergencia_parentesco')->nullable()->after('nombre_madre');
            $table->string('emergencia_telefono')->nullable()->after('emergencia_parentesco');

            // ── Dirección ──
            $table->string('departamento')->nullable()->after('emergencia_telefono');
            $table->string('provincia')->nullable()->after('departamento');
            $table->string('distrito')->nullable()->after('provincia');
            $table->string('direccion_calle')->nullable()->after('distrito');
            $table->string('direccion_numero')->nullable()->after('direccion_calle');

            // ── Información adicional ──
            $table->string('ocupacion')->nullable()->after('direccion_numero');
            $table->string('empresa')->nullable()->after('ocupacion');
            $table->decimal('ingresos_aproximados', 12, 2)->nullable()->after('empresa');
            $table->string('antiguedad_laboral')->nullable()->after('ingresos_aproximados');
            $table->string('direccion_laboral')->nullable()->after('antiguedad_laboral');
            $table->boolean('desea_seguro')->default(false)->after('direccion_laboral');
            $table->string('tipo_seguro')->nullable()->after('desea_seguro');

            // ── Confirmación ──
            $table->boolean('acepta_terminos')->default(false)->after('tipo_seguro');
            $table->boolean('datos_correctos')->default(false)->after('acepta_terminos');
        });

        // Las solicitudes antiguas (formulario básico) quedan como pendientes.
        DB::table('solicitudes_tarjeta')
            ->where('estado', 'nuevo')
            ->update(['estado' => 'pendiente']);
    }

    public function down(): void
    {
        Schema::table('solicitudes_tarjeta', function (Blueprint $table) {
            $table->dropColumn([
                'nombres',
                'apellidos',
                'tipo_documento',
                'numero_documento',
                'fecha_nacimiento',
                'celular',
                'password',
                'estado_civil',
                'nombre_padre',
                'nombre_madre',
                'emergencia_parentesco',
                'emergencia_telefono',
                'departamento',
                'provincia',
                'distrito',
                'direccion_calle',
                'direccion_numero',
                'ocupacion',
                'empresa',
                'ingresos_aproximados',
                'antiguedad_laboral',
                'direccion_laboral',
                'desea_seguro',
                'tipo_seguro',
                'acepta_terminos',
                'datos_correctos',
            ]);
        });
    }
};
