<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos_inversion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('categoria');
            $table->string('descripcion_corta')->nullable();
            $table->text('descripcion_completa')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->decimal('inversion_total', 12, 2)->default(0);
            $table->decimal('inversion_disponible', 12, 2)->default(0);
            $table->unsignedInteger('tiempo_meses')->default(0);
            $table->decimal('retorno_estimado', 5, 2)->default(30.00);
            $table->decimal('retorno_final', 5, 2)->nullable();
            $table->string('estado')->default('disponible');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('visible')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['estado', 'visible']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos_inversion');
    }
};
