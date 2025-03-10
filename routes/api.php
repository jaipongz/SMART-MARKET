<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MembershipController;

Route::middleware(['web', 'auth:web'])->group(function () {
    Route::post('/register-member', [MembershipController::class, 'registerMember']);
});
