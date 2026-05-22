<?php

namespace App\Services;

use App\Models\User;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

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
     * Create a new user.
     *
     * @param  array<string, mixed>  $data
     * @return User
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'password' => bcrypt(Str::random(16)),
                'role' => $data['role'] ?? 'user',
                'enabled' => (bool) ($data['enabled'] ?? false),
            ]);

            $this->keycloakUserService->create($user);

            return $user;
        });
    }

    /**
     * Update an existing user.
     *
     * @param  User  $user
     * @param  array<string, mixed>  $data
     * @return void
     */
    public function update(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {

            $user->update($data);

            $user->refresh();

            $this->keycloakUserService->update($user->keycloak_id, [
                'username' => $user->username,
                'email' => $user->email,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'enabled' => $user->enabled,
            ]);

            $this->keycloakUserService->syncClientRole(
                $user->keycloak_id,
                $user->role->value
            );

            $user->syncRoles($user->role);
        });
    }

    /**
     * Delete a user.
     *
     * @param  User  $user
     * @return bool|null
     */
    public function delete(User $user): ?bool
    {
        $this->keycloakUserService->delete($user->keycloak_id);

        return $user->delete();
    }
}
