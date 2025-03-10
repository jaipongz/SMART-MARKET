<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\UserController;

Route::post('/api/login', [APIController::class, 'login'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/api/getItems', [APIController::class, 'getItems'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/api/updatePlayerData', [APIController::class, 'updatePlayerData'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/products{productId}', [UserController::class, 'getProducts'])->name('getProducts');
    Route::get('/merchantScan{merchantId}', [UserController::class, 'merchantScan'])->name('merchantScan');
    Route::post('/products', [UserController::class, 'storeProduct'])->name('product.store');

});

require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
