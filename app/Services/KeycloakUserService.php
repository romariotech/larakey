<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Traits\KeycloakCommonTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class KeycloakUserService
{
    use KeycloakCommonTrait;

    private const CACHE_TTL_7_DAYS = 60 * 60 * 24 * 7;

    /**
     * Create a new user in the database and in Keycloak
     *
     * @param  User  $user
     * @return void
     * @throws \Exception
     */
    public function create(User $user): void
    {
        $response = $this->makeAdminRequest('POST', '/users', [
            'email' => $user->email,
            'username' => $user->username,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'enabled' => $user->enabled,
            'emailVerified' => false,
            'requiredActions' => ['VERIFY_EMAIL'],
            'credentials' => [
                [
                    'type' => 'password',
                    'value' => Str::random(16),
                    'temporary' => true,
                ],
            ],
        ]);

        $keycloakUserId = null;

        if ($response->successful() || $response->status() === 201) {
            $location = $response->header('Location');
            if ($location) {
                $keycloakUserId = basename($location);
            }
        }

        try {
            if ($response->status() === 409) {
                throw new \Exception('This email already exists in Keycloak.');
            }

            throw_if(!$keycloakUserId, 'Failed to create user in Keycloak: ' . $response->body());

            $this->assignClientRole($keycloakUserId, $user->role);

            $this->sendExecuteActionsEmail($keycloakUserId);

            $user->keycloak_id = $keycloakUserId;
            $user->save();

        } catch (\Exception $e) {

            if ($keycloakUserId) {

                $this->delete($keycloakUserId);
            }

            throw new \Exception('Failed to save user to the local database. The Keycloak creation was rolled back. Error: ' . $e->getMessage());
        }
    }

    /**
     * Update a user in Keycloak
     *
     * @param  string  $keycloakUserId
     * @param  array  $data
     * @return bool
     * @throws \Exception
     */
    public function update(string $keycloakUserId, array $data): bool
    {
        $response = $this->makeAdminRequest('PUT', "/users/{$keycloakUserId}", $data);

        throw_if(!$response->successful(), 'Failed to update user in Keycloak. Status: ' . $response->status() . ' - ' . $response->body());

        return true;
    }

    /**
     * Delete a user from Keycloak
     *
     * @param  string  $keycloakUserId
     * @return bool
     * @throws \Exception
     */
    public function delete(string $keycloakUserId): bool
    {
        $response = $this->makeAdminRequest('DELETE', "/users/{$keycloakUserId}");

        throw_if(!$response->successful(), 'Failed to delete user from Keycloak. Status: ' . $response->status() . ' - ' . $response->body());

        return true;
    }

    /**
     * Send the verification and/or password reset email
     *
     * @param string $keycloakUserId
     * @param array $actions Actions to execute, e.g., ['VERIFY_EMAIL', 'UPDATE_PASSWORD']
     * @return bool
     * @throws \Exception
     */
    public function sendExecuteActionsEmail(string $keycloakUserId, array $actions = ['VERIFY_EMAIL']): bool
    {
        $response = $this->makeAdminRequest(
            'PUT',
            "/users/{$keycloakUserId}/execute-actions-email",
            $actions
        );

        throw_if(!$response->successful(), 'Failed to send execute actions email from Keycloak: ' . $response->body());

        return true;
    }

    /**
     * Get the UUID of the Keycloak client by its client_id, with caching
     *
     * @return string
     */
    protected function getClientUuid(): string
    {
        return Cache::remember('keycloak_client_uuid', self::CACHE_TTL_7_DAYS, function () {

            $response = $this->makeAdminRequest('GET', "/clients?clientId={$this->getClientId()}");

            throw_if(
                !$response->successful() || empty($response->json()),
                "Failed to retrieve client UUID from Keycloak for client_id '{$this->getClientId()}'. Status: " . $response->status() . ' - ' . $response->body()
            );

            return $response->json()[0]['id'];
        });
    }

    /**
     * Get the role data for a specific client role, with caching
     * @param string $clientUuid
     * @param string $roleName
     * @return array
     */
    protected function getClientRolesData(string $clientUuid, string $roleName): array
    {
        return Cache::remember("keycloak_client_roles_data_{$clientUuid}_{$roleName}", self::CACHE_TTL_7_DAYS, function () use ($clientUuid, $roleName) {

            $response = $this->makeAdminRequest('GET', "/clients/{$clientUuid}/roles/{$roleName}");

            throw_if(
                !$response->successful(),
                "Failed to retrieve role data from Keycloak for client UUID '{$clientUuid}' and role '{$roleName}'. Status: " . $response->status() . ' - ' . $response->body()
            );

            return $response->json();
        });
    }

    /**
     * Assign a client role to a user in Keycloak
     * @param string $keycloakUserId
     * @param RoleEnum $role
     * @throws \Exception
     * @return void
     */
    public function assignClientRole(string $keycloakUserId, RoleEnum $role): void
    {
        $clientUuid = $this->getClientUuid();
        $roleData = $this->getClientRolesData($clientUuid, $role->value);

        $assignResponse = $this->makeAdminRequest(
            'POST',
            "/users/{$keycloakUserId}/role-mappings/clients/{$clientUuid}",
            [
                [
                    'id' => $roleData['id'],
                    'name' => $roleData['name'],
                ]
            ]
        );

        throw_if(!$assignResponse->successful(), 'Failed to assign role in Keycloak: ' . $assignResponse->body());
    }

    /**
     * Sync the user's client role in Keycloak
     *
     * @param string $keycloakUserId
     * @param string $roleName
     * @return void
     * @throws \Exception
     */
    public function syncClientRole(string $keycloakUserId, string $roleName): void
    {
        $clientUuid = $this->getClientUuid();

        $currentRolesResponse = $this->makeAdminRequest(
            'GET',
            "/users/{$keycloakUserId}/role-mappings/clients/{$clientUuid}"
        );

        throw_if(!$currentRolesResponse->successful(), 'Failed to retrieve current roles from Keycloak: ' . $currentRolesResponse->body());

        $currentRoles = $currentRolesResponse->json();

        if (!empty($currentRoles)) {
            $removeResponse = $this->makeAdminRequest(
                'DELETE',
                "/users/{$keycloakUserId}/role-mappings/clients/{$clientUuid}",
                $currentRoles
            );

            throw_if(!$removeResponse->successful(), 'Failed to remove existing roles in Keycloak: ' . $removeResponse->body());
        }

        $this->assignClientRole($keycloakUserId, RoleEnum::from($roleName));
    }
}
