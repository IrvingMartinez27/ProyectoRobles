<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class InventoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id'     => Product::factory(),
            'talla'          => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL', '38', '39', '40', '41', '42', '43']),
            'stock'          => $this->faker->numberBetween(0, 30),
            'precio_decimal' => $this->faker->randomFloat(2, 150, 2500),
        ];
    }
}