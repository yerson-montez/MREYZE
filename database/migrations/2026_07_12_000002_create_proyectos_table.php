<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descripcion_corta')->nullable();
            $table->longText('descripcion_completa')->nullable();
            $table->string('categoria');
            $table->string('estado');
            $table->string('tipo');
            $table->string('imagen_principal')->nullable();
            $table->string('tecnologias')->nullable();
            $table->string('cliente')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('visible')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->decimal('inversion_monto_total', 10, 2)->nullable();
            $table->decimal('inversion_monto_disponible', 10, 2)->nullable();
            $table->unsignedInteger('inversion_porcentaje_financiado')->default(0);
            $table->string('inversion_tiempo_estimado')->nullable();
            $table->string('inversion_roi')->nullable();
            $table->string('inversion_estado')->nullable();
            $table->string('inversion_etiqueta')->nullable();
            $table->string('whatsapp_numero')->nullable();
            $table->text('whatsapp_mensaje')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
