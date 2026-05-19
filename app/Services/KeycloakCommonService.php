<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;

use function count;

abstract class KeycloakCommonService
{
    protected $baseUrl;

    protected $realm;

    protected $clientId;

    public function __construct()
    {
        $this->baseUrl = config('services.keycloak.base_url');
        $this->realm = config('services.keycloak.realms');
        $this->clientId = config('services.keycloak.client_id');
    }

    /**
     * Get the Keycloak Admin API URL
     *
     * @return string
     */
    protected function getKeycloakAdminUrl(): string
    {
        return "{$this->baseUrl}/admin/realms/{$this->realm}";
    }

    /**
     * Get the Keycloak base URL
     *
     * @return string
     */
    protected function getKeycloakBaseUrl(): string
    {
        return "{$this->baseUrl}/realms/{$this->realm}";
    }

    /**
     * Get a Keycloak admin access token and make an authenticated request to the Keycloak Admin API
     *
     * @param  string  $method
     * @param  string  $endpoint
     * @param  mixed  $data
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
            default => throw new \Exception("Método HTTP inválido: {$method}"),
        };
    }

    /**
     * Get an admin access token from Keycloak
     *
     * @return string
     * @throws \Exception
     */
    public function getKeycloakAdminToken(): string
    {
        $response = Http::asForm()->post("$this->baseUrl/realms/$this->realm/protocol/openid-connect/token", [
            'client_id' => config('services.keycloak.client_id'),
            'client_secret' => config('services.keycloak.client_secret'),
            'grant_type' => 'client_credentials',
        ]);

        if (!$response->successful()) {
            throw new \Exception('Falha ao obter token de administrador do Keycloak.');
        }

        return $response->json()['access_token'];
    }

    /**
     * Create a new user in Keycloak
     *
     * @param  array  $userData Array com 'username', 'email', 'first_name', 'last_name', 'role' e opcionalmente 'keycloak_id'
     * @return \App\Models\User
     */
    public function createOrUpdateUserFromData(array $userData): User
    {
        $updateData = [
            ...$userData,
            'password' => bcrypt(str()->random(16)),
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
     * Sincronyze user roles based on an array of Keycloak roles
     *
     * @param  \App\Models\User  $user
     * @param  array  $keycloakRoles
     * @return void
     */
    public function syncUserRolesFromArray(User $user, array $keycloakRoles): void
    {
        $rolesToSync = array_intersect(
            $keycloakRoles,
            Role::pluck('name')->toArray()
        );

        throw_if(empty($rolesToSync), 'No matching roles found for synchronization. Check available roles and Keycloak configuration.');

        throw_if(count($rolesToSync) > 1, InvalidArgumentException::class, 'Keycloak configuration error: only one role expected per user.');

        $user->syncRoles($rolesToSync);

        $user->role = $rolesToSync[0];

        $user->save();
    }
}
