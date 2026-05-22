<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Log;
use Spatie\Permission\Models\Role;
use InvalidArgumentException;

use function count;

trait KeycloakCommonTrait
{
    private function getBaseUrl(): string
    {
        return config('services.keycloak.base_url');
    }

    private function getRealm(): string
    {
        return config('services.keycloak.realms');
    }

    private function getClientId(): string
    {
        return config('services.keycloak.client_id');
    }

    /**
     * Get the Keycloak Admin API URL.
     *
     * @return string
     */
    protected function getKeycloakAdminUrl(): string
    {
        return "{$this->getBaseUrl()}/admin/realms/{$this->getRealm()}";
    }

    /**
     * Get the Keycloak base URL.
     *
     * @return string
     */
    protected function getKeycloakBaseUrl(): string
    {
        return "{$this->getBaseUrl()}/realms/{$this->getRealm()}";
    }

    /**
     * Make an authenticated request to the Keycloak Admin API.
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE).
     * @param string $endpoint API endpoint.
     * @param mixed|null $data Request payload.
     * @return \Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function makeAdminRequest(string $method, string $endpoint, mixed $data = null)
    {
        $token = $this->getKeycloakAdminToken();
        $url = $this->getKeycloakAdminUrl() . $endpoint;

        $request = Http::withToken($token);

        return match (strtoupper($method)) {
            'GET' => $request->get($url),
            'POST' => $request->post($url, $data ?? []),
            'PUT' => $request->put($url, $data ?? []),
            'DELETE' => $request->delete($url),
            default => throw new \Exception("Invalid HTTP method: {$method}"),
        };
    }

    /**
     * Get an admin access token from Keycloak.
     *
     * @return string
     * @throws \Exception
     */
    public function getKeycloakAdminToken(): string
    {
        $response = Http::asForm()->post("{$this->getBaseUrl()}/realms/{$this->getRealm()}/protocol/openid-connect/token", [
            'client_id' => config('services.keycloak.client_id'),
            'client_secret' => config('services.keycloak.client_secret'),
            'grant_type' => 'client_credentials',
        ]);

        throw_unless($response->successful(), 'Failed to obtain Keycloak admin token. Status: ' . $response->status() . ' - ' . $response->body());

        return $response->json()['access_token'];
    }

    /**
     * Create or update a user in the local database based on Keycloak data.
     *
     * @param array $userData User data including 'username', 'email', 'first_name', 'last_name', 'role', and optionally 'keycloak_id'.
     * @return \App\Models\User
     */
    public function createOrUpdateUserFromData(array $userData): User
    {
        $updateData = [
            ...$userData,
            'password' => bcrypt(Str::random(16)),
        ];

        if (isset($userData['keycloak_id'])) {
            $updateData['keycloak_id'] = $userData['keycloak_id'];
        }

        return User::updateOrCreate(
            ['email' => $userData['email']],
            $updateData
        );
    }

    /**
     * Synchronize user roles based on an array of Keycloak roles.
     *
     * @param \App\Models\User $user
     * @param array $keycloakRoles
     * @return void
     * @throws \InvalidArgumentException
     */
    public function syncUserRolesFromArray(User $user, array $keycloakRoles): void
    {
        $rolesToSync = array_intersect(
            $keycloakRoles,
            Role::pluck('name')->toArray()
        );

        throw_if(empty($rolesToSync), 'No matching roles found for synchronization. Check available roles and Keycloak configuration.');

        throw_if(count($rolesToSync) > 1, 'Keycloak configuration error: only one role expected per user.');

        $user->syncRoles($rolesToSync);

        $user->role = $rolesToSync[0];

        $user->save();
    }
}
