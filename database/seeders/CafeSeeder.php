<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cafe;
use App\Models\User;

class CafeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dapatkan user dengan role admin
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->info('User admin tidak ditemukan. Silakan buat terlebih dahulu.');
            return;
        }
        
        // Buat data cafe dummy
        $cafes = [
            [
                'nama' => 'CoffeSkuy Cafe',
                'alamat' => 'Jl. Kopi No. 123, Jakarta',
                'gambar' => '1713843699.jpg', // Gunakan gambar yang sudah ada di folder image
                'content' => 'Cafe kopi premium dengan suasana nyaman dan kopi berkualitas tinggi',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'user_id' => $admin->id
            ],
            [
                'nama' => 'Brew Corner',
                'alamat' => 'Jl. Braga No. 45, Bandung',
                'gambar' => '1713785491.jpg',
                'content' => 'Tempat nongkrong asik dengan kopi spesialitas dari seluruh Indonesia',
                'latitude' => '-6.9122',
                'longitude' => '107.6097',
                'user_id' => $admin->id
            ],
        ];
        
        foreach ($cafes as $cafeData) {
            Cafe::create($cafeData);
        }
        
        $this->command->info('Data cafe berhasil ditambahkan!');
    }
} 