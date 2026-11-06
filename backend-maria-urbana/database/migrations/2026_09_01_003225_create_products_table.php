<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
       Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: Camisa Lino Beige
            $table->integer('price'); // Ej: 4500 (usar enteros para monedas es buena práctica)
            $table->string('category'); // Ej: Remeras y Camisas
            $table->string('gender'); // Ej: Hombre, Mujer, Unisex
            $table->integer('stock'); // Ej: 30
            $table->text('description')->nullable(); // Detalles de la tela, corte, etc.
            $table->json('sizes')->nullable(); // Para guardar un array como ["S", "M", "L"]
            $table->string('image_path')->nullable(); // La ruta donde se guardará la foto
            $table->timestamps(); // Crea automatically created_at y updated_at });

       });
     }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
