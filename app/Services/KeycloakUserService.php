<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class KeycloakUserService extends KeycloakCommonService
{
    /**
     * Create a new user in the database and in Keycloak
     *
     * @param  array  $data
     * @return string|null Retorna o ID do usuário criado no Keycloak
     * @throws \Exception
     */
    public function createKeycloakUser(array $data): ?string
    {
        $tempPassword = $data['password'] ?? Str::random(16);

        $response = $this->makeAdminRequest('POST', '/users', [
            'email' => $data['email'],
            'username' => $data['username'],
            'firstName' => $data['first_name'],
            'lastName' => $data['last_name'],
            'enabled' => (bool) ($data['enabled'] ?? false),
            'emailVerified' => false,
            'requiredActions' => ['VERIFY_EMAIL'],
            'credentials' => [
                [
                    'type' => 'password',
                    'value' => $tempPassword,
                    'temporary' => true,
                ],
            ],
        ]);

        if ($response->successful() || $response->status() === 201) {
            $location = $response->header('Location');
            if ($location) {
                return basename($location);
            }
        }

        if ($response->status() === 409) {
            throw new \Exception('This email already exists in Keycloak.');
        }

        throw new \Exception('Failed to create user in Keycloak: ' . $response->body());
    }

    /**
     * Create a new user in the database and in Keycloak
     *
     * @param  array  $data
     * @return User
     * @throws \Exception
     */
    public function createUser(array $data): User
    {
        $keycloakUserId = $this->createKeycloakUser($data);

        try {

            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'password' => bcrypt(Str::random(16)),
                'keycloak_id' => $keycloakUserId,
                'role' => 'user',
                'enabled' => (bool) ($data['enabled'] ?? false),
            ]);

            $this->sendExecuteActionsEmail($keycloakUserId);

            return $user;

        } catch (\Exception $e) {

            $this->deleteKeycloakUser($keycloakUserId);
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
    public function updateKeycloakUser(string $keycloakUserId, array $data): bool
    {
        $response = $this->makeAdminRequest('PUT', "/users/{$keycloakUserId}", $data);

        if (!$response->successful()) {
            throw new \Exception('Failed to update user in Keycloak.');
        }

        return true;
    }

    /**
     * Delete a user from Keycloak
     *
     * @param  string  $keycloakUserId
     * @return bool
     * @throws \Exception
     */
    public function deleteKeycloakUser(string $keycloakUserId): bool
    {
        $response = $this->makeAdminRequest('DELETE', "/users/{$keycloakUserId}");

        if (!$response->successful()) {
            throw new \Exception('Failed to delete user from Keycloak.');
        }

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

        if (!$response->successful()) {
            throw new \Exception('Failed to send execute actions email from Keycloak: ' . $response->body());
        }

        return true;
    }

    /**
     * Assign a Realm Role to a user
     *
     * @param string $keycloakUserId
     * @param string $roleName
     * @return void
     * @throws \Exception
     */
    public function assignRealmRole(string $keycloakUserId, string $roleName): void
    {
        $roleResponse = $this->makeAdminRequest('GET', "/roles/{$roleName}");

        if (!$roleResponse->successful()) {
            throw new \Exception("Role '{$roleName}' not found in Keycloak.");
        }

        $roleData = $roleResponse->json();

        $assignResponse = $this->makeAdminRequest(
            'POST',
            "/users/{$keycloakUserId}/role-mappings/realm",
            [
                [
                    'id' => $roleData['id'],
                    'name' => $roleData['name'],
                ]
            ]
        );

        if (!$assignResponse->successful()) {
            throw new \Exception('Failed to assign role in Keycloak: ' . $assignResponse->body());
        }
    }
}
