
export class Role {
    id: number;
    name: string;
    user_count: number;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;
    constructor() {
    }
};
export class User {
    id: number;
    employee_code: string;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
    constructor() {
    }
}

export interface Repo {
    status: string;
    message: string;
    users: User[];
}

export interface OneRole {
    status: string;
    message: string;
    role: Role;
}
export interface RoleUser {
    id: Number;
    name: string;
    user_count: number;
    email: string;
    created_at: string;
    updated_at: string;
}
export interface RoleUserRepo {
    status: string;
    message: string;
    users: RoleUser[];
}
export interface ListRoleResponse {
    status: string;
    message: string;
    roles: Role[];
}