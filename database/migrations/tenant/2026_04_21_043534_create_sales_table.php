<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); // users está en la BD central, sin foreign key
            $table->foreignId('box_id')->constrained()->onDelete('cascade');
            $table->decimal('total')->unsigned();
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};