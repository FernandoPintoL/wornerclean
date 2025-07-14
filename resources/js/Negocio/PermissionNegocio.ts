import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { Permission, PermissionData } from '@/Data/Permission';

export class PermissionNegocio extends BaseNegocio<Permission> {
    public model: string = 'permissions';
    protected dataService: PermissionData;

    constructor() {
        super();
        this.dataService = new PermissionData();
    }

    protected validar(permission: Permission) {
        if (!permission.name) {
            throw new Error('El nombre es requerido');
        }
        // Otras validaciones específicas para Permission si son necesarias
    }
}
