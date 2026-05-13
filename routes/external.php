<?php

// use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'loginExternal']);

Route::middleware('auth:external')->group(function () {

    Route::get('/teste-integracao', function () {

        $tokenPayload = Auth::guard('external')->token();

        return response()->json([
            'status' => 'sucesso',
            'mensagem' => 'Acesso autorizado pelo Keycloak Guard!',
            'dados_do_token' => json_decode($tokenPayload, true)
        ]);
    });
});
