<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\RestaurantOrderSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users, restaurants, menu items and orders
        $this->call([
            UsersTableSeeder::class,
            RestaurantOrderSeeder::class,
        ]);
    }
}
