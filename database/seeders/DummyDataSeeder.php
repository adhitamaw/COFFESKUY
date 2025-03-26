<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cafe;
use App\Models\Menu;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Buat atau dapatkan user admin dan user biasa
        $adminEmail = 'admin@coffeskuy.com';
        $userEmail = 'user@coffeskuy.com';
        
        // Cek apakah admin sudah ada
        $adminUser = User::where('email', $adminEmail)->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin CoffeSkuy',
                'email' => $adminEmail,
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 1
            ]);
            $this->command->info('User admin berhasil dibuat');
        } else {
            $this->command->info('User admin sudah ada, menggunakan user yang sudah ada');
        }
        
        // Cek apakah user biasa sudah ada
        $regularUser = User::where('email', $userEmail)->first();
        if (!$regularUser) {
            $regularUser = User::create([
                'name' => 'Pengguna Biasa',
                'email' => $userEmail,
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 1
            ]);
            $this->command->info('User biasa berhasil dibuat');
        } else {
            $this->command->info('User biasa sudah ada, menggunakan user yang sudah ada');
        }
        
        // 2. Hapus cafe dan menu yang ada jika ingin membuat ulang
        $this->command->info('Menghapus data cafe dan menu lama jika ada...');
        Menu::whereIn('cafe_id', Cafe::where('user_id', $adminUser->id)->pluck('id'))->delete();
        Cafe::where('user_id', $adminUser->id)->delete();
        
        // 3. Buat data cafe dummy
        $cafes = [
            [
                'nama' => 'CoffeSkuy Cafe',
                'alamat' => 'Jl. Kopi No. 123, Jakarta',
                'gambar' => '1713843699.jpg', // Gunakan gambar yang sudah ada di folder image
                'content' => 'Cafe kopi premium dengan suasana nyaman dan kopi berkualitas tinggi',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Brew Corner',
                'alamat' => 'Jl. Braga No. 45, Bandung',
                'gambar' => '1713785491.jpg',
                'content' => 'Tempat nongkrong asik dengan kopi spesialitas dari seluruh Indonesia',
                'latitude' => '-6.9122',
                'longitude' => '107.6097',
                'user_id' => $adminUser->id
            ],
        ];
        
        $createdCafes = [];
        foreach ($cafes as $cafeData) {
            $createdCafes[] = Cafe::create($cafeData);
        }
        
        $this->command->info('Data cafe berhasil ditambahkan');
        
        // 4. Buat data menu untuk cafe pertama
        $menuItems = [
            [
                'nama' => 'Espresso',
                'harga' => 18000,
                'gambar' => '1704069294.jpg', // menggunakan gambar yang sudah ada di folder image
                'cafe_id' => $createdCafes[0]->id,
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Cappuccino',
                'harga' => 25000,
                'gambar' => '1704069340.jpg',
                'cafe_id' => $createdCafes[0]->id,
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Latte',
                'harga' => 28000,
                'gambar' => '1704069808.jpg',
                'cafe_id' => $createdCafes[0]->id,
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Americano',
                'harga' => 22000,
                'gambar' => '1704075776.jpg',
                'cafe_id' => $createdCafes[0]->id,
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Mocha',
                'harga' => 30000,
                'gambar' => '1704068442.jpg',
                'cafe_id' => $createdCafes[0]->id,
                'user_id' => $adminUser->id
            ],
        ];
        
        // Menu untuk cafe kedua
        $menuItemsCafe2 = [
            [
                'nama' => 'Caramel Macchiato',
                'harga' => 32000,
                'gambar' => '1704078685.jpg',
                'cafe_id' => $createdCafes[1]->id,
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Cold Brew',
                'harga' => 26000,
                'gambar' => '1704080148.jpg',
                'cafe_id' => $createdCafes[1]->id,
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Affogato',
                'harga' => 35000,
                'gambar' => '1713843646.jpg',
                'cafe_id' => $createdCafes[1]->id,
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Vanilla Latte',
                'harga' => 30000,
                'gambar' => '1704069772.jpg',
                'cafe_id' => $createdCafes[1]->id,
                'user_id' => $adminUser->id
            ],
            [
                'nama' => 'Frappe',
                'harga' => 32000,
                'gambar' => '1704069512.jpg',
                'cafe_id' => $createdCafes[1]->id,
                'user_id' => $adminUser->id
            ]
        ];
        
        // Gabungkan kedua array menu
        $allMenuItems = array_merge($menuItems, $menuItemsCafe2);

        foreach ($allMenuItems as $item) {
            Menu::create($item);
        }
        
        $this->command->info('10 menu kopi berhasil ditambahkan untuk 2 cafe');
    }
} 