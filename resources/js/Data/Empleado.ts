import { BaseData } from '@/Data/BaseData';

export interface Empleado {
    id?: number;
    nombre: string;
    ci: string;
    tipo_documento_id: number;
    direccion: string;
    telefono: string;
    email: string;
    empleado_cargo_id: number;
    user_id: number;
    created_at?: string;
    updated_at?: string;
}
export class EmpleadoData extends BaseData<Empleado> {
    protected path_api_url: string = 'api.empleado';
}
export function getDefaultAttributes() {
    return {
        id: true,
        nombre: true,
        ci: true,
        tipo_documento_id: true,
        direccion: true,
        telefono: true,
        email: true,
        empleado_cargo_id: true,
        user_id: false,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'nombre', label: 'Nombre', isSearch: true },
    { key: 'ci', label: 'Número ID', isSearch: true },
    { key: 'tipo_documento_id', label: 'Tipo Documento', isSearch: true },
    { key: 'direccion', label: 'Dirección', isSearch: true },
    { key: 'telefono', label: 'Teléfono', isSearch: true },
    { key: 'email', label: 'Email', isSearch: true },
    { key: 'empleado_cargo_id', label: 'Cargo', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
