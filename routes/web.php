<?php

use App\Http\Controllers\CafeController;
use App\Http\Controllers\RatingsController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//start page
Route::get('/', function () {
    return view('halaman.home');
});

Route::get('/contact', function () {
    return view('halaman.contact');
});


Auth::routes();

//favorite
Route::post('/favorite/{cafe}', [FavoriteController::class,'store']);
Route::get('/favorite',[FavoriteController::class,'index']);

//cafe
Route::resource('/cafe', CafeController::class);

//ratings - memindahkan ke middleware auth
Route::get('/ratings', [RatingsController::class, 'index'])->name('ratings.index');
Route::get('/ratings/{rating}', [RatingsController::class, 'show'])->name('ratings.show');

//menu
Route::resource('/menu', MenuController::class);

//search bar
Route::get('/cafes/search', [cafeController::class,'search']);




Route::post('/session', [StripeController::class, 'session'])->name('session');
Route::get('/success', [StripeController::class, 'success'])->name('success');
Route::get('/cancel', [StripeController::class, 'cancel'])->name('cancel');

Route::get('/search/{cafe}', [MenuController::class,'search']);


Route::middleware(['auth'])->group(function() {

    Route::get('add-to-cart/{id}', [MenuController::class, 'addToCart'])->name('add_to_cart');
    Route::get('/clear-cart', [MenuController::class, 'clearCart'])->name('clear_cart');
    Route::get('cart', [MenuController::class, 'cart'])->name('cart');
    Route::patch('update-cart', [MenuController::class, 'updateCart'])->name('update_cart');
    Route::delete('remove-from-cart/{menu}', [MenuController::class, 'remove'])->name('remove_from_cart');
    
    //ratings middleware auth
    Route::post('/ratings', [RatingsController::class, 'store'])->name('ratings.store');
    Route::get('/ratings/create', [RatingsController::class, 'create'])->name('ratings.create');
    Route::get('/ratings/{rating}/edit', [RatingsController::class, 'edit'])->name('ratings.edit');
    Route::put('/ratings/{rating}', [RatingsController::class, 'update'])->name('ratings.update');
    Route::delete('/ratings/{rating}', [RatingsController::class, 'destroy'])->name('ratings.destroy');

});


Route::middleware(['isAdmin'])->group(function () {

Route::view('/admin', 'admin.dashboard');
Route::get('/table-cafe',[CafeController::class,'indexAdmin']);
Route::get('/table-cafe/{cafe}',[CafeController::class,'showAdmin']);
Route::get('/table-cafe/{cafe}/edit',[CafeController::class,'editAdmin']);
Route::put('/table-cafe/{cafe}',[CafeController::class,'updateAdmin']);

Route::get('/table-menu',[MenuController::class,'indexAdmin']);
Route::get('/table-menu/{menu}/edit',[MenuController::class,'editAdmin']);
Route::put('/table-menu/{menu}',[MenuController::class,'updateAdmin']);

Route::get('/table-user',[UserController::class,'index']);
Route::post('/table-user/{user}/accept',[UserController::class,'accept']);
Route::post('/table-user/{user}/reject',[UserController::class,'reject']);
Route::delete('/table-user/{user}',[UserController::class,'destroy']);

});




