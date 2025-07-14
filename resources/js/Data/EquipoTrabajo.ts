import { BaseData } from '@/Data/BaseData';
export interface EquipoTrabajo {
    id?: number;
    nombre: string;
    descripcion?: string;
    estado?: string;
    created_at?: string;
    updated_at?: string;
}
export class EquipoTrabajoData extends BaseData<EquipoTrabajo>{
    protected path_api_url: string = 'api.equipo-trabajo';
}
export function getDefaultAttributes() {
    return {
        id: true,
        nombre: true,
        descripcion: true,
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
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
