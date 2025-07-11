import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { EmpleadoEquipoTrabajo, EmpleadoEquipoTrabajoData } from '@/Data/EmpleadoEquipoTrabajo';

export class EmpleadoEquipoTrabajoNegocio extends BaseNegocio<EmpleadoEquipoTrabajo>{
    public model: string = 'empleado_equipo_trabajo';
    protected dataService: EmpleadoEquipoTrabajoData;

    constructor() {
        super();
        this.dataService = new EmpleadoEquipoTrabajoData();
    }

    protected validar(empleadoEquipoTrabajo: EmpleadoEquipoTrabajo): void {
        if (!empleadoEquipoTrabajo.empleado_id) {
            throw new Error('El ID del empleado es requerido');
        }
        if (!empleadoEquipoTrabajo.equipo_trabajo_id) {
            throw new Error('El ID del equipo de trabajo es requerido');
        }
    }
}
