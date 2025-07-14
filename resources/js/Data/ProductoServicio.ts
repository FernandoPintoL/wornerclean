import { BaseData } from '@/Data/BaseData';
export interface ProductoServicio{
    id?: number;
    servicio_id: number;
    producto_id: number;
    cantidad: number;
    created_at?: string;
    updated_at?: string;
}
export class ProductoServicioData extends BaseData<ProductoServicio> {
    protected path_api_url: string = 'api.producto-servicio';
}
export function getDefaultAttributes() {
    return {
        cantidad: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'cantidad', label: 'Cantidad', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
