import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { Incidencia, IncidenciaData } from '@/Data/Incidencia';

export class IncidenciaNegocio extends BaseNegocio<Incidencia>{
    public model: string = 'incidencias';
    protected dataService: IncidenciaData;

    constructor() {
        super();
        this.dataService = new IncidenciaData();
    }

    protected validar(incidencia: Incidencia) {
        if (!incidencia.nombre) {
            throw new Error('El nombre es requerido');
        }
        if (!incidencia.descripcion) {
            throw new Error('La descripción es requerida');
        }
    }

}
