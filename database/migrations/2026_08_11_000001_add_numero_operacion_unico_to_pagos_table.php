<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('numero_operacion_unico', 100)
                ->nullable()
                ->storedAs("CASE WHEN estado_pago = 'aprobado' AND TRIM(numero_operacion) <> '' THEN numero_operacion ELSE NULL END")
                ->unique();
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropUnique(['numero_operacion_unico']);
            $table->dropColumn('numero_operacion_unico');
        });
    }
};
