export type User = {
    id: number;
    first_name: string;
    last_name: string;
    full_name: string;
    email: string;
    username: string;
    role: Role;
    enabled: boolean;
    keycloak_id?: string;
    created_at: string;
    updated_at: string;
};

export type Auth = {
    user: User;
};

export type Role = 'admin' | 'agente' | 'user'

export interface CreateUserDTO {
    first_name: string
    last_name: string
    email: string
    username: string
    role: Role
}

export type UpdateUserDTO = CreateUserDTO
