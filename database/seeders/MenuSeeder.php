<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Cafe;
use App\Models\User;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dapatkan cafe dan user pertama untuk digunakan pada menu
        $cafe = Cafe::first();
        $user = User::first();
        
        if (!$cafe || !$user) {
            $this->command->info('Tidak ditemukan Cafe atau User untuk diasosiasikan dengan Menu. Silakan buat terlebih dahulu.');
            return;
        }
        
        $menuItems = [
            [
                'nama' => 'Espresso',
                'harga' => 18000,
                'gambar' => '1704069294.jpg', // menggunakan gambar yang sudah ada di folder image
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Cappuccino',
                'harga' => 25000,
                'gambar' => '1704069340.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Latte',
                'harga' => 28000,
                'gambar' => '1704069808.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Americano',
                'harga' => 22000,
                'gambar' => '1704075776.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Mocha',
                'harga' => 30000,
                'gambar' => '1704068442.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Caramel Macchiato',
                'harga' => 32000,
                'gambar' => '1704078685.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Cold Brew',
                'harga' => 26000,
                'gambar' => '1704080148.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Affogato',
                'harga' => 35000,
                'gambar' => '1713843646.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Vanilla Latte',
                'harga' => 30000,
                'gambar' => '1704069772.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ],
            [
                'nama' => 'Frappe',
                'harga' => 32000,
                'gambar' => '1704069512.jpg',
                'cafe_id' => $cafe->id,
                'user_id' => $user->id
            ]
        ];

        foreach ($menuItems as $item) {
            Menu::create($item);
        }
        
        $this->command->info('10 menu kopi berhasil ditambahkan!');
    }
} 