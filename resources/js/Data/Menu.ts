import { BaseData } from '@/Data/BaseData';
export interface MenuLink {
    id?: number;
    title: string;
    href: string;
    icon: string;
    isMain: boolean;
    // parentId?: number | null;
    parent_id?: number | null; // Keep for backward compatibility
    isSubmenu?: boolean;
    created_at?: string;
    updated_at?: string;
}

export class MenuData extends BaseData<MenuLink> {
    protected path_api_url: string = 'api.menu';
}
export function getDefaultAttributes() {
    return {
        id: true,
        title: true,
        href: true,
        icon: true,
        isMain: true,
        parent_id: true,
        isSubmenu: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}
export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'title', label: 'Título', isSearch: true },
    { key: 'href', label: 'Enlace', isSearch: true },
    { key: 'icon', label: 'Icono', isSearch: true },
    { key: 'isMain', label: 'Principal', isSearch: true },
    { key: 'parent_id', label: 'Menú Padre', isSearch: true },
    { key: 'isSubmenu', label: 'Es Submenú', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
