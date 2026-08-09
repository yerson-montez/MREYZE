<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->string('ubicacion')->nullable()->after('cliente');
            $table->string('aviso_apertura')->nullable()->after('ubicacion');
            $table->date('fecha_apertura')->nullable()->after('aviso_apertura');
            $table->string('detalle_pdf')->nullable()->after('contrato_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropColumn(['ubicacion', 'aviso_apertura', 'fecha_apertura', 'detalle_pdf']);
        });
    }
};
