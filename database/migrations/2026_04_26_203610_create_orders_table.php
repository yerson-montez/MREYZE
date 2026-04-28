<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->integer('amount'); // en céntimos: 1000 = S/ 10.00
            $table->string('currency_code')->default('PEN');
            $table->string('email');
            $table->enum('status', ['pendiente', 'pagado', 'fallido'])->default('pendiente');
            $table->string('payment_id')->nullable(); // ID del cargo en Culqi
            $table->string('culqi_token')->nullable();
            $table->json('culqi_response')->nullable(); // respuesta completa de Culqi
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
