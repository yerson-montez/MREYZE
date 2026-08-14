<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfil_corporativo', function (Blueprint $table) {
            $table->id();
            $table->string('ruta')->nullable();
            $table->string('nombre_original')->nullable();
            $table->string('tipo_mime')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfil_corporativo');
    }
};
