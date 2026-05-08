<?php

namespace Database\Factories;

use App\Models\client;
use App\Models\box;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id'   => client::inRandomOrder()->first()?->id,
            'user_id'     => User::first()?->id,
            'box_id'      => box::first()?->id,
            'total'       => $this->faker->randomFloat(2, 100, 5000),
            'metodo_pago' => $this->faker->randomElement(['efectivo', 'tarjeta', 'transferencia']),
            'created_at'  => today(),
        ];
    }
}