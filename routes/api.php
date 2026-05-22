<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

// Rotas de usuários
Route::controller(UserController::class)
    ->name('users.')
    ->prefix('users')
    ->group(function () {

        Route::get('', 'list')->name('paginated');
        Route::post('', 'store')->name('store');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });
