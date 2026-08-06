<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boletas', function (Blueprint $table) {
            $table->unique('pedido_id');
        });
    }

    public function down(): void
    {
        Schema::table('boletas', function (Blueprint $table) {
            $table->dropUnique(['pedido_id']);
        });
    }
};
