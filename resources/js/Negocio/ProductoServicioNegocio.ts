import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { ProductoServicio, ProductoServicioData } from '@/Data/ProductoServicio';

export class ProductoServicioNegocio extends BaseNegocio<ProductoServicio>{
    public model: string = 'producto_servicio';
    protected dataService: ProductoServicioData;

    constructor() {
        super();
        this.dataService = new ProductoServicioData();
    }

    protected validar(productoServicio: ProductoServicio): void {
        if (!productoServicio.servicio_id) {
            throw new Error('El ID del servicio es requerido');
        }
        if (!productoServicio.producto_id) {
            throw new Error('El ID del producto es requerido');
        }
        if (productoServicio.cantidad <= 0) {
            throw new Error('La cantidad debe ser mayor a cero');
        }
    }
}
