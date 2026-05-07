<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('cliente_email', 255)->after('cliente_telefono');
            $table->string('departamento', 100)->after('cliente_direccion');
            $table->string('provincia', 100)->after('departamento');
            $table->string('distrito', 100)->after('provincia');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['cliente_email', 'departamento', 'provincia', 'distrito']);
        });
    }
};