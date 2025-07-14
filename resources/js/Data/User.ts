import { BaseData } from '@/Data/BaseData';

export interface Usuario {
    id?: number;
    name: string;
    email: string;
    usernick: string;
    password?: string;
    created_at?: string;
    updated_at?: string;
}
export class UserData extends BaseData<Usuario>{
    protected path_api_url: string = 'api.users';
}
export function getDefaultAttributes() {
    return {
        id: true,
        name: true,
        email: true,
        usernick: true,
        password: false,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'name', label: 'Nombre', isSearch: true },
    { key: 'email', label: 'Email', isSearch: true },
    { key: 'usernick', label: 'Usernick', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
