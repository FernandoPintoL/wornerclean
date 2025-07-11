import { BaseData } from '@/Data/BaseData';
export interface Producto{
    id?: number;
    nombre: string;
    descripcion: string;
    precio: number;
    stock: number;
    created_at?: string;
    updated_at?: string;
}

export class ProductoData extends BaseData<Producto>{
    protected path_api_url: string = 'api.producto';
}
export function getDefaultAttributes() {
    return {
        id: true,
        nombre: true,
        descripcion: true,
        precio: true,
        stock: true,
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
    { key: 'stock', label: 'Stock', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
