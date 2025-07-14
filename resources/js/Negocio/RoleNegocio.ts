import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { Role, RoleData } from '@/Data/Role';

export class RoleNegocio extends BaseNegocio<Role> {
    public model: string = 'roles';
    protected dataService: RoleData;

    constructor() {
        super();
        this.dataService = new RoleData();
    }

    protected validar(role: Role) {
        if (!role.name) {
            throw new Error('El nombre es requerido');
        }
        // Otras validaciones específicas para Role si son necesarias
    }
}
