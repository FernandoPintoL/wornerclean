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
        if (!cliente.direccion) {
            throw new Error('La dirección es requerida');
        }
        if (!cliente.telefono) {
            throw new Error('El teléfono es requerido');
        }
    }

    public validateField(field: string, value: string | number | Event): { value: any; error: string } {
        switch (field) {
            case 'nombre':
                return this.validateName(value as Event);
            case 'descripcion':
                return this.validateDescripcion(value as Event);
            case 'telefono':
                return this.validateTelefono(value as string | number);
            case 'tipo_cliente':
                return this.validateTipoCliente(value as string);
            default:
                return { value: null, error: 'Campo no reconocido' };
        }
    }

    private validateName(e: Event): { value: string; error: string } {
        const target = e.target as HTMLInputElement;
        if (target.value.length === 0) {
            return { value: '', error: 'El nombre es requerido.' };
        }
        if (target.value.length < 2) {
            return { value: '', error: 'El nombre debe tener al menos 2 caracteres.' };
        }
        return { value: target.value, error: '' };
    }

    private validateDescripcion(e: Event): { value: string; error: string } {
        const target = e.target as HTMLTextAreaElement;
        if (target.value.length === 0) {
            return { value: '', error: '' };
        }
        return { value: target.value, error: '' };
    }

    private validateTelefono(value: string | number): { value: string; error: string } {
        if (typeof value !== 'string' && typeof value !== 'number') {
            return { value: '', error: 'El teléfono debe ser un número válido.' };
        }
        const telefono = String(value).trim();
        if (telefono.length === 0) {
            return { value: '', error: 'El teléfono es requerido.' };
        }
        if (!/^\d+$/.test(telefono)) {
            return { value: '', error: 'El teléfono debe contener solo números.' };
        }
        return { value: telefono, error: '' };
    }
    private validateTipoCliente(value: string): { value: string; error: string } {
        if (!value) {
            return { value: '', error: 'El tipo de cliente es requerido.' };
        }
        const tiposValidos = ['Regular', 'Premium', 'VIP'];
        if (!tiposValidos.includes(value)) {
            return { value: '', error: `El tipo de cliente debe ser uno de los siguientes: ${tiposValidos.join(', ')}` };
        }
        return { value, error: '' };
    }
}
