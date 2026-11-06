<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Los campos que permitimos guardar desde el formulario
    protected $fillable = [
        'name', 'price', 'category', 'gender',
        'stock', 'description', 'sizes', 'image_path'
    ];

    // Le decimos a Laravel que la columna "sizes" (que es JSON en la BD)
    // la transforme automáticamente en un Array de PHP/Vue cuando la pidamos.
    protected $casts = [
        'sizes' => 'array',
    ];

    // Relación: Un producto puede estar en muchos "items de pedido"
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
