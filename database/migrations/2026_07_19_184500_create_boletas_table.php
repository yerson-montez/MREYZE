<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boletas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('numero_boleta', 20)->unique();
            $table->string('serie', 10)->default('B001');
            $table->integer('correlativo');
            $table->dateTime('fecha_emision');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('estado', 20)->default('emitida');

            // Datos del cliente (snapshot)
            $table->string('cliente_nombre', 255)->nullable();
            $table->string('cliente_email', 255)->nullable();
            $table->string('cliente_documento', 20)->nullable();
            $table->string('cliente_tipo_documento', 5)->nullable();

            // Preparación SUNAT
            $table->string('tipo_comprobante', 10)->default('BOLETA');
            $table->string('ruc', 11)->nullable();
            $table->json('sunat_response')->nullable();
            $table->timestamp('xml_generado_at')->nullable();
            $table->timestamp('sunat_enviado_at')->nullable();
            $table->timestamp('sunat_baja_at')->nullable();
            $table->string('sunat_cdr_path', 255)->nullable();
            $table->text('motivo_baja')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boletas');
    }
};
