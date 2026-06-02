=<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('almacen_id')->nullable()->after('tenant_id');
            $table->integer('chat_consultas_mes')->default(0)->after('almacen_id');
            $table->date('chat_reset_fecha')->nullable()->after('chat_consultas_mes');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['almacen_id', 'chat_consultas_mes', 'chat_reset_fecha']);
        });
    }
};