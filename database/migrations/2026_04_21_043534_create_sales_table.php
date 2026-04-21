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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->foreignId('user_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->foreignId('box_id')
                    ->constrained()
                    ->onDelete('cascade');
            $table->decimal('total')->unsigned();
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};