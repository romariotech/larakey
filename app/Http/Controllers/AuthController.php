<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    /**
     * Redireciona o usuário para a tela de login do Keycloak.
     */
    public function redirect()
    {
        return inertia()->location(
            Socialite::driver('keycloak')->redirect()->getTargetUrl()
        );
    }

    /**
     * Recebe o retorno do Keycloak com os dados do usuário.
     */
    public function callback()
    {
        try {
            // Pega as informações do usuário retornadas pelo Keycloak
            $socialiteUser = Socialite::driver('keycloak')->user();

            // Busca o usuário no banco local pelo email, ou cria um novo se não existir
            $user = User::updateOrCreate(
                ['email' => $socialiteUser->getEmail()],
                [
                    'name' => $socialiteUser->getName(),
                    'password' => bcrypt(str()->random(16)),
                ]
            );

            $user->syncRoles(array_intersect(
                $socialiteUser->user['realm_access']['roles'] ?? [],
                Role::pluck('name')->toArray()
            ));

            Auth::login($user);

            session([
                'id_token' => $socialiteUser->accessTokenResponseBody['id_token'] ?? null,
                'access_token' => $socialiteUser->token ?? null,
                'refresh_token' => $socialiteUser->refreshToken ?? null,
            ]);

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {

            return redirect('/')->withErrors(['error' => 'Falha ao autenticar com Keycloak.']);
        }
    }

    public function logout(Request $request)
    {
        $idToken = session('id_token');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $baseUrl = config('services.keycloak.base_url', 'http://localhost:8080/auth');
        $realm = config('services.keycloak.realms', 'meu-app-realm');

        $keycloakLogoutUrl = "{$baseUrl}/realms/{$realm}/protocol/openid-connect/logout?" . http_build_query([
            'post_logout_redirect_uri' => url('/'),
            'id_token_hint' => $idToken,
            'client_id' => config('services.keycloak.client_id', 'laravel-vue-app'),
        ]);

        return inertia()->location($keycloakLogoutUrl);
    }
}
