<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
    Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Llaves foráneas: a qué pedido y a qué producto pertenece
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Detalles de esa prenda específica
            $table->string('size'); // Ej: "L" (fundamental porque el cliente elige el talle)
            $table->integer('quantity'); // Ej: 1
            $table->integer('price'); // IMPORTANTE: Guardamos el precio al momento de la compra por si luego el producto sube de precio.

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
