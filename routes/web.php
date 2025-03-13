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
Route::get('/scan-product', [UserController::class, 'scanProduct']);
Route::get('/get-product-details', [UserController::class, 'getProductDetails']);
Route::get('/cart', function () {
    return view('cart');
});
Route::get('/verify-store', [UserController::class, 'verifyStore'])->name('user.verify');
Route::get('/get-merchant-info', [UserController::class, 'getMerchantInfo']);
Route::post('/upload-merchant-profile', [UserController::class, 'updateProfilepic']);


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
    Route::put('/products', [UserController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [UserController::class, 'destroy'])->name('product.destroy');
    Route::get('/merchant/home', [UserController::class, 'welcome'])->name('merchant.welcome');
});


require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';
