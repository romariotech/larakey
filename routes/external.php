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

    Route::middleware('permission:view_dashboard')
        ->get('/dashboard', function () {
            return response()->json([
                'message' => 'Permission granted [view_dashboard].',
            ]);
        });

    Route::middleware('permission:create_properties')
        ->get('/properties/create', function () {
            return response()->json([
                'message' => 'Permission granted [create_properties].',
            ]);
        });

    Route::middleware('permission:edit_properties')
        ->get('/properties/edit', function () {
            return response()->json([
                'message' => 'Permission granted [edit_properties].',
            ]);
        });

    Route::middleware('permission:view_reports')
        ->get('/reports', function () {
            return response()->json([
                'message' => 'Permission granted [view_reports].',
            ]);
        });
});
