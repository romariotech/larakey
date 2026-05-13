<?php

namespace App\Services;

use App\Models\User;
use Http;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class KeycloakAuthService
{
    /**
     * Obter a URL de redirect para o Keycloak
     */
    public function getKeycloakRedirectUrl(): string
    {
        return Socialite::driver('keycloak')->redirect()->getTargetUrl();
    }

    /**
     * Processar o callback do Keycloak
     *
     * @return void
     * @throws \Exception
     */
    public function handleKeycloakCallback(): void
    {
        $socialiteUser = Socialite::driver('keycloak')->user();

        $user = $this->createOrUpdateUserFromData([
            'email' => $socialiteUser->getEmail(),
            'name' => $socialiteUser->getName(),
        ]);

        $this->syncUserRolesFromArray(
            $user,
            $socialiteUser->user['realm_access']['roles'] ?? []
        );

        Auth::login($user);

        $this->storeTokensInSession($socialiteUser);
    }

    /**
     * Logout do Keycloak
     */
    public function getKeycloakLogoutUrl(string $idToken): string
    {
        $baseUrl = config('services.keycloak.base_url');
        $realm = config('services.keycloak.realms');
        $clientId = config('services.keycloak.client_id');

        return "{$baseUrl}/realms/{$realm}/protocol/openid-connect/logout?" . http_build_query([
            'post_logout_redirect_uri' => url('/'),
            'id_token_hint' => $idToken,
            'client_id' => $clientId,
        ]);
    }

    /**
     * Autenticar com credenciais externas
     *
     * @param  string  $username
     * @param  string  $password
     * @return array
     * @throws \Exception
     */
    public function authenticateWithCredentials(string $username, string $password): array
    {
        $keycloakBaseUrl = config('services.keycloak.base_url');
        $realm = config('services.keycloak.realms');

        $response = Http::asForm()->post("$keycloakBaseUrl/realms/$realm/protocol/openid-connect/token", [
            'client_id' => config('services.keycloak.client_id'),
            'client_secret' => config('services.keycloak.client_secret'),
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password,
        ]);

        if (!$response->successful()) {

            throw new \Exception('Credenciais inválidas ou acesso negado.');
        }

        $tokens = $response->json();
        $tokenParts = explode('.', $tokens['access_token']);
        $payload = json_decode(base64_decode(strtr($tokenParts[1], '-_', '+/')), true);

        $user = $this->createOrUpdateUserFromData([
            'email' => $payload['email'] ?? '',
            'name' => $payload['name'] ?? $payload['preferred_username'] ?? $username,
        ]);

        $keycloakRoles = $payload['realm_access']['roles'] ?? [];
        $this->syncUserRolesFromArray($user, $keycloakRoles);

        return $tokens;
    }

    /**
     * Criar ou atualizar usuário baseado em dados
     *
     * @param  array  $userData
     * @return \App\Models\User
     */
    private function createOrUpdateUserFromData(array $userData): User
    {
        return User::updateOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'],
                'password' => bcrypt(str()->random(16)),
            ]
        );
    }

    /**
     * Sincronizar roles do usuário baseado em um array de roles
     *
     * @param  \App\Models\User  $user
     * @param  array  $keycloakRoles
     * @return void
     */
    private function syncUserRolesFromArray(User $user, array $keycloakRoles): void
    {
        $availableRoles = Role::pluck('name')->toArray();
        $rolesToSync = array_intersect($keycloakRoles, $availableRoles);

        $user->syncRoles($rolesToSync);
    }

    /**
     * Armazenar tokens na sessão
     *
     * @param  \Laravel\Socialite\Contracts\User  $socialiteUser
     * @return void
     */
    private function storeTokensInSession($socialiteUser): void
    {
        session([
            'id_token' => $socialiteUser->accessTokenResponseBody['id_token'] ?? null,
            'access_token' => $socialiteUser->token ?? null,
            'refresh_token' => $socialiteUser->refreshToken ?? null,
        ]);
    }
}
