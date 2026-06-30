<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pagos')) {
            Schema::create('pagos', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->id();
                $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('numero_operacion', 100)->nullable();
                $table->string('comprobante_pago', 255)->nullable();
                $table->string('metodo_pago', 50)->default('yape');
                $table->decimal('monto', 10, 2);
                $table->string('estado_pago', 50)->default('pendiente');
                $table->text('observaciones_admin')->nullable();
                $table->timestamp('fecha_pago')->nullable();
                $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
