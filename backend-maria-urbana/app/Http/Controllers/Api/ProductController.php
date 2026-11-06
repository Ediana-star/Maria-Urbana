<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Función que ya tenías para listar
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // NUEVA FUNCIÓN: Para guardar un producto y su foto
    public function store(Request $request)
    {
        // 1. Validamos que los datos que mande Vue sean correctos
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'category' => 'required|string',
            'gender' => 'required|string',
            'stock' => 'required|integer',
            'sizes' => 'nullable|array', // Talles
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Foto real
        ]);

        $imagePath = null;

        // 2. Si viene una foto física, la guardamos en la carpeta
        if ($request->hasFile('image')) {
            // Esto guarda la foto en storage/app/public/productos
            // y devuelve el texto con la ruta (ej: "productos/foto123.jpg")
            $path = $request->file('image')->store('productos', 'public');

            // Le agregamos "/storage/" adelante para que Vue sepa dónde buscarla
            $imagePath = '/storage/' . $path;
        }

        // 3. Guardamos todo en la base de datos (nuestro "cuaderno" SQLite)
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'gender' => $request->gender,
            'stock' => $request->stock,
            'description' => $request->description,
            'sizes' => $request->sizes, // Se guarda como JSON automáticamente
            'image_path' => $imagePath // Guardamos el texto de la ruta
        ]);

        // 4. Le avisamos a Vue que todo salió bien y le mandamos el producto recién creado
        return response()->json([
            'message' => 'Producto creado con éxito',
            'producto' => $product
        ], 201);
    }
    // --- FUNCIÓN PARA EDITAR ---
    public function update(Request $request, int $id)
    {
        // 1. Buscamos el producto en la base de datos
        $product = Product::findOrFail($id);

        // 2. Revisamos que los datos estén bien
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        // 3. Lo actualizamos con los datos nuevos
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return response()->json(['message' => 'Producto actualizado']);
    }

    // --- FUNCIÓN PARA BORRAR ---
    public function destroy(int $id)
    {
        // 1. Buscamos el producto
        $product = Product::findOrFail($id);

        // 2. ¡Lo borramos!
        $product->delete();

        return response()->json(['message' => 'Producto eliminado correctamente']);
    }
}
