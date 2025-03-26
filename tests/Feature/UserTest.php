<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreWithValidData()
    {
        $userData = [
            'id' => 'id',
            'nama' => 'Nama Cafe',
            'alamat' => 'Alamat Cafe',
            'gambar' => File::image('avatar.jpg'),
            'content' => 'Konten Cafe',
        ];
        $response = $this->post(route('cafe.store'),$userData);

        $response->assertStatus(422);
    }
    public function testStoreWithInalidData()
    {
        $userData = [
            // 'nama' => 'Nama Cafe',
            // 'alamat' => 'Alamat Cafe',
            // 'gambar' => File::image('avatar.jpg'),
            // 'content' => 'Konten Cafe',
        ];
        $response = $this->post(route('cafe.store'),$userData);

        $response->assertStatus(422);
    }
    

    
}
