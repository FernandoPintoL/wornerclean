import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { Usuario, UserData } from '@/Data/User';

export class UserNegocio extends BaseNegocio<Usuario>{
    public model: string = 'usuario';
    protected dataService: UserData;

    constructor() {
        super();
        this.dataService = new UserData();
    }
    protected validar(user: Usuario) {
        if (!user.name) {
            throw new Error('El nombre es requerido');
        }
        if (!user.email) {
            throw new Error('El email es requerido');
        }
        if (!user.usernick) {
            throw new Error('El nickname es requerido');
        }
        if (user.password && user.password.length < 6) {
            throw new Error('La contraseña debe tener al menos 6 caracteres');
        }
    }
}
