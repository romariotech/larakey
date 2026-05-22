<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\KeycloakUserService;
use App\Services\UserService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private KeycloakUserService $keycloakUserService,
        private UserService $userService
    ) {
    }

    /**
     * Create a new user in the database and Keycloak
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {

            $user = $this->userService->create($request->validated());

            return response()->json([
                'message' => 'Usuário cadastrado com sucesso!',
                'user' => $user->except(['password', 'remember_token']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List all users
     */
    public function index(): Response
    {
        return Inertia::render('user/Index');
    }

    /**
     * Show the form to create a new user
     */
    public function create(): Response
    {
        return Inertia::render('user/Form');
    }
    public function list(Request $request): JsonResponse
    {
        return response()->json(
            $this->userService->getPaginated(
                $request->only(['search']),
                $request->input('per_page', 10),
                $request->input('page', 1)
            )
        );
    }

    /**
     * Show a specific user
     */
    public function show(User $user): Response
    {
        return Inertia::render('user/Form', [
            'user' => $user->only([
                'id',
                'first_name',
                'last_name',
                'full_name',
                'email',
                'username',
                'role',
                'enabled',
                'created_at',
                'keycloak_id',
            ]),
        ]);
    }

    /**
     * Update an existing user
     */
    public function update(StoreUserRequest $request, User $user): JsonResponse
    {
        try {
            $this->userService->update($user, $request->validated());

            return response()->json([
                'message' => 'Usuário atualizado com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a user
     */
    public function destroy(User $user): JsonResponse
    {
        try {

            $this->userService->delete($user);

            return response()->json([
                'message' => 'Usuário deletado com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user: ' . $e->getMessage(),
            ], 500);
        }
    }
}
