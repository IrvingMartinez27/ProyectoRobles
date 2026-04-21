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
        Schema::create('movement_boxes', function (Blueprint $table) {
            $table->id();
             $table->foreignId('box_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->string('concepto');
            $table->decimal('monto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movement_boxes');
    }
};