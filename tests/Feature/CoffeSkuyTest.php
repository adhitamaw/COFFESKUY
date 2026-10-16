<?php

namespace Tests\Feature;

use App\Models\Cafe;
use App\Models\Menu;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CoffeSkuyTest extends TestCase
{
    use RefreshDatabase;

    private array $uploaded = [];

    protected function tearDown(): void
    {
        foreach ($this->uploaded as $file) {
            File::delete(public_path('image/' . $file));
        }
        parent::tearDown();
    }

    private function user(string $role = 'user', string $status = 'approved'): User
    {
        return User::factory()->create(['role' => $role, 'status' => $status]);
    }

    private function cafe(User $owner, array $attrs = []): Cafe
    {
        return Cafe::create($attrs + [
            'nama' => 'Kopi Senja',
            'alamat' => 'Jl. Braga 1',
            'gambar' => 'default.jpg',
            'content' => 'Tempat ngopi santai',
            'latitude' => '-6.9',
            'longitude' => '107.6',
            'user_id' => $owner->id,
        ]);
    }

    public function test_public_pages_render(): void
    {
        $cafe = $this->cafe($this->user('admin'));
        Menu::create(['nama' => 'Latte', 'harga' => 25000, 'gambar' => 'default.jpg', 'cafe_id' => $cafe->id, 'user_id' => $cafe->user_id]);

        $this->get('/')->assertOk()->assertSee('Kopi Senja');
        $this->get('/cafe')->assertOk()->assertSee('Kopi Senja');
        $this->get('/cafe?q=tidak-ada')->assertOk()->assertDontSee('Kopi Senja');
        $this->get("/cafe/{$cafe->id}")->assertOk()->assertSee('Latte');
        $this->get('/contact')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_logged_in_pages_render(): void
    {
        $user = $this->user();
        $cafe = $this->cafe($this->user('admin'));
        $user->favorites()->create(['cafe_id' => $cafe->id]);

        $this->actingAs($user)->get('/favorite')->assertOk()->assertSee('Kopi Senja');
        $this->actingAs($user)->get('/cart')->assertOk();
        $this->actingAs($user)->get("/cafe/{$cafe->id}")->assertOk();
    }

    public function test_register_buyer_is_active_and_seller_is_pending(): void
    {
        $this->post('/register', [
            'name' => 'Budi', 'email' => 'budi@test.com', 'role' => 'user',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect('/');
        $this->assertSame('approved', User::where('email', 'budi@test.com')->value('status'));

        auth()->logout();

        $this->post('/register', [
            'name' => 'Sari', 'email' => 'sari@test.com', 'role' => 'penjual',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ]);
        $this->assertSame('pending', User::where('email', 'sari@test.com')->value('status'));
    }

    public function test_cannot_register_as_admin(): void
    {
        $this->post('/register', [
            'name' => 'Hacker', 'email' => 'h@test.com', 'role' => 'admin',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'h@test.com']);
    }

    public function test_dashboard_access_rules(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->actingAs($this->user())->get('/admin')->assertRedirect('/');
        $this->actingAs($this->user('penjual', 'pending'))->get('/admin')->assertRedirect('/');
        $this->actingAs($this->user('admin', 'pending'))->get('/admin')->assertRedirect('/');

        $seller = $this->user('penjual');
        $this->actingAs($seller)->get('/admin')->assertOk();
        $this->actingAs($seller)->get('/admin/users')->assertRedirect('/');

        $admin = $this->user('admin');
        foreach (['/admin', '/admin/cafes', '/admin/cafes/create', '/admin/menus', '/admin/reviews', '/admin/users'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_seller_only_manages_own_cafe(): void
    {
        $seller = $this->user('penjual');
        $mine = $this->cafe($seller, ['nama' => 'Punyaku']);
        $other = $this->cafe($this->user('penjual'), ['nama' => 'Punya Orang']);

        $this->actingAs($seller)->get('/admin/cafes')->assertSee('Punyaku')->assertDontSee('Punya Orang');
        $this->actingAs($seller)->get("/admin/cafes/{$mine->id}")->assertOk();
        $this->actingAs($seller)->get("/admin/cafes/{$mine->id}/edit")->assertOk();
        $this->actingAs($seller)->get("/admin/cafes/{$other->id}/edit")->assertForbidden();
        $this->actingAs($seller)->delete("/admin/cafes/{$other->id}")->assertForbidden();
        $this->assertDatabaseHas('cafe', ['id' => $other->id]);
    }

    public function test_admin_creates_cafe_and_menu(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)->post('/admin/cafes', [
            'nama' => 'Kafe Baru', 'alamat' => 'Jl. Baru', 'content' => 'Deskripsi',
            'gambar' => UploadedFile::fake()->image('cafe.jpg'),
            'latitude' => '-6.91', 'longitude' => '107.61',
        ])->assertRedirect('/admin/cafes');

        $cafe = Cafe::where('nama', 'Kafe Baru')->firstOrFail();
        $this->uploaded[] = $cafe->gambar;

        $this->actingAs($admin)->get('/admin/menus/create')->assertOk();
        $this->actingAs($admin)->post('/admin/menus', [
            'nama' => 'Kopi Susu', 'harga' => 18000, 'cafe_id' => $cafe->id,
            'gambar' => UploadedFile::fake()->image('menu.jpg'),
        ])->assertRedirect('/admin/menus');

        $menu = Menu::where('nama', 'Kopi Susu')->firstOrFail();
        $this->uploaded[] = $menu->gambar;

        $this->actingAs($admin)->get("/admin/menus/{$menu->id}/edit")->assertOk();
        $this->actingAs($admin)->put("/admin/menus/{$menu->id}", ['nama' => 'Kopi Susu Aren', 'harga' => 20000, 'cafe_id' => $cafe->id])
            ->assertRedirect('/admin/menus');
        $this->assertDatabaseHas('menu', ['id' => $menu->id, 'nama' => 'Kopi Susu Aren', 'gambar' => $menu->gambar]);
    }

    public function test_review_flow(): void
    {
        $user = $this->user();
        $cafe = $this->cafe($this->user('admin'));

        $this->actingAs($user)->post('/reviews', ['cafe_id' => $cafe->id, 'rating' => 5, 'komentar' => 'Mantap sekali'])
            ->assertSessionHas('success');
        $this->actingAs($user)->post('/reviews', ['cafe_id' => $cafe->id, 'rating' => 3, 'komentar' => 'Lagi'])
            ->assertSessionHas('error');
        $this->assertSame(1, Review::count());

        $review = Review::first();
        $this->actingAs($user)->put("/reviews/{$review->id}", ['rating' => 4, 'komentar' => 'Diubah'])->assertSessionHas('success');
        $this->assertSame(4, (int) $review->fresh()->rating);

        $this->actingAs($this->user())->delete("/reviews/{$review->id}")->assertForbidden();
        $this->actingAs($user)->delete("/reviews/{$review->id}");
        $this->assertSame(0, Review::count());
    }

    public function test_favorite_and_cart(): void
    {
        $user = $this->user();
        $cafe = $this->cafe($this->user('admin'));
        $menu = Menu::create(['nama' => 'Latte', 'harga' => 25000, 'gambar' => 'default.jpg', 'cafe_id' => $cafe->id, 'user_id' => $cafe->user_id]);

        $this->actingAs($user)->postJson("/favorite/{$cafe->id}")->assertJson(['status' => true]);
        $this->actingAs($user)->postJson("/favorite/{$cafe->id}")->assertJson(['status' => false]);

        $this->actingAs($user)->postJson("/cart/{$menu->id}")->assertJson(['count' => 1]);
        $this->actingAs($user)->postJson("/cart/{$menu->id}")->assertJson(['count' => 2]);
        $this->actingAs($user)->get('/cart')->assertSee('Rp50.000');

        $this->actingAs($user)->patch("/cart/{$menu->id}", ['quantity' => 0]);
        $this->assertEmpty(session('cart'));
    }

    public function test_admin_approves_seller_and_rejected_user_cannot_login(): void
    {
        $admin = $this->user('admin');
        $seller = $this->user('penjual', 'pending');

        $this->actingAs($admin)->post("/admin/users/{$seller->id}/approve");
        $this->assertSame('approved', $seller->fresh()->status);
        $this->assertSame(1, $seller->cafes()->count());

        $this->actingAs($admin)->post("/admin/users/{$seller->id}/reject");
        auth()->logout();

        $this->post('/login', ['email' => $seller->email, 'password' => 'password'])->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_admin_deletes_user_with_data(): void
    {
        $admin = $this->user('admin');
        $seller = $this->user('penjual');
        $cafe = $this->cafe($seller);
        Review::create(['cafe_id' => $cafe->id, 'user_id' => $this->user()->id, 'rating' => 4, 'komentar' => 'Oke']);

        $this->actingAs($admin)->delete("/admin/users/{$seller->id}")->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $seller->id]);
        $this->assertDatabaseMissing('cafe', ['id' => $cafe->id]);

        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")->assertSessionHas('error');
    }

    public function test_api_is_read_only(): void
    {
        $this->getJson('/api/cafe')->assertOk();
        $this->postJson('/api/cafe', [])->assertStatus(405);
    }
}
