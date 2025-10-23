<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        User::updateOrCreate(
            ['email' => 'admin@smarthealth.tn'],
            [
                'name' => 'Admin SmartHealth',
                'email' => 'admin@smarthealth.tn',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Créer un utilisateur client pour tester
        User::updateOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Client Test',
                'email' => 'client@test.com',
                'password' => Hash::make('client123'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]
        );
    }
}
