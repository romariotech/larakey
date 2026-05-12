<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login/keycloak', [AuthController::class, 'redirect'])->name('login.keycloak');
Route::get('/auth/callback', [AuthController::class, 'callback']);

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')
    ->middleware('permission:view_dashboard')
    ->name('dashboard');
});

require __DIR__.'/settings.php';
