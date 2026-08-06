<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->decimal('inversion_retorno_final', 5, 2)->nullable()->after('inversion_roi');
        });

        DB::table('proyectos')
            ->where('inversion_estado', 'abierto')
            ->update(['inversion_estado' => 'disponible']);

        DB::table('proyectos')
            ->where('inversion_estado', 'proximamente')
            ->update(['inversion_estado' => 'en_ejecucion']);

        DB::table('proyectos')
            ->where('inversion_estado', 'cerrado')
            ->update(['inversion_estado' => 'completado']);
    }

    public function down(): void
    {
        DB::table('proyectos')
            ->where('inversion_estado', 'disponible')
            ->update(['inversion_estado' => 'abierto']);

        DB::table('proyectos')
            ->where('inversion_estado', 'en_ejecucion')
            ->update(['inversion_estado' => 'proximamente']);

        DB::table('proyectos')
            ->where('inversion_estado', 'completado')
            ->update(['inversion_estado' => 'cerrado']);

        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn('inversion_retorno_final');
        });
    }
};
