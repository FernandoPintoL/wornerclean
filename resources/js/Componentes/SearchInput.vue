<script setup lang="ts">
import { ref } from 'vue';
import { RefreshCcw, Search, X, RotateCcw } from 'lucide-vue-next';

const props = defineProps({
    modelPath: {
        type: String,
        required: true
    }
});

const emits = defineEmits(['update:query', 'refresh', 'clear']);

const query = ref('');
const isRefreshing = ref(false);

const onSearchQuery = (e : any) => {
    const target = e.target as HTMLSelectElement;
    emits('update:query', target.value);
};

const refreshTable = () => {
    // Add animation effect
    isRefreshing.value = true;
    setTimeout(() => {
        isRefreshing.value = false;
    }, 1000);

    query.value = '';
    emits('refresh');
};

const clearSearch = () => {
    query.value = '';
    emits('update:query', '');
    emits('clear');
};
</script>

<template>
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="w-full">
            <label :for="'search-'+props.modelPath" class="sr-only">Buscar</label>
            <div class="relative w-full">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <Search class="h-5 w-5 text-indigo-500 dark:text-indigo-400" />
                </div>
                <input
                    type="search"
                    v-model="query"
                    @input="onSearchQuery"
                    name="search"
                    :id="'search-'+props.modelPath"
                    class="ps-10 pe-10 py-2.5 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full transition-all duration-200 hover:shadow-md dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                    :placeholder="'Buscar por '+props.modelPath+'s'" />
                <button
                    v-if="query.length > 0"
                    type="button"
                    @click="clearSearch"
                    class="absolute inset-y-0 end-0 flex items-center pe-3 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors duration-200"
                >
                    <X class="h-4 w-4" />
                </button>
            </div>
        </div>
        <div class="flex gap-2 w-full sm:w-auto justify-end">
            <!-- Clear button to reset search fields and reload tables -->
            <button
                type="button"
                id="clear-button"
                @click="clearSearch"
                class="flex items-center justify-center px-3 py-2 text-white bg-gradient-to-r from-purple-500 to-indigo-500 hover:from-purple-600 hover:to-indigo-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-300 dark:focus:ring-purple-800"
                title="Limpiar búsqueda"
            >
                <RotateCcw class="h-5 w-5" />
                <span class="hidden sm:inline-block ml-1">Limpiar</span>
            </button>

            <!-- Refresh button with improved styling -->
            <button
                type="button"
                id="refresh-button"
                @click="refreshTable"
                class="flex items-center justify-center px-3 py-2 text-white bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800"
                title="Refrescar tabla"
            >
                <RefreshCcw
                    class="h-5 w-5"
                    :class="{ 'animate-spin': isRefreshing }"
                />
                <span class="hidden sm:inline-block ml-1">Refrescar</span>
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Add animation for input focus */
input:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

/* Add animation for button hover */
button {
    position: relative;
    overflow: hidden;
}

button::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
}

button:hover::after {
    animation: ripple 1s ease-out;
}

@keyframes ripple {
    0% {
        transform: scale(0, 0);
        opacity: 0.5;
    }
    100% {
        transform: scale(20, 20);
        opacity: 0;
    }
}
</style>
