import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { Servicio, ServicioData } from '@/Data/Servicio';

export class ServicioNegocio extends BaseNegocio<Servicio>{
    public model: string = 'servicio';
    protected dataService: ServicioData;

    constructor() {
        super();
        this.dataService = new ServicioData();
    }

    protected validar(servicio: Servicio) {
        if (!servicio.nombre) {
            throw new Error('El nombre del servicio es requerido');
        }
        if (!servicio.descripcion) {
            throw new Error('La descripción del servicio es requerida');
        }
        if (servicio.precio == null || servicio.precio < 0) {
            throw new Error('El precio del servicio debe ser un número positivo');
        }
        if (!servicio.frecuencia) {
            throw new Error('Carga la frecuencia del servicio es requerida');
        }
        if(!servicio.estado){
            throw new Error('El estado del servicio es requerido');
        }
        // ... otras validaciones específicas del servicio
    }

    protected prepararServicioParaCrear(servicio: Servicio) {
        // Lógica de negocio para preparar el servicio
        return {
            ...servicio,
            created_at: new Date()
        };
    }

    protected prepararServicioParaActualizar(servicio: Servicio) {
        // Lógica de negocio para preparar el servicio
        return {
            ...servicio,
            updated_at: new Date()
        };
    }
}
