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
            $table->string('slug')->unique();
            $table->text('descripcion')->nullable();
            $table->string('categoria'); // iot, software, kits
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_anterior', 10, 2)->nullable();
            $table->string('estado')->default('disponible'); // disponible, agotado, nuevo
            $table->string('emoji')->default('📦');
            $table->string('imagen')->nullable(); // path de imagen subida
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};