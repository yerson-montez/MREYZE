<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. pedidos.estado ──────────────────────────────────────────
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('estado', 50)->default('pendiente_pago')->change();
        });

        DB::table('pedidos')->where('estado', 'pendiente')->update(['estado' => 'pendiente_pago']);
        DB::table('pedidos')->where('estado', 'confirmado')->update(['estado' => 'pagado_parcial']);

        // ─── 2. pagos.estado_pago ──────────────────────────────────────
        if (Schema::hasTable('pagos') && Schema::hasColumn('pagos', 'estado_pago')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->string('estado_pago', 50)->default('pendiente')->change();
            });
        }

        // ─── 3. productos.categoria ─────────────────────────────────────
        if (Schema::hasTable('productos') && Schema::hasColumn('productos', 'categoria')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('categoria', 50)->default('iot')->change();
            });
        }

        // ─── 4. productos.estado ────────────────────────────────────────
        if (Schema::hasTable('productos') && Schema::hasColumn('productos', 'estado')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('estado', 50)->default('disponible')->change();
            });
        }

        // ─── 5. users.role ──────────────────────────────────────────────
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 50)->default('cliente')->change();
            });
        }

        // ─── 6. colaboradores.estado ────────────────────────────────────
        if (Schema::hasTable('colaboradores') && Schema::hasColumn('colaboradores', 'estado')) {
            Schema::table('colaboradores', function (Blueprint $table) {
                $table->string('estado', 50)->default('pendiente')->change();
            });
        }

        // ─── 7. orders.status ──────────────────────────────────────────
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status', 50)->default('pendiente')->change();
            });
        }
    }

    public function down(): void
    {
    }
};
