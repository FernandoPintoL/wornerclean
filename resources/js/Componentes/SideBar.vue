<script setup lang="ts">
//cargar menus al montado
import {onMounted, reactive} from "vue";
import { MenuNegocio } from '@/Negocio/MenuNegocio';
import {route} from "ziggy-js";
import {Link, router} from "@inertiajs/vue3";
import { ParamsConsulta } from '@/Data/PaginacionLaravel';
import { MenuLink } from '@/Data/Menu';

const negocio = new MenuNegocio();

const data = reactive<{
    menus: MenuLink[]
}>({
    menus: []
});

onMounted(() => {
    console.log('cargando pagina menu')
    //console.log('mounted');
    try{
        const params: ParamsConsulta = {
            query: '',
        };
        negocio.consultar(params).then(response => {
            data.menus = response.data;
        }).catch(error => {
            console.error(error);
        });
    } catch (error) {
        console.error('Error al cargar los menús:', error);
    }
});

const logout = () => {
    router.post(route('logout'))
}

</script>

<template>
    <aside id="sidebar"
           class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width"
           aria-label="Sidebar">
        <div
            class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
                <div
                    class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <ul class="pb-2 space-y-2">
                        <li>
                            <Link :href="route('dashboard')"
                                  class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                                <svg
                                    class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                                </svg>
                                <span class="ml-3">Dashboard</span>
                            </Link>
                        </li>
                        <li v-for="menu in data.menus" :key="menu.id">
                            <Link :href="route(menu.href)"
                                  class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700">
                                <i :class="menu.icon"></i>
                                <span class="ml-3">{{ menu.title }}</span>
                            </Link>

                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="divide-y divide-gray-100 dark:divide-gray-700 absolute bottom-0 left-0 justify-center hidden w-full p-4 space-x-4 bg-white lg:flex dark:bg-gray-800">
                <a href="#" data-tooltip-target="tooltip-settings" class="inline-flex
                justify-center p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100
                dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                              clip-rule="evenodd"></path>
                    </svg>
                </a>
                <div id="tooltip-settings" role="tooltip"
                     class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Settings page
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                <a href="#" @click="logout"
                   class="inline-flex justify-center p-2 rounded cursor-pointer text-gray-500 hover:text-gray-900 hover:bg-gray-100
                dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg
                        class="text-gray-500 w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </a>
            </div>
        </div>
    </aside>

    <div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>
</template>

<style scoped>

</style>
