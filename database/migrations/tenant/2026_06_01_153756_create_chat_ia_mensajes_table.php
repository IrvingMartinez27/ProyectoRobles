<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_ia_mensajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('role'); // user, assistant
            $table->text('contenido');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_ia_mensajes');
    }
};