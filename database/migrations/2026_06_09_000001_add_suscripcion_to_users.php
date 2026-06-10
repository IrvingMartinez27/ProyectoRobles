<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mp_preapproval_id')->nullable()->after('plan');
            $table->string('mp_payer_email')->nullable()->after('mp_preapproval_id');
            $table->timestamp('plan_vence_en')->nullable()->after('mp_payer_email');
            $table->string('tipo_suscripcion')->default('ninguna')->after('plan_vence_en'); // ninguna, mensual, anual
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mp_preapproval_id', 'mp_payer_email', 'plan_vence_en', 'tipo_suscripcion']);
        });
    }
};