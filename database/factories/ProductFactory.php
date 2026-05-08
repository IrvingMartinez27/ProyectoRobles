<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $productos = [
            'Tenis Nike Air Max', 'Playera Adidas', 'Shorts Puma',
            'Calcetines Under Armour', 'Sudadera Jordan', 'Gorra New Era',
            'Tenis Vans Old Skool', 'Chamarra North Face', 'Leggings Nike',
            'Mochila Adidas', 'Pants Puma', 'Camiseta FIFA',
        ];

        return [
            'name'        => $this->faker->unique()->randomElement($productos),
            'descripcion' => $this->faker->sentence(),
            'precio'      => $this->faker->randomFloat(2, 150, 2500),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'estado'      => true,
            // slug se genera automático en el boot() del modelo
        ];
    }
}