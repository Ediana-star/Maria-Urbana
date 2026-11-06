<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Camisa Blanca Hombre',
            'price' => 1290,
            'category' => 'Remeras y Camisas',
            'gender' => 'Hombre',
            'stock' => 20,
            'description' => 'Camisa de vestir blanca, corte entallado.',
            'sizes' => ['S', 'M', 'L', 'XL'],
            'image_path' => '/imagenes/camisa-hombre.jpeg'
        ]);

        Product::create([
            'name' => 'Remera Algodón Hombre',
            'price' => 590,
            'category' => 'Remeras y Camisas',
            'gender' => 'Hombre',
            'stock' => 45,
            'description' => 'Remera lisa de algodón, muy cómoda.',
            'sizes' => ['S', 'M', 'L'],
            'image_path' => '/imagenes/camiseta-hombre.jpg'
        ]);

        Product::create([
            'name' => 'Remera Básica Mujer',
            'price' => 590,
            'category' => 'Remeras y Camisas',
            'gender' => 'Mujer',
            'stock' => 35,
            'description' => 'Remera básica de mujer ideal para uso diario.',
            'sizes' => ['S', 'M', 'L'],
            'image_path' => '/imagenes/camiseta-mujer.jpg'
        ]);

        Product::create([
            'name' => 'Campera Invierno Hombre',
            'price' => 2890,
            'category' => 'Camperas',
            'gender' => 'Hombre',
            'stock' => 15,
            'description' => 'Campera acolchada de abrigo para hombre.',
            'sizes' => ['M', 'L', 'XL'],
            'image_path' => '/imagenes/campera-hombre (2).jpeg'
        ]);

        Product::create([
            'name' => 'Campera de Cuero Mujer',
            'price' => 2490,
            'category' => 'Camperas',
            'gender' => 'Mujer',
            'stock' => 10,
            'description' => 'Campera estilo biker de cuero sintético.',
            'sizes' => ['S', 'M'],
            'image_path' => '/imagenes/campera-mujer.jpeg'
        ]);

        Product::create([
            'name' => 'Enterito Elegante',
            'price' => 1890,
            'category' => 'Vestidos y Enteritos',
            'gender' => 'Mujer',
            'stock' => 12,
            'description' => 'Enterito largo sin mangas, ideal para la noche.',
            'sizes' => ['S', 'M', 'L'],
            'image_path' => '/imagenes/enterito.jpeg'
        ]);

        Product::create([
            'name' => 'Jean Clásico Mujer',
            'price' => 1490,
            'category' => 'Pantalones',
            'gender' => 'Mujer',
            'stock' => 25,
            'description' => 'Jean ajustado de tiro alto para mujer.',
            'sizes' => ['S', 'M', 'L'],
            'image_path' => '/imagenes/jean-mujer.jpeg'
        ]);

        Product::create([
            'name' => 'Jean Suelto Mujer',
            'price' => 1590,
            'category' => 'Pantalones',
            'gender' => 'Mujer',
            'stock' => 20,
            'description' => 'Jean holgado y relajado, estilo urbano.',
            'sizes' => ['M', 'L', 'XL'],
            'image_path' => '/imagenes/jean-mujer-suelto.jpeg'
        ]);

        Product::create([
            'name' => 'Pantalón Chino Hombre',
            'price' => 1390,
            'category' => 'Pantalones',
            'gender' => 'Hombre',
            'stock' => 30,
            'description' => 'Pantalón de gabardina de corte clásico.',
            'sizes' => ['S', 'M', 'L', 'XL'],
            'image_path' => '/imagenes/pantalon-hombre.jpg'
        ]);

        Product::create([
            'name' => 'Pantalón Vestir Mujer',
            'price' => 1690,
            'category' => 'Pantalones',
            'gender' => 'Mujer',
            'stock' => 18,
            'description' => 'Pantalón elegante de vestir para mujer.',
            'sizes' => ['S', 'M', 'L'],
            'image_path' => '/imagenes/pantalon-mujer.jpeg'
        ]);

        Product::create([
            'name' => 'Vestido Estampado',
            'price' => 1990,
            'category' => 'Vestidos y Enteritos',
            'gender' => 'Mujer',
            'stock' => 14,
            'description' => 'Vestido largo y fresco con estampado.',
            'sizes' => ['S', 'M'],
            'image_path' => '/imagenes/vestido.jpeg'
        ]);

        Product::create([
            'name' => 'Reloj Deportivo Negro',
            'price' => 2190,
            'category' => 'Accesorios',
            'gender' => 'Hombre',
            'stock' => 8,
            'description' => 'Reloj resistente con correa de silicona.',
            'sizes' => ['Único'],
            'image_path' => '/imagenes/reloj-negro.jpeg'
        ]);

        Product::create([
            'name' => 'Reloj Clásico Azul',
            'price' => 1990,
            'category' => 'Accesorios',
            'gender' => 'Hombre',
            'stock' => 5,
            'description' => 'Reloj analógico con detalles en azul.',
            'sizes' => ['Único'],
            'image_path' => '/imagenes/reloj-azul.jpeg'
        ]);

        Product::create([
            'name' => 'Musculosa Básica',
            'price' => 490,
            'category' => 'Remeras y Camisas',
            'gender' => 'Mujer',
            'stock' => 50,
            'description' => 'Musculosa de hilo ligera para el verano.',
            'sizes' => ['S', 'M', 'L'],
            'image_path' => '/imagenes/musculosa.jpeg'
        ]);

        Product::create([
            'name' => 'Soutien Encaje',
            'price' => 890,
            'category' => 'Ropa Interior',
            'gender' => 'Mujer',
            'stock' => 40,
            'description' => 'Soutien cómodo de encaje delicado.',
            'sizes' => ['S', 'M', 'L'],
            'image_path' => '/imagenes/sutien.jpeg'
        ]);
    }
}
