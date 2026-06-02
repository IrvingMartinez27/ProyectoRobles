<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('creditos_voz_mensuales')->default(150)->after('productos_creados_totales');
            $table->date('voz_reset_fecha')->nullable()->after('creditos_voz_mensuales');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['creditos_voz_mensuales', 'voz_reset_fecha']);
        });
    }
};