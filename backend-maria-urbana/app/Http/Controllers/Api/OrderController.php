<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

   public function store(Request $request)
    {
        // Ya no le exigimos a Vue que nos mande el 'total', lo vamos a calcular nosotros
        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'items' => 'required|array',
        ]);

        // ACÁ ESTÁ LA MAGIA: Laravel calcula todo de cero
        $totalReal = 0;
        $itemsProcesados = [];

        foreach ($request->items as $item) {
            // Buscamos el producto verdadero y su precio original en la base de datos
            $productoFidedigno = \App\Models\Product::find($item['id']);

            if ($productoFidedigno) {
                $precioVerdadero = $productoFidedigno->price;

                // Sumamos usando el precio real, no el que mandó el cliente
                $totalReal += ($precioVerdadero * $item['cantidad']);

                // Guardamos los datos limpios para anotar en el pedido
                $itemsProcesados[] = [
                    'product_id' => $productoFidedigno->id,
                    'size' => $item['talle'],
                    'quantity' => $item['cantidad'],
                    'price' => $precioVerdadero,
                ];
            }
        }

        // Creamos el pedido usando el total que calculamos nosotros
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total' => $totalReal,
            'status' => 'Pendiente',
        ]);

        // Anotamos las prendas una por una
        foreach ($itemsProcesados as $itemReal) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $itemReal['product_id'],
                'size' => $itemReal['size'],
                'quantity' => $itemReal['quantity'],
                'price' => $itemReal['price'],
            ]);
        }

        return response()->json(['message' => '¡Pedido creado con éxito!', 'order_id' => $order->id], 201);
    }

   public function update(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $estadoAnterior = $order->status; // Guardamos cómo estaba antes

    $order->update([
        'status' => $request->status
    ]);

    // Si recién ahora se entrega, descontamos el stock
    if ($request->status === 'Entregado' && $estadoAnterior !== 'Entregado') {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product && $product->stock >= $item->quantity) {
                $product->stock = $product->stock - $item->quantity;
                $product->save();
            }
        }
    }
    // Si estaba entregado y lo cancelan/pasan a pendiente, devolvemos el stock
    elseif ($estadoAnterior === 'Entregado' && $request->status !== 'Entregado') {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->stock = $product->stock + $item->quantity;
                $product->save();
            }
        }
    }

    return response()->json(['message' => 'Estado actualizado']);
}

    // --- NUEVA FUNCIÓN: Para borrar definitivamente ---
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Primero borramos las prendas asociadas a este pedido para que no queden sueltas
        $order->items()->delete();
        // Ahora sí borramos el pedido de la base de datos
        $order->delete();

        return response()->json(['message' => 'Pedido eliminado de la base de datos']);
    }
}
