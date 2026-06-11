<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('puntos_ganados')->default(0)->after('tipo_venta');
            $table->integer('puntos_canjeados')->default(0)->after('puntos_ganados');
            $table->decimal('descuento_puntos', 10, 2)->default(0)->after('puntos_canjeados');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['puntos_ganados', 'puntos_canjeados', 'descuento_puntos']);
        });
    }
};
