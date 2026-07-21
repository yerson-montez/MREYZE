<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boleta_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boleta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('producto_id')->nullable()->constrained()->nullOnDelete();
            $table->string('descripcion', 500);
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boleta_detalles');
    }
};
