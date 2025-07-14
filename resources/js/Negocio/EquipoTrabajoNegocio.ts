import { EquipoTrabajo, EquipoTrabajoData } from '@/Data/EquipoTrabajo';
import { BaseNegocio } from '@/Negocio/BaseNegocio';

export class EquipoTrabajoNegocio extends BaseNegocio<EquipoTrabajo>{
    public model: string = 'equipo-trabajo';
    protected dataService: EquipoTrabajoData;

    constructor() {
        super();
        this.dataService = new EquipoTrabajoData();
    }

    protected validar(equipoTrabajo: EquipoTrabajo) {
        if (!equipoTrabajo.nombre) {
            throw new Error('El nombre del equipo de trabajo es requerido');
        }
        if (!equipoTrabajo.descripcion) {
            throw new Error('La descripción del equipo de trabajo es requerida');
        }
        // Otras validaciones específicas del equipo de trabajo
    }

    protected prepararEquipoParaCrear(equipoTrabajo: EquipoTrabajo) {
        // Lógica de negocio para preparar el equipo de trabajo antes de crear
        return {
            ...equipoTrabajo,
            created_at: new Date()
        };
    }

    protected prepararEquipoParaActualizar(equipoTrabajo: EquipoTrabajo) {
        // Lógica de negocio para preparar el equipo de trabajo antes de actualizar
        return {
            ...equipoTrabajo,
            updated_at: new Date()
        };
    }
}
