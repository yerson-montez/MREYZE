<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('ocr_resultado', 50)->nullable()->after('observaciones_admin');
            $table->json('ocr_datos')->nullable()->after('ocr_resultado');
            $table->decimal('ocr_confianza', 5, 2)->nullable()->after('ocr_datos');
            $table->timestamp('ocr_procesado_at')->nullable()->after('ocr_confianza');
            $table->unsignedTinyInteger('ocr_intentos')->default(0)->after('ocr_procesado_at');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropColumn([
                'ocr_resultado',
                'ocr_datos',
                'ocr_confianza',
                'ocr_procesado_at',
                'ocr_intentos',
            ]);
        });
    }
};
