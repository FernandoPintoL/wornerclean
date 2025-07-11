import { BaseData } from '@/Data/BaseData';
export interface ContratoIncidencia {
    id?: number;
    contrato_id: number;
    incidencia_id: number;
    estado: string;
    descripcion?: string;
    fecha_solucion?: string;
    created_at?: string;
    updated_at?: string;
}
export class ContratoIncidenciaData extends BaseData<ContratoIncidencia> {
    protected path_api_url: string = 'api.contrato_incidencia';
}
export function getDefaultAttributes() {
    return {
        id: true,
        contrato_id: true,
        incidencia_id: true,
        estado: true,
        descripcion: true,
        fecha_solucion: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'contrato_id', label: 'Contrato ID', isSearch: true },
    { key: 'incidencia_id', label: 'Incidencia ID', isSearch: true },
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'descripcion', label: 'Descripción', isSearch: true },
    { key: 'fecha_solucion', label: 'Fecha Solución', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
