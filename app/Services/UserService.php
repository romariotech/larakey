<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct(private KeycloakUserService $keycloakUserService)
    {
    }

    /**
     * Get paginated users with optional filters.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @param  int  $page
     * @return LengthAwarePaginator
     */
    public function getPaginated(
        array $filters = [],
        int $perPage = 15,
        int $page = 1,
    ): LengthAwarePaginator {

        return User::query()
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->whereAny(['email', 'first_name', 'last_name'], 'like', "%{$search}%");
            })
            ->paginate(
                $perPage,
                [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'role',
                    'created_at',
                    'enabled',
                ],
                page: $page
            );
    }

    /**
     * Get all users without pagination.
     *
     * @param  array<string, mixed>  $columns
     * @return Collection
     */
    public function getAll(array $columns = ['*']): Collection
    {
        return User::select($columns)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new user.
     *
     * @param  array<string, mixed>  $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update an existing user.
     *
     * @param  User  $user
     * @param  array<string, mixed>  $data
     * @return bool
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete a user.
     *
     * @param  User  $user
     * @return bool|null
     */
    public function delete(User $user): ?bool
    {
        $this->keycloakUserService->deleteKeycloakUser($user->keycloak_id);

        return $user->delete();
    }

    /**
     * Find a user by ID.
     *
     * @param  int  $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find a user by keycloak ID.
     *
     * @param  string  $keycloakId
     * @return User|null
     */
    public function findByKeycloakId(string $keycloakId): ?User
    {
        return User::where('keycloak_id', $keycloakId)->first();
    }

    /**
     * Check if a keycloak ID exists.
     *
     * @param  string  $keycloakId
     * @return bool
     */
    public function keycloakIdExists(string $keycloakId): bool
    {
        return User::where('keycloak_id', $keycloakId)->exists();
    }

    /**
     * Check if a user exists by email.
     *
     * @param  string  $email
     * @param  int|null  $excludeId
     * @return bool
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = User::where('email', $email);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Assign a role to a user.
     *
     * @param  User  $user
     * @param  string|array<string>  $role
     * @return void
     */
    public function assignRole(User $user, $role): void
    {
        $user->assignRole($role);
    }

    /**
     * Sync roles for a user.
     *
     * @param  User  $user
     * @param  array<string>  $roles
     * @return void
     */
    public function syncRoles(User $user, array $roles): void
    {
        $user->syncRoles($roles);
    }

    /**
     * Remove a role from a user.
     *
     * @param  User  $user
     * @param  string  $role
     * @return void
     */
    public function removeRole(User $user, string $role): void
    {
        $user->removeRole($role);
    }

    /**
     * Check if user has a specific permission.
     *
     * @param  User  $user
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission(User $user, string $permission): bool
    {
        return $user->hasPermissionTo($permission);
    }

    /**
     * Get user count.
     *
     * @return int
     */
    public function count(): int
    {
        return User::count();
    }

    /**
     * Get recently created users.
     *
     * @param  int  $limit
     * @return Collection
     */
    public function getRecent(int $limit = 10): Collection
    {
        return User::latest('created_at')->limit($limit)->get();
    }
}
