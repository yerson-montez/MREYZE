<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('departamento', 100)->nullable()->change();
            $table->string('provincia', 100)->nullable()->change();
            $table->string('distrito', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('departamento', 100)->nullable(false)->change();
            $table->string('provincia', 100)->nullable(false)->change();
            $table->string('distrito', 100)->nullable(false)->change();
        });
    }
};
