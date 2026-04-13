<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique(); // yape, plin, bcp, interbank, etc.
            $table->string('nombre');
            $table->string('emoji')->default('💳');
            $table->boolean('activo')->default(true);
            $table->json('datos')->nullable(); // número de cuenta, alias, etc.
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
};