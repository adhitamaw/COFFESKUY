<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        // Jalankan DeveloperUserSeeder untuk membuat akun developer, admin, dan user biasa
        $this->call(DeveloperUserSeeder::class);
        
        // Panggil seeder data dummy untuk menu dan cafe
        $this->call([
            DummyDataSeeder::class,
        ]);
    }
}
