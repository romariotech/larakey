
import api from '@/lib/axios';
import { paginated, store, destroy as destroyById, update as updateById, show } from '@/routes/users';
import type { User } from '@/types';
import type { PaginatedResponse } from '@/types/global';

export type UserFilters = {
    page?: number;
    per_page?: number;
    search?: string;
}

export type CreateUserDTO = {
    first_name: string;
    last_name: string;
    email: string;
    role: string;
}

export type UpdateUserDTO = {
    first_name?: string;
    last_name?: string;
    email?: string;
    role?: string;
}

export function useUserService() {

    async function getAll(
        filters: UserFilters = {},
    ): Promise<PaginatedResponse<User>> {

        const response = await api.get<PaginatedResponse<User>>(paginated({
            query: filters,
        }).url);

        return response.data;
    }

    async function getById(id: number): Promise<User> {
        const response = await api.get<User>(show({ id }).url);

        return response.data;
    }

    async function create(payload: CreateUserDTO): Promise<User> {
        const response = await api.post<User>(store().url, payload);

        return response.data;
    }

    async function update(
        id: number,
        payload: UpdateUserDTO,
    ): Promise<User> {
        const response = await api.put<User>(updateById(id).url, payload);

        return response.data;
    }

    async function destroy(id: number): Promise<void> {
        await api.delete(destroyById(id).url);
    }

    return {
        getAll,
        getById,
        create,
        update,
        destroy,
    };
}
