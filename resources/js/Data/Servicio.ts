import { BaseData } from '@/Data/BaseData';
export interface Servicio {
    id?: number;
    nombre: string;
    descripcion: string;
    precio: number;
    frecuencia: string; // Puede ser 'diario', 'semanal', 'mensual', etc.
    estado: string; // Puede ser 'activo', 'inactivo', etc.
    created_at?: string;
    updated_at?: string;
}
export class ServicioData extends BaseData<Servicio>{
    protected path_api_url: string = 'api.servicio';
}
export function getDefaultAttributes() {
    return {
        id: true,
        nombre: true,
        descripcion: true,
        precio: true,
        frecuencia: true,
        estado: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'nombre', label: 'Nombre', isSearch: true },
    { key: 'descripcion', label: 'Descripción', isSearch: true },
    { key: 'precio', label: 'Precio', isSearch: true },
    { key: 'frecuencia', label: 'Frecuencia', isSearch: true },
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
