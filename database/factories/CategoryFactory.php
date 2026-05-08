<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        // Ya no se usa — las categorías las crea el seeder directamente
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }
}