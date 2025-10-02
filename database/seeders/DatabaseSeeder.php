<?php

namespace Database\Seeders;

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
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'), // You can change 'password' to a more secure password
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            ObjectiveSeeder::class,
            CategorySeeder::class,
            ActivitySeeder::class,
            AlimentRepasSeeder::class,
            PartnerSeeder::class
        ]);
    }
}
