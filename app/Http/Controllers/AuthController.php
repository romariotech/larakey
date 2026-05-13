<?php

namespace App\Http\Controllers;

use App\Services\KeycloakAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private KeycloakAuthService $keycloakAuthService
    ) {
    }

    /**
     * Redireciona o usuário para a tela de login do Keycloak.
     */
    public function redirect()
    {
        return inertia()->location(
            $this->keycloakAuthService->getKeycloakRedirectUrl()
        );
    }

    /**
     * Recebe o retorno do Keycloak com os dados do usuário.
     */
    public function callback()
    {
        try {
            $this->keycloakAuthService->handleKeycloakCallback();
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['error' => 'Falha ao autenticar com Keycloak.']);
        }
    }

    /**
     * Realiza logout do usuário
     */
    public function logout(Request $request)
    {
        $idToken = session('id_token');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $keycloakLogoutUrl = $this->keycloakAuthService->getKeycloakLogoutUrl($idToken);

        return inertia()->location($keycloakLogoutUrl);
    }

    /**
     * Autentica com credenciais externas
     */
    public function loginExternal(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'O campo de nome de usuário é obrigatório.',
            'password.required' => 'O campo de senha é obrigatório.',
        ], [
            'username' => 'nome de usuário',
            'password' => 'senha',
        ]);

        try {
            $tokens = $this->keycloakAuthService->authenticateWithCredentials(
                $request->username,
                $request->password
            );

            return response()->json([
                'mensagem' => 'Login bem-sucedido!',
                'access_token' => $tokens['access_token'],
                'expires_in' => $tokens['expires_in'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'erro' => $e->getMessage(),
            ], 401);
        }
    }
}
