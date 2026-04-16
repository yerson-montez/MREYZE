<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->cascadeOnDelete();
            $table->foreignId('colaborador_id')
                  ->nullable()
                  ->constrained('colaboradores')
                  ->nullOnDelete();
            $table->string('cliente_nombre');
            $table->string('cliente_telefono');
            $table->string('cliente_direccion');
            $table->decimal('total', 10, 2);
            $table->decimal('adelanto', 10, 2);
            $table->decimal('saldo', 10, 2);
            $table->enum('estado', ['pendiente', 'confirmado', 'entregado', 'cancelado'])
                  ->default('pendiente');
            $table->decimal('comision_generada', 10, 2)->default(0);
            $table->boolean('comision_pagada')->default(false);
            $table->timestamp('entregado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};