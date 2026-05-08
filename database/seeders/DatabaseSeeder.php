<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\client;
use App\Models\inventory;
use App\Models\product;
use App\Models\User;
use App\Models\sale;
use App\Models\detail_sale;
use App\Models\ticket;
use App\Models\box;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario admin
        User::factory()->create([
            'name'     => 'Administrador',
            'email'    => 'robles@gmail.com',
            'password' => bcrypt('123456')
        ]);

        // ← QUITAR category::factory(10), solo crear las 3 fijas
        $categorias = ['Ropa', 'Calzado', 'Accesorios'];
        foreach ($categorias as $cat) {
            category::firstOrCreate(['name' => $cat]);
        }

        // Clientes de prueba
        client::factory(10)->create();

        // 12 productos con tallas
        product::factory(12)->create()->each(function ($producto) {
            $tallas = $producto->category->name === 'Calzado'
                ? ['38', '39', '40', '41', '42', '43']
                : ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

            collect($tallas)->shuffle()->take(rand(3, 5))
                ->each(function ($talla) use ($producto) {
                    inventory::create([
                        'product_id'     => $producto->id,
                        'talla'          => $talla,
                        'stock'          => rand(0, 25),
                        'precio_decimal' => $producto->precio,
                    ]);
                });
        });

       // Crear una caja abierta
        $caja = box::create([  // ← minúscula
            'user_id'        => 1,
            'fecha_apertura' => today(),
            'fecha_cierre'   => today(),
            'monto_apertura' => 5000,
            'monto_final'    => 0,
            'estado'         => true,
        ]);

        // Crear 10 ventas del día con sus detalles y ticket
        sale::factory(10)->create()->each(function ($venta) {  // ← minúscula
            $productos = product::inRandomOrder()->take(rand(1, 4))->get();  // ← minúscula
            $total = 0;

            foreach ($productos as $producto) {
                $cantidad = rand(1, 3);
                $subtotal = $cantidad * $producto->precio;
                $total   += $subtotal;

                detail_sale::create([
                    'sale_id'         => $venta->id,
                    'product_id'      => $producto->id,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $producto->precio,
                    'subtotal'        => $subtotal,
                ]);
            }

            $venta->update(['total' => $total]);

            ticket::create(['sale_id' => $venta->id]);  // ← minúscula
        });
    }
}