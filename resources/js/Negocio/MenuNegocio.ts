import { MenuLink, MenuData } from '@/Data/Menu';
import { BaseNegocio } from '@/Negocio/BaseNegocio';

export class MenuNegocio extends BaseNegocio<MenuLink>{
    public model: string = 'menu';
    protected dataService: MenuData;
    constructor() {
        super();
        this.dataService = new MenuData();
    }
    protected validar(menu: MenuLink) {
        if (!menu.title) {
            throw new Error('El título es requerido');
        }
        if (!menu.href) {
            throw new Error('La URL es requerida');
        }

        // Validar que un menú principal no puede ser submenu
        if (menu.isMain && menu.isSubmenu) {
            throw new Error('Un menú principal no puede ser submenu');
        }

        // Validar que un submenu debe tener un padre
        // Comprobar tanto parentId como parent_id
        if (menu.isSubmenu && !menu.parent_id && !menu.parent_id) {
            throw new Error('Un submenu debe tener un menú padre');
        }

        // Si parentId está definido pero parent_id no, asignar parentId a parent_id
        if (menu.parent_id && !menu.parent_id) {
            menu.parent_id = menu.parent_id;
        }
        // Si parent_id está definido pero parentId no, asignar parent_id a parentId
        else if (!menu.parent_id && menu.parent_id) {
            menu.parent_id = menu.parent_id;
        }

        // ... otras validaciones
    }
    protected prepararMenuParaCrear(menu: MenuLink) {
        // Lógica de negocio para preparar el menú
        const menuPreparado = {
            ...menu,
            created_at: new Date()
        };

        // Si es un submenu, asegurarse de que isSubmenu sea true
        if (menu.parent_id) {
            menuPreparado.isSubmenu = true;
            // Asegurar que parent_id esté sincronizado con parentId
            menuPreparado.parent_id = menu.parent_id;
        }

        // Si es un menú principal, asegurarse de que no tenga parentId
        if (menu.isMain) {
            menuPreparado.parent_id = null;
            menuPreparado.parent_id = null;
            menuPreparado.isSubmenu = false;
        }

        return menuPreparado;
    }

    protected prepararMenuParaActualizar(menu: MenuLink) {
        // Lógica de negocio para preparar el menú
        const menuPreparado = {
            ...menu,
            updated_at: new Date()
        };

        // Si es un submenu, asegurarse de que isSubmenu sea true
        if (menu.parent_id) {
            menuPreparado.isSubmenu = true;
            // Asegurar que parent_id esté sincronizado con parentId
            menuPreparado.parent_id = menu.parent_id;
        }

        // Si es un menú principal, asegurarse de que no tenga parentId
        if (menu.isMain) {
            menuPreparado.parent_id = null;
            menuPreparado.parent_id = null;
            menuPreparado.isSubmenu = false;
        }

        return menuPreparado;
    }

    // Método para obtener todos los menús principales
    public async obtenerMenusPrincipales() {
        try {
            const response = await this.dataService.obtenerDatos('api.menu.main-menus');
            if (response.isSuccess) {
                return response.data;
            }
            return [];
        } catch (error) {
            console.error('Error al obtener menús principales:', error);
            return [];
        }
    }
}
