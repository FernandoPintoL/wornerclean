import { BaseData } from '@/Data/BaseData';
export interface Cliente {
    id?: number;
    ci: string;
    nombre: string;
    direccion: string;
    telefono: string;
    tipo_cliente: string;
    created_at?: string;
    updated_at?: string;
}
export class ClienteData extends BaseData<Cliente> {
    protected path_api_url: string = 'api.cliente';
}

export function getDefaultAttributes() {
    return {
        id: true,
        ci: true,
        nombre: true,
        direccion: true,
        telefono: true,
        tipo_cliente: true,
        created_at: true,
        updated_at: true,
    } as Record<string, boolean>;
}

export const selectedAttributes = getDefaultAttributes();

export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'ci', label: 'CI', isSearch: true },
    { key: 'nombre', label: 'Nombre', isSearch: true },
    { key: 'direccion', label: 'Dirección', isSearch: true },
    { key: 'telefono', label: 'Teléfono', isSearch: true },
    { key: 'tipo_cliente', label: 'Tipo Cliente', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
