<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inversiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('proyecto_id');
            $table->string('nombre');
            $table->string('email');
            $table->string('telefono')->nullable();
            $table->string('dni')->nullable();
            $table->decimal('monto', 10, 2);
            $table->unsignedBigInteger('metodo_pago_id')->nullable();
            $table->string('numero_operacion')->nullable();
            $table->string('comprobante')->nullable();
            $table->string('estado')->default('pendiente');
            $table->text('notas_admin')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->cascadeOnDelete();
            $table->foreign('metodo_pago_id')->references('id')->on('metodos_pago')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inversiones');
    }
};
