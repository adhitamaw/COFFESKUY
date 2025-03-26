<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DeveloperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        // Buat akun admin yang sudah approved
        User::create([
            'name' => 'Admin CoffeSkuy',
            'email' => 'admin@coffeskuy.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'approved',
        ]);

        // Buat akun user biasa
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@coffeskuy.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'approved', // User biasa tidak perlu approval
        ]);
    }
}
