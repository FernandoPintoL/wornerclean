<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun, Baby, Users, UserCog } from 'lucide-vue-next';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Claro' },
    { value: 'dark', Icon: Moon, label: 'Oscuro' },
    { value: 'system', Icon: Monitor, label: 'Sistema' },
    { value: 'children', Icon: Baby, label: 'Niños' },
    { value: 'youth', Icon: Users, label: 'Jóvenes' },
    { value: 'adults', Icon: UserCog, label: 'Adultos' },
] as const;
</script>

<template>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 p-2 bg-neutral-100 rounded-lg dark:bg-neutral-800">
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-3 py-2 transition-colors',
                appearance === value
                    ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                    : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
            ]"
        >
            <component :is="Icon" class="h-4 w-4" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>
</template>
