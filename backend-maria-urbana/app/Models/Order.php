<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // ¡Corregido! Ahora tienen guion bajo en vez de espacio
    protected $fillable = [
        'customer_name', 'customer_phone', 'customer_address',
        'total', 'status'
    ];

    // Relación: Un pedido TIENE MUCHOS items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
