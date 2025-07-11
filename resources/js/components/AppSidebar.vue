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
import { onMounted, reactive } from 'vue';
const negocio: MenuNegocio = new MenuNegocio();
/*const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
];*/

const footerNavItems: NavItem[] = [
    /*{
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },*/
];

const datas = reactive({
    menus: [],
});

onMounted(() => {
    const params: ParamsConsulta = {
        query: '',
    };
    negocio
        .consultar(params)
        .then((response) => {
            console.log("api response: ");
            console.log(response);
            datas.menus = response.data;
        })
        .catch((error) => {
            console.error(error);
        });
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
