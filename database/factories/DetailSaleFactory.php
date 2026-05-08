<?php

namespace Database\Factories;

use App\Models\sale;
use App\Models\product;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailSaleFactory extends Factory
{
    public function definition(): array
    {
        $producto  = product::inRandomOrder()->first();
        $cantidad  = $this->faker->numberBetween(1, 5);
        $precio    = $producto?->precio ?? 100;

        return [
            'sale_id'         => sale::factory(),
            'product_id'      => $producto?->id,
            'cantidad'        => $cantidad,
            'precio_unitario' => $precio,
            'subtotal'        => $cantidad * $precio,
        ];
    }
}