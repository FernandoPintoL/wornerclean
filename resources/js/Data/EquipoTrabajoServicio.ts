import { BaseData } from '@/Data/BaseData';
export interface EquipoTrabajoServicio {
    id?: number;
    equipo_trabajo_id: number;
    servicio_id: number;
    estado?: string;
    created_at?: string;
    updated_at?: string;
}
export class EquipoTrabajoServicioData extends BaseData<EquipoTrabajoServicio> {
    protected path_api_url: string = 'api.equipo-trabajo-servicio';
}
export function getDefaultAttributes() {
    return {
        id: true,
        equipo_trabajo_id: true,
        servicio_id: true,
        estado: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'equipo_trabajo_id', label: 'Equipo Trabajo ID', isSearch: true },
    { key: 'servicio_id', label: 'Servicio ID', isSearch: true },
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
