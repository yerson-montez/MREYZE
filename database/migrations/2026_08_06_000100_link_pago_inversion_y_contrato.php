<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->unsignedBigInteger('pedido_id')->nullable()->change();
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->unsignedBigInteger('inversion_id')->nullable()->after('id');
            $table->foreign('inversion_id')->references('id')->on('inversiones')->cascadeOnDelete();
        });

        Schema::table('inversiones', function (Blueprint $table) {
            $table->string('contrato_pdf')->nullable()->after('comprobante');
        });
    }

    public function down(): void
    {
        Schema::table('inversiones', function (Blueprint $table) {
            $table->dropColumn('contrato_pdf');
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['inversion_id']);
            $table->dropColumn('inversion_id');

            if (! DB::table('pagos')->whereNull('pedido_id')->exists()) {
                $table->unsignedBigInteger('pedido_id')->nullable(false)->change();
            }
            if (! DB::table('pagos')->whereNull('user_id')->exists()) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            }
        });
    }
};
