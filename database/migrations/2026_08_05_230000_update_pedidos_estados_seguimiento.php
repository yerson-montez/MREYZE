<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('estado', 50)->default('pendiente')->change();
        });

        $this->remapear('pedidos');
        $this->remapear('pedido_estados');
    }

    public function down(): void
    {
        $this->remapearInverso('pedidos');
        $this->remapearInverso('pedido_estados');

        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('estado', 50)->default('pendiente_pago')->change();
        });
    }

    private function remapear(string $tabla): void
    {
        DB::table($tabla)
            ->whereIn('estado', ['pendiente_pago', 'pendiente_aprobacion'])
            ->update(['estado' => 'pendiente']);

        DB::table($tabla)
            ->where('estado', 'pagado_parcial')
            ->update(['estado' => 'pago_confirmado']);

        DB::table($tabla)
            ->whereIn('estado', ['en_proceso_envio', 'enviado'])
            ->update([
                'estado' => DB::raw("CASE estado WHEN 'en_proceso_envio' THEN 'enviado' WHEN 'enviado' THEN 'en_camino' END"),
            ]);

        DB::table($tabla)
            ->where('estado', 'entregado')
            ->update(['estado' => 'completado']);
    }

    private function remapearInverso(string $tabla): void
    {
        DB::table($tabla)->where('estado', 'pendiente')->update(['estado' => 'pendiente_pago']);
        DB::table($tabla)->where('estado', 'pago_confirmado')->update(['estado' => 'pagado_parcial']);
        DB::table($tabla)->where('estado', 'enviado')->update(['estado' => 'en_proceso_envio']);
        DB::table($tabla)->where('estado', 'en_camino')->update(['estado' => 'enviado']);
        DB::table($tabla)->whereIn('estado', ['listo_recojo', 'completado'])->update(['estado' => 'entregado']);
    }
};
