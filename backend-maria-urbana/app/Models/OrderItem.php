<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'size', 'quantity', 'price'
    ];

    // Relación: Este item PERTENECE A un pedido
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación: Este item PERTENECE A un producto específico
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
