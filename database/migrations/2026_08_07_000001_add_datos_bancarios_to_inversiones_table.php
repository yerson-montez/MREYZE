<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inversiones', function (Blueprint $table) {
            $table->string('banco')->nullable()->after('dni');
            $table->string('cuenta_bancaria')->nullable()->after('banco');
            $table->string('cci_bancario')->nullable()->after('cuenta_bancaria');
            $table->string('titular_cuenta')->nullable()->after('cci_bancario');
        });
    }

    public function down(): void
    {
        Schema::table('inversiones', function (Blueprint $table) {
            $table->dropColumn(['banco', 'cuenta_bancaria', 'cci_bancario', 'titular_cuenta']);
        });
    }
};
