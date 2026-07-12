<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitud_colaboradores', function (Blueprint $table) {
            $table->string('estado')->default('pendiente')->after('mensaje');
        });
    }

    public function down(): void
    {
        Schema::table('solicitud_colaboradores', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
