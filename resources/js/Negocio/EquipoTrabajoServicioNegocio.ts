import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { EquipoTrabajoServicio, EquipoTrabajoServicioData } from '@/Data/EquipoTrabajoServicio';

export class EquipoTrabajoServicioNegocio extends BaseNegocio<EquipoTrabajoServicio>{
    public model: string = 'equipo_trabajo_servicio';
    protected dataService: EquipoTrabajoServicioData;

    constructor() {
        super();
        this.dataService = new EquipoTrabajoServicioData();
    }

    protected validar(equipoTrabajoServicio: EquipoTrabajoServicio): void {
        if (!equipoTrabajoServicio.equipo_trabajo_id) {
            throw new Error('El ID del equipo de trabajo es requerido');
        }
        if (!equipoTrabajoServicio.servicio_id) {
            throw new Error('El ID del servicio es requerido');
        }
    }

}
