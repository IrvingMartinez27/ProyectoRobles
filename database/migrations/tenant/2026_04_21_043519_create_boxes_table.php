<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Sin foreign key — users está en BD central
            $table->date('fecha_apertura');
            $table->date('fecha_cierre');
            $table->decimal('monto_apertura');
            $table->decimal('monto_final');
            $table->boolean('estado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boxes');
    }
};