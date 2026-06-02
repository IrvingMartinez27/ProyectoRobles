<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos_operativos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('almacen_id')->nullable()->constrained('almacenes')->nullOnDelete();
            $table->string('concepto'); // "Renta", "Guías DHL", "Meta Ads"
            $table->string('categoria'); // fijo, variable, hormiga
            $table->decimal('monto', 10, 2);
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos_operativos');
    }
};