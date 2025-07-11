import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { Producto, ProductoData } from '@/Data/Producto';

export class ProductoNegocio extends BaseNegocio<Producto>{
    public model: string = 'producto';
    protected dataService: ProductoData; // Aquí deberías definir el tipo de dataService específico para Producto

    constructor() {
        super();
        // Inicializa dataService con la implementación específica para Producto
        this.dataService = new ProductoData(); // Reemplaza con la instancia correcta de tu servicio de datos
    }

    protected validar(producto: Producto) {
        if (!producto.nombre) {
            throw new Error('El nombre es requerido');
        }
        if (!producto.precio) {
            throw new Error('El precio es requerido');
        }
        // ... otras validaciones específicas para Producto
    }
}
