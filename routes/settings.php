<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

Route::middleware(['auth', 'verified'])->group(function () {


    Route::inertia('settings/appearance', 'settings/Appearance')->name('appearance.edit');
});
