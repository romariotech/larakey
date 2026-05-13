<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvalidApiTokenException extends Exception
{
    /**
     * Renderiza a exceção como uma resposta HTTP.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'erro' => 'Não Autorizado',
            'mensagem' => 'O seu token expirou ou é inválido. Por favor, faça login novamente.',
            // 'status_code' => 401
        ], 401);
    }
}
