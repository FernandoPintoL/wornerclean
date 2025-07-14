<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';

const props = defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const openSubmenus = ref<Record<string, boolean>>({});

// Function to check and open parent menus of active routes
const checkAndOpenActiveSubmenus = () => {
    const checkItemAndParents = (items: NavItem[]) => {
        items.forEach(item => {
            if (item.children && item.children.length > 0) {
                // Check if this item or any of its children are active
                if (isActiveRoute(item)) {
                    // Open this submenu
                    openSubmenus.value[item.title] = true;
                }

                // Recursively check children
                checkItemAndParents(item.children);
            }
        });
    };

    checkItemAndParents(props.items);
};

// Initialize active submenus when component mounts
onMounted(() => {
    checkAndOpenActiveSubmenus();
});

// Watch for changes in the items prop to update active submenus
watch(() => props.items, () => {
    checkAndOpenActiveSubmenus();
}, { deep: true });

// Toggle submenu open/closed state
const toggleSubmenu = (itemTitle: string) => {
    openSubmenus.value[itemTitle] = !openSubmenus.value[itemTitle];
};

// Check if a route is active or one of its children is active
const isActiveRoute = (item: NavItem): boolean => {
    try {
        // Check if the current URL matches this route
        const routeUrl = route(item.href);
        if (routeUrl === page.url) {
            return true;
        }

        // Check if any child route is active
        if (item.children && item.children.length > 0) {
            return item.children.some(child => isActiveRoute(child));
        }

        return false;
    } catch (error) {
        // If route() throws an error (invalid route name), just return false
        console.error(`Error checking route for ${item.title}:`, error);
        return false;
    }
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Worner Clean</SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <!-- Elemento de menú con submenu -->
                <SidebarMenuItem v-if="item.children && item.children.length > 0">
                    <SidebarMenuButton
                        :is-active="isActiveRoute(item)"
                        :tooltip="item.title"
                        @click="toggleSubmenu(item.title)"
                    >
                        <i :class="item.icon"></i>
                        <span>{{ item.title }}</span>
                        <!-- Dropdown indicator -->
                        <i
                            class="ml-auto text-xs"
                            :class="openSubmenus[item.title] ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'"
                        ></i>
                    </SidebarMenuButton>

                    <!-- Submenu items -->
                    <SidebarMenuSub v-show="openSubmenus[item.title]">
                        <SidebarMenuItem v-for="child in item.children" :key="child.title">
                            <SidebarMenuButton as-child :is-active="isActiveRoute(child)" :tooltip="child.title">
                                <Link :href="route(child.href)" class="pl-4">
                                    <i :class="child.icon || 'fa-solid fa-circle-dot'" class="text-xs"></i>
                                    <span>{{ child.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenuSub>
                </SidebarMenuItem>

                <!-- Elemento de menú normal sin submenu -->
                <SidebarMenuItem v-else>
                    <SidebarMenuButton as-child :is-active="isActiveRoute(item)" :tooltip="item.title">
                        <Link :href="route(item.href)">
                            <i :class="item.icon"></i>
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>

<style scoped>
/* Add some animation for submenu expansion */
.sidebar-menu-sub {
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
}
</style>
