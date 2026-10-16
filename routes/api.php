<?php

use App\Http\Controllers\CafeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API publik hanya baca
Route::get('cafe', [CafeController::class, 'index']);
Route::get('cafe/{cafe}', [CafeController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
