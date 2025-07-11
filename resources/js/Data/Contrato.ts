import { BaseData } from '@/Data/BaseData';
export interface Contrato {
    id?: number;
    cliente_id: number;
    servicio_id: number;
    descripcion: string;
    costo_total: number;
    estado: string;
    fecha_inicio: string;
    fecha_fin: string;
    estado_pago: string;
    monto_pagado?: number;
    monto_saldo?: number;
    created_at?: string;
    updated_at?: string;
}

export class ContratoData extends BaseData<Contrato> {
    protected path_api_url: string = 'api.contrato';
}
export function getDefaultAttributes() {
    return {
        id: true,
        cliente_id: true,
        servicio_id: true,
        descripcion: true,
        costo_total: true,
        estado: true,
        fecha_inicio: true,
        fecha_fin: true,
        estado_pago: true,
        monto_pagado: true,
        monto_saldo: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'cliente_id', label: 'Cliente ID', isSearch: true },
    { key: 'servicio_id', label: 'Servicio ID', isSearch: true },
    { key: 'descripcion', label: 'Descripción', isSearch: true },
    { key: 'costo_total', label: 'Costo Total', isSearch: true },
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'fecha_inicio', label: 'Fecha Inicio', isSearch: true },
    { key: 'fecha_fin', label: 'Fecha Fin', isSearch: true },
    { key: 'estado_pago', label: 'Estado Pago', isSearch: true },
    { key: 'monto_pagado', label: 'Monto Pagado', isSearch: true },
    { key: 'monto_saldo', label: 'Monto Saldo', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
