<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';
import { MenuNegocio } from '@/Negocio/MenuNegocio';
import { ParamsConsulta } from '@/Data/PaginacionLaravel';
import { MenuLink } from '@/Data/Menu';
import { onMounted, reactive } from 'vue';

const negocio: MenuNegocio = new MenuNegocio();

const footerNavItems: NavItem[] = [];

const datas = reactive({
    menus: [] as NavItem[],
    allMenus: [] as MenuLink[],
});

// Function to organize menu items into a hierarchical structure
const organizeMenuItems = (items: MenuLink[]): NavItem[] => {
    // First, find all main menu items (no parent)
    const mainMenus = items.filter(item => item.isMain);

    // Create a map of all items by ID for a quick lookup
    const itemMap = new Map<number, MenuLink>();
    items.forEach(item => {
        if (item.id) {
            itemMap.set(item.id, item);
        }
    });

    // Convert MenuLink to NavItem with children
    const convertToNavItem = (menuItem: MenuLink): NavItem => {
        // Find all children of this menu item
        // Check both parent_id and parentId
        const children = items.filter(item =>
            (item.parent_id === menuItem.id) || (item.parent_id === menuItem.id)
        );

        // Create the NavItem
        const navItem: NavItem = {
            title: menuItem.title,
            href: menuItem.href,
            icon: menuItem.icon,
            // We'll let NavMain handle the active state
        };

        // Add children if any
        if (children.length > 0) {
            navItem.children = children.map(child => convertToNavItem(child));
        }

        return navItem;
    };

    // Convert all main menus to NavItems with their children
    return mainMenus.map(menu => convertToNavItem(menu));
};

onMounted(async () => {
    const params: ParamsConsulta = {
        query: '',
        perPage: 100, // Ensure we get all menu items
    };
    try {
        const response = await negocio.consultar(params);
        console.log(response);
        if (response.isSuccess && response.data) {
            datas.allMenus = response.data;
            datas.menus = organizeMenuItems(datas.allMenus);
        }
    } catch (error) {
        console.error('Error fetching menu items:', error);
    }
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="datas.menus" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
