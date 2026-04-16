<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaboradores', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('nombre');
            $table->string('email')->unique()->nullable();
            $table->string('telefono')->nullable();
            $table->string('codigo_ref')->unique();
            $table->decimal('comision_porcentaje', 5, 2)->default(10.00);
            $table->decimal('comision_total_acumulada', 10, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaboradores');
    }
};