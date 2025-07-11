import { Cliente, ClienteData } from '@/Data/Cliente';
import { BaseNegocio } from '@/Negocio/BaseNegocio';

export class ClienteNegocio extends BaseNegocio<Cliente>{
    public model: string = 'cliente';
    protected dataService: ClienteData;
    constructor() {
        super();
        this.dataService = new ClienteData();
    }
    protected validar(cliente: Cliente) {
        if (!cliente.nombre) {
            throw new Error('El nombre es requerido');
        }
        if (!cliente.ci) {
            throw new Error('El CI es requerido');
        }
        if (!cliente.tipo_documento_id) {
            throw new Error('El tipo de documento es requerido');
        }
        if (!cliente.direccion) {
            throw new Error('La dirección es requerida');
        }
        if (!cliente.telefono) {
            throw new Error('El teléfono es requerido');
        }
        if (!cliente.email) {
            throw new Error('El email es requerido');
        }
    }
}
