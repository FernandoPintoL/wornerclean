import { BaseData } from '@/Data/BaseData';

export interface Empleado {
    id?: number;
    ci: string;
    nombre: string;
    telefono: string;
    puesto: string;
    estado: string;
    user_id?: number | null;
    created_at?: string;
    updated_at?: string;
}
export class EmpleadoData extends BaseData<Empleado> {
    protected path_api_url: string = 'api.empleado';
}
export function getDefaultAttributes() {
    return {
        id: true,
        ci: true,
        nombre: true,
        telefono: true,
        puesto: true,
        estado: true,
        user_id: false,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'ci', label: 'CI', isSearch: true },
    { key: 'nombre', label: 'Nombre', isSearch: true },
    { key: 'telefono', label: 'Teléfono', isSearch: true },
    { key: 'puesto', label: 'Puesto', isSearch: true },
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
