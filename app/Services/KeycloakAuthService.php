<?php

namespace App\Services;

use App\Traits\KeycloakCommonTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class KeycloakAuthService
{
    use KeycloakCommonTrait;

    /**
     * Get the Keycloak redirect URL for authentication
     */
    public function getKeycloakRedirectUrl(): string
    {
        return Socialite::driver('keycloak')->redirect()->getTargetUrl();
    }

    /**
     * Handle the callback from Keycloak with user data
     *
     * @return void
     * @throws \Exception
     */
    public function handleKeycloakCallback(): void
    {
        /**
         * @var \Laravel\Socialite\Two\User $socialiteUser
         */
        $socialiteUser = Socialite::driver('keycloak')->user();

        $rawUser = $socialiteUser->user;

        $user = $this->createOrUpdateUserFromData([
            'email' => $socialiteUser->getEmail(),
            'username' => $rawUser['preferred_username'] ?? $socialiteUser->getNickname() ?? $socialiteUser->getEmail(),
            "keycloak_id" => $rawUser['sub'] ?? null,
            'first_name' => $rawUser['given_name'] ?? 'User',
            'last_name' => $rawUser['family_name'] ?? '',
        ]);

        $this->syncUserRolesFromArray(
            $user,
            data_get($rawUser, "resource_access.{$this->getClientId()}.roles", [])
        );

        $user = $user->fresh();

        Auth::login($user);

        $this->storeTokensInSession($socialiteUser);
    }

    /**
     * Get the Keycloak logout URL
     */
    public function getKeycloakLogoutUrl(string $idToken): string
    {
        return $this->getKeycloakBaseUrl() . "/protocol/openid-connect/logout?" . http_build_query([
            'post_logout_redirect_uri' => url('/'),
            'id_token_hint' => $idToken,
            'client_id' => $this->getClientId(),
        ]);
    }

    /**
     * Login using external credentials (for API clients)
     *
     * @param  string  $username
     * @param  string  $password
     * @return array
     * @throws \Exception
     */
    public function authenticateWithCredentials(string $username, string $password): array
    {
        $response = Http::asForm()->post($this->getKeycloakBaseUrl() . "/protocol/openid-connect/token", [
            'client_id' => $this->getClientId(),
            'client_secret' => config('services.keycloak.client_secret'),
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password,
        ]);

        throw_if(!$response->successful(), 'Failed to authenticate with Keycloak. Status: ' . $response->status() . ' - ' . $response->body());

        $tokens = $response->json();
        $tokenParts = explode('.', $tokens['access_token']);
        $payload = json_decode(base64_decode(strtr($tokenParts[1], '-_', '+/')), true);

        $user = $this->createOrUpdateUserFromData([
            'username' => $payload['preferred_username'] ?? $username,
            'email' => $payload['email'],
            'first_name' => $payload['given_name'] ?? 'User',
            'last_name' => $payload['family_name'] ?? '',
            'keycloak_id' => $payload['sub'] ?? null,
        ]);

        $this->syncUserRolesFromArray(
            $user,
            data_get($payload, "resource_access.{$this->getClientId()}.roles", [])
        );

        return $tokens;
    }

    /**
     * Store tokens in the session
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

    /**
     * Refresh the access token using the refresh token
     *
     * @param  string  $refreshToken
     * @return array|null Returns new tokens or null if refresh failed
     * @throws \Exception
     */
    public function refreshAccessToken(string $refreshToken): ?array
    {
        $response = Http::asForm()->post($this->getKeycloakBaseUrl() . "/protocol/openid-connect/token", [
            'client_id' => $this->getClientId(),
            'client_secret' => config('services.keycloak.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);

        if (!$response->successful()) {

            return null;
        }

        $tokens = $response->json();

        if (session()->isStarted()) {

            session([
                'id_token' => $tokens['id_token'] ?? session('id_token'),
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'] ?? $refreshToken,
            ]);
        }

        return $tokens;
    }
}
