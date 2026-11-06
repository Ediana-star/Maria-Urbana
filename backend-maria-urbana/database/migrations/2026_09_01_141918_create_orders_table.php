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
       Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Datos que pedís en "Finalizar Compra"
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');

            // Datos internos del pedido
            $table->integer('total'); // El total a pagar
            $table->string('status')->default('pendiente'); // Para tu etiqueta amarilla de "PENDIENTE"

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
