import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { Empleado, EmpleadoData } from '@/Data/Empleado';

export class EmpleadoNegocio extends BaseNegocio<Empleado>{
    public model: string = 'empleado';
    protected dataService: EmpleadoData;

    constructor() {
        super();
        this.dataService = new EmpleadoData();
    }

    protected validar(empleado: Empleado) {
        if (!empleado.nombre) {
            throw new Error('El nombre es requerido');
        }
        if (!empleado.ci) {
            throw new Error('El CI es requerido');
        }
        if (!empleado.tipo_documento_id) {
            throw new Error('El tipo de documento es requerido');
        }
        if (!empleado.direccion) {
            throw new Error('La dirección es requerida');
        }
        if (!empleado.telefono) {
            throw new Error('El teléfono es requerido');
        }
        if (!empleado.email) {
            throw new Error('El email es requerido');
        }
    }
}
