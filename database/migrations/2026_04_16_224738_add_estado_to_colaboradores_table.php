<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->enum('estado', ['pendiente', 'activo', 'rechazado', 'inactivo'])
                  ->default('pendiente')
                  ->after('activo');
            $table->text('mensaje_solicitud')->nullable()->after('estado');
            $table->timestamp('aprobado_at')->nullable()->after('mensaje_solicitud');
        });
    }

    public function down(): void
    {
        Schema::table('colaboradores', function (Blueprint $table) {
            $table->dropColumn(['estado', 'mensaje_solicitud', 'aprobado_at']);
        });
    }
};