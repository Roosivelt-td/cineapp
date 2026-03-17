<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Usuario Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@cineapp.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Usuario Visor
        User::create([
            'name' => 'Juan Perez',
            'email' => 'juan@cineapp.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // 3. Usuario Visor 2
        User::create([
            'name' => 'Maria Gomez',
            'email' => 'maria@cineapp.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}
