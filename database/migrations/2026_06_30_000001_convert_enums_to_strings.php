<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. pedidos.estado ──────────────────────────────────────────
        DB::statement("ALTER TABLE pedidos MODIFY COLUMN estado VARCHAR(50) NOT NULL DEFAULT 'pendiente_pago'");

        DB::statement("UPDATE pedidos SET estado = 'pendiente_pago' WHERE estado = 'pendiente'");
        DB::statement("UPDATE pedidos SET estado = 'pagado_parcial' WHERE estado = 'confirmado'");

        // ─── 2. pagos.estado_pago ──────────────────────────────────────
        if (Schema::hasTable('pagos') && Schema::hasColumn('pagos', 'estado_pago')) {
            DB::statement("ALTER TABLE pagos MODIFY COLUMN estado_pago VARCHAR(50) NOT NULL DEFAULT 'pendiente'");
        }

        // ─── 3. productos.categoria ─────────────────────────────────────
        if (Schema::hasColumn('productos', 'categoria')) {
            DB::statement("ALTER TABLE productos MODIFY COLUMN categoria VARCHAR(50) NOT NULL DEFAULT 'iot'");
        }

        // ─── 4. productos.estado ────────────────────────────────────────
        if (Schema::hasColumn('productos', 'estado')) {
            DB::statement("ALTER TABLE productos MODIFY COLUMN estado VARCHAR(50) NOT NULL DEFAULT 'disponible'");
        }

        // ─── 5. users.role ──────────────────────────────────────────────
        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'cliente'");
        }

        // ─── 6. colaboradores.estado ────────────────────────────────────
        if (Schema::hasColumn('colaboradores', 'estado')) {
            DB::statement("ALTER TABLE colaboradores MODIFY COLUMN estado VARCHAR(50) NOT NULL DEFAULT 'pendiente'");
        }

        // ─── 7. orders.status ──────────────────────────────────────────
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'status')) {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pendiente'");
        }
    }

    public function down(): void
    {
    }
};
