import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { Contrato, ContratoData } from '@/Data/Contrato';

export class ContratoNegocio extends BaseNegocio<Contrato>{
    public model: string = 'contrato';
    protected dataService: ContratoData; // Aquí deberías definir el tipo de tu servicio de datos

    constructor() {
        super();
        this.dataService = new ContratoData(); // Inicializa tu servicio de datos aquí
    }

    protected validar(contrato: Contrato): void {
        if (!contrato.cliente_id) {
            throw new Error('El ID del cliente es requerido');
        }
        if (!contrato.fecha_inicio) {
            throw new Error('La fecha de inicio es requerida');
        }
        if (!contrato.fecha_fin) {
            throw new Error('La fecha de fin es requerida');
        }
    }
}
