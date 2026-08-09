<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Los productos existentes se importaron con PREC US $ guardado directamente
     * en "precio" (sin margen). Se asume que ese valor era el precio de
     * distribuidor: se copia a precio_distribuidor y se recalcula el precio de
     * venta aplicando el 20%.
     */
    public function up(): void
    {
        DB::table('productos')
            ->whereNull('precio_distribuidor')
            ->where('precio', '>', 0)
            ->orderBy('id')
            ->get(['id', 'precio'])
            ->each(function ($producto) {
                $distribuidor = (float) $producto->precio;
                DB::table('productos')
                    ->where('id', $producto->id)
                    ->update([
                        'precio_distribuidor' => $distribuidor,
                        'precio'              => round($distribuidor * 1.20, 2),
                    ]);
            });
    }

    public function down(): void
    {
        // Reversión: se conserva el precio actual como precio_distribuidor
        // (no se puede restaurar el valor original automáticamente).
        DB::table('productos')
            ->whereNotNull('precio_distribuidor')
            ->where('precio_distribuidor', '>', 0)
            ->get(['id', 'precio_distribuidor'])
            ->each(function ($producto) {
                DB::table('productos')
                    ->where('id', $producto->id)
                    ->update(['precio' => $producto->precio_distribuidor]);
            });
    }
};
