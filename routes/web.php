<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/cafe', [CafeController::class, 'index'])->name('cafe.index');
Route::get('/cafe/{cafe}', [CafeController::class, 'show'])->name('cafe.show');

// Link lama tetap jalan
Route::redirect('/cafes/search', '/cafe');
Route::get('/menu/{cafe}', fn ($cafe) => redirect()->to(route('cafe.show', $cafe) . '#menu'));

Auth::routes(['verify' => false]);

// Pengguna login
Route::middleware('auth')->group(function () {
    Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite.index');
    Route::post('/favorite/{cafe}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{menu}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/checkout/success', [CartController::class, 'success'])->name('checkout.success');
});

// Dashboard: admin + penjual yang sudah di-approve
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:dashboard'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('cafes', Admin\CafeController::class);
    Route::resource('menus', Admin\MenuController::class)->except('show');
    Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');

    // Khusus admin
    Route::middleware('role:admin')->group(function () {
        Route::delete('/reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/approve', [Admin\UserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/reject', [Admin\UserController::class, 'reject'])->name('users.reject');
        Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
    });
});
