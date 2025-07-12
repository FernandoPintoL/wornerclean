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
        if (!empleado.telefono) {
            throw new Error('El teléfono es requerido');
        }
        if (!empleado.puesto) {
            throw new Error('El puesto es requerido');
        }
    }
}
