<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tipo');         // restock, sin_ventas, meta
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('icono')->default('notifications');
            $table->string('color')->default('blue'); // blue, red, amber, green
            $table->json('data')->nullable(); // data extra: product_id, etc
            $table->timestamp('leida_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'leida_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};