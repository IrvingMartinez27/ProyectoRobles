<?php

namespace Database\Seeders;

use App\Models\category;
use App\Models\client;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'robles@gmail.com',
            'password' => bcrypt('123456')
        ]);

        category::factory(10)->create();
        client::factory(10)->create();
    }
}
