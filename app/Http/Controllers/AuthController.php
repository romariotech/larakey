<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
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
     * Redirect to Keycloak for authentication
     */
    public function redirect()
    {
        return inertia()->location(
            $this->keycloakAuthService->getKeycloakRedirectUrl()
        );
    }

    /**
     * Handle the callback from Keycloak with user data.
     */
    public function callback()
    {
        try {
            $this->keycloakAuthService->handleKeycloakCallback();
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {

            return redirect('/')->withErrors(['error' => 'Failed to authenticate with Keycloak: ' . $e->getMessage()]);
        }
    }

    /**
     * Logout the user from the application and Keycloak
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
     * Login using external credentials (for API clients)
     */
    public function loginExternal(LoginRequest $request)
    {
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
                'erro' => 'Failed to authenticate with Keycloak: ' . $e->getMessage(),
            ], 401);
        }
    }
}
