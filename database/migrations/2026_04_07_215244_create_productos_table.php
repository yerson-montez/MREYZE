<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_anterior', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->enum('categoria', ['iot', 'software', 'kits'])->default('iot');
            $table->enum('estado', ['nuevo', 'disponible', 'agotado'])->default('disponible');
            $table->string('icono_emoji')->default('📦');
            $table->string('icono_color')->default('rgba(0,212,255,0.12)');
            $table->string('slug')->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
