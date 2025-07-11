import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { ContratoIncidencia, ContratoIncidenciaData } from '@/Data/ContratoIncidencia';

export class ContratoIncidenciaNegocio extends BaseNegocio<ContratoIncidencia>{
    public model: string = 'contrato_incidencia';
    protected dataService: ContratoIncidenciaData;

    constructor() {
        super();
        this.dataService = new ContratoIncidenciaData();
    }

    protected validar(incidencia: ContratoIncidencia): void {
        if (!incidencia.contrato_id) {
            throw new Error('El ID del contrato es requerido');
        }
        if (!incidencia.descripcion) {
            throw new Error('La descripción es requerida');
        }
        if (!incidencia.fecha_solucion) {
            throw new Error('La fecha de inicio es requerida');
        }
    }
}
