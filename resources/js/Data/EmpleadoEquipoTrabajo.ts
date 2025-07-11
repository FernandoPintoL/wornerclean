import { BaseData } from '@/Data/BaseData';
export interface EmpleadoEquipoTrabajo {
    id?: number;
    empleado_id: number;
    equipo_trabajo_id: number;
    estado?: string;
    ocupacion?: string;
    created_at?: string;
    updated_at?: string;
}
export class EmpleadoEquipoTrabajoData extends BaseData<EmpleadoEquipoTrabajo> {
    protected path_api_url: string = 'api.empleado_equipo_trabajo';
}
export function getDefaultAttributes() {
    return {
        id: true,
        empleado_id: true,
        equipo_trabajo_id: true,
        estado: true,
        ocupacion: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'empleado_id', label: 'Empleado ID', isSearch: true },
    { key: 'equipo_trabajo_id', label: 'Equipo Trabajo ID', isSearch: true },
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'ocupacion', label: 'Ocupación', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
