import { BaseData } from '@/Data/BaseData';

export interface Incidencia {
    id?: number;
    nombre: string;
    descripcion: string;
    created_at?: string;
    updated_at?: string;
}
export class IncidenciaData extends BaseData<Incidencia>{
    protected path_api_url: string = 'api.incidencias';
}
export function getDefaultAttributes() {
    return {
        id: true,
        nombre: true,
        descripcion: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'nombre', label: 'Nombre', isSearch: true },
    { key: 'descripcion', label: 'Descripción', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
