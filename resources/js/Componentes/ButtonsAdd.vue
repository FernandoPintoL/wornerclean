<script setup lang="ts">
import {route} from "ziggy-js";
import { CirclePlus } from 'lucide-vue-next';

const props = defineProps({
    model_path: {
        type: String,
        required: true
    },
    crear: {
        type: Boolean,
        default: true
    }
});

</script>

<template>
    <a v-if="props.crear" :href="route(model_path+'.create')"
          :id="'create-'+props.model_path"
          class="flex justify-center items-center gap-2 px-4 py-2 text-white bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 dark:from-emerald-600 dark:to-teal-600 dark:hover:from-emerald-700 dark:hover:to-teal-700 focus:outline-none dark:focus:ring-emerald-800">
        <span class="hidden sm:block">Nuevo {{ props.model_path }}</span>
        <CirclePlus class="h-5 w-5 text-white"/>
    </a>
</template>

<style scoped>
/* Add animation for hover effect */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
}

a:hover {
    animation: pulse 1.5s infinite;
}
</style>
