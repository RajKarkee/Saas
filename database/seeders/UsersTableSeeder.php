<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a couple of known users and some random users
        User::factory()->create([
            'name' => 'Alice Customer',
            'email' => 'alice@example.com',
        ]);

        User::factory()->create([
            'name' => 'Bob Customer',
            'email' => 'bob@example.com',
        ]);

        // Create 8 more random users
        User::factory(8)->create();
    }
}
