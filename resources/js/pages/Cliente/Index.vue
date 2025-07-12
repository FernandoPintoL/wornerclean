<script setup lang="ts">
import AlertInfo from '@/Componentes/Alert-Info.vue';
import Alert from '@/Componentes/Alert.vue';
import ButtonsAdd from '@/Componentes/ButtonsAdd.vue';
import HeaderIndex from '@/Componentes/HeaderIndex.vue';
import Loader from '@/Componentes/Loader.vue';
import Pagination from '@/Componentes/Pagination.vue';
import SearchInput from '@/Componentes/SearchInput.vue';
import TableLayout from '@/Componentes/TableLayout.vue';
import TdTable from '@/Componentes/Td-Table.vue';
import { Cliente, getDefaultAttributes, selectedAttributes } from '@/Data/Cliente';
import { ParamsConsulta } from '@/Data/PaginacionLaravel';
import AppLayout from '@/layouts/AppLayout.vue';
import { ClienteNegocio } from '@/Negocio/ClienteNegocio';
import AlertService from '@/Services/AlertService.js';
import UtilsServices from '@/Services/UtilsServices.js';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { onMounted, reactive, ref } from 'vue';

const negocio = new ClienteNegocio();
const model = negocio.model;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: model.toUpperCase(),
        href: '/' + model,
    },
];

const props = defineProps({
    listado: {
        type: Array as () => Array<Cliente>,
        default: () => [],
    },
    crear: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
    editar: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
    eliminar: {
        type: Boolean,
        default: false, // Valor por defecto puede ser true o false
    },
});

const datas = reactive({
    list: [] as Array<Cliente>,
    isLoad: false,
    siglaError: '',
    detalleError: '',
    currentPage: 1,
    lastPages: 1,
    totalItems: 0,
    totalPages: 1,
    perPage: 5,
});

onMounted(() => {
    fetchList();
});

const query = ref('');
const dateStart = ref('');
const dateEnd = ref('');
// const attributesHeadReactive = attributesHead;
let selectedAttributesReactive = reactive(selectedAttributes);

const fetchList = async (page = 1) => {
    /*if (selectedAttributesReactive['created_at'] || selectedAttributesReactive['updated_at']) {
        if (dateStart.value.length == 0 && dateEnd.value.length == 0) {
            AlertService.error('Fecha de inicio y fecha final deben ser rellenadas');
            return;
        }
        if (dateStart.value > dateEnd.value) {
            AlertService.error('La fecha de inicio no puede ser mayor a la fecha de fin');
            return;
        }
    }*/
    datas.isLoad = true;
    const attributes = Object.keys(selectedAttributesReactive).filter((attr) => selectedAttributesReactive[attr]);
    const params: ParamsConsulta = {
        query: query.value,
        is_query_table: true,
        page: page,
        perPage: datas.perPage,
        attributes: attributes.length > 0 ? attributes : undefined,
        dateStart: dateStart.value,
        dateEnd: dateEnd.value,
    };
    const response = await negocio.consultar(params);
    console.log(response);
    /*if (response === undefined || response.data === undefined) {
        AlertService.error('Error al obtener los datos');
        datas.isLoad = false;
        return;
    }*/
    if (response.isSuccess) {
        datas.list = response.data.data;
        datas.currentPage = response.data.current_page;
        datas.lastPages = response.data.last_page;
        datas.totalPages = response.data.last_page;
        datas.totalItems = response.data.total;
    } else {
        datas.list = [];
    }
    datas.isLoad = false;
};

const queryList = async (consulta: string) => {
    query.value = consulta;
    await fetchList();
};

const destroyMessage = (id: number) => {
    AlertService.confirm('Esta información ya no podrá ser recuperada').then((result) => {
        if (result.isConfirmed) {
            datas.isLoad = true;
            destroyData(id);
            datas.isLoad = false;
        }
    });
};

const destroyData = async (id: number) => {
    const response = await negocio.eliminar(id);
    if (response.isSuccess) {
        AlertService.success('La operación se completo exitosamente!.');
        await queryList('');
    } else {
        AlertService.error(response.message);
    }
};

const handlePerPageChange = (perPage: number) => {
    datas.perPage = perPage;
    fetchList(1);
};

const refreshTable = () => {
    query.value = '';
    datas.perPage = 5;
    datas.currentPage = 1;
    datas.lastPages = 1;
    datas.totalItems = 0;
    datas.totalPages = 1;
    selectedAttributesReactive = reactive({ ...getDefaultAttributes() });
    fetchList(datas.currentPage);
};
</script>

<template>
    <Head :title="UtilsServices.capitalizeFirstLetter(model)" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Main container with responsive padding -->
        <div class="p-4 sm:p-6 lg:p-8 transition-all duration-300">
            <div class="w-full max-w-7xl mx-auto">
                <!-- Header section with shadow and rounded corners -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6 transition-all duration-300 animate-fadeIn">
                    <HeaderIndex :title="model" class="mb-4" />

                    <!-- Search and Add buttons section with improved layout -->
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row gap-4 mb-4">
                            <div class="flex-grow">
                                <SearchInput
                                    :model-path="model"
                                    v-model="query"
                                    @update:query="queryList"
                                    @refresh="refreshTable"
                                    @clear="refreshTable"
                                    class="w-full"
                                />
                            </div>
                            <div class="flex-shrink-0 flex justify-center sm:justify-end">
                                <ButtonsAdd
                                    :model_path="model"
                                    :crear="props.crear"
                                    class="w-full sm:w-auto"
                                />
                            </div>
                        </div>

                        <!-- Quick help text -->
                        <div class="text-xs text-gray-500 dark:text-gray-400 italic text-center sm:text-right">
                            Utilice los botones <span class="font-medium">Limpiar</span> para resetear la búsqueda y <span class="font-medium">Refrescar</span> para actualizar la tabla
                        </div>
                    </div>

                    <!-- Date search section with improved styling -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4 transition-all duration-300">
                        <h3 class="font-semibold text-pretty text-indigo-700 dark:text-indigo-300 mb-3 text-lg">
                            BÚSQUEDA POR FECHAS
                        </h3>

                        <!-- Checkboxes for date search type -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="flex items-center">
                                <label
                                    for="created_at"
                                    class="flex cursor-pointer items-center text-sm font-medium hover:text-indigo-600 transition-colors duration-200"
                                >
                                    <input
                                        type="checkbox"
                                        id="created_at"
                                        class="h-5 w-5 rounded-md border-2 border-gray-300 checked:border-indigo-600 checked:bg-indigo-600 focus:ring-2 focus:ring-indigo-500 transition-colors duration-200"
                                        v-model="selectedAttributesReactive['created_at']"
                                    />
                                    <span class="ml-2">Busqueda fecha de creación</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <label
                                    for="updated_at"
                                    class="flex cursor-pointer items-center text-sm font-medium hover:text-indigo-600 transition-colors duration-200"
                                >
                                    <input
                                        type="checkbox"
                                        id="updated_at"
                                        class="h-5 w-5 rounded-md border-2 border-gray-300 checked:border-indigo-600 checked:bg-indigo-600 focus:ring-2 focus:ring-indigo-500 transition-colors duration-200"
                                        v-model="selectedAttributesReactive['updated_at']"
                                    />
                                    <span class="ml-2">Busqueda fecha de actualización</span>
                                </label>
                            </div>
                        </div>

                        <!-- Date inputs and search button -->
                        <div
                            v-show="selectedAttributesReactive['created_at'] || selectedAttributesReactive['updated_at']"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end"
                        >
                            <div>
                                <label for="start-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Fecha Inicio:
                                </label>
                                <input
                                    type="date"
                                    v-model="dateStart"
                                    id="start-time"
                                    class="w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                />
                            </div>
                            <div>
                                <label for="end-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Fecha Fin:
                                </label>
                                <input
                                    type="date"
                                    v-model="dateEnd"
                                    id="end-time"
                                    class="w-full rounded-lg border border-gray-300 bg-white p-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                />
                            </div>
                            <div>
                                <button
                                    type="button"
                                    @click="fetchList(1)"
                                    class="w-full sm:w-auto flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-white hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition-all duration-200 dark:bg-indigo-700 dark:hover:bg-indigo-800"
                                >
                                    <Search class="h-4 w-4" />
                                    <span>Buscar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div v-if="datas.isLoad" class="flex justify-center items-center bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-lg shadow-md p-8 my-4 min-h-[200px] animate-fadeIn">
                    <div class="text-center">
                        <Loader />
                        <p class="mt-4 text-gray-600 dark:text-gray-400 font-medium">Cargando datos...</p>
                    </div>
                </div>

                <!-- Empty state messages -->
                <div v-if="datas.list.length === 0 && !datas.isLoad" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 my-4 animate-fadeIn-delayed">
                    <div class="text-center py-8">
                        <div class="mb-4">
                            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <Alert v-if="query.length === 0" :message="'No se encontraron registros'" :path="model + '.create'" />
                        <Alert-Info v-else :message="'No se encontraron registros con: ' + query" />
                    </div>
                </div>

                <!-- Data table with improved styling -->
                <div v-else-if="!datas.isLoad" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transition-all duration-300 transform hover:shadow-xl animate-fadeIn-delayed">
                    <div class="overflow-x-auto">
                        <TableLayout :mostrarfoot="datas.list.length > 10">
                            <template #thead>
                                <tr class="bg-gradient-to-r from-indigo-500/10 to-purple-500/10 dark:from-indigo-900/30 dark:to-purple-900/30">
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-300">ID</th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-300">Nombre</th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-300">N. Identificación</th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-300">Direccion</th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-300">Telefono</th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider dark:text-gray-300">Acciones</th>
                                </tr>
                            </template>
                            <template #tbody>
                                <tr
                                    v-for="(item, index) in datas.list"
                                    :key="item.id"
                                    :class="[
                                        'transition-all duration-300 animate-fadeIn',
                                        index % 2 === 0 ? 'bg-white dark:bg-gray-800' : 'bg-indigo-50/30 dark:bg-indigo-900/10',
                                        'hover:bg-indigo-100 dark:hover:bg-indigo-900/20'
                                    ]"
                                >
                                    <td class="p-4 text-sm md:text-base font-medium text-gray-900 dark:text-white" data-label="ID">
                                        {{ item.id }}
                                    </td>
                                    <td class="p-4 text-sm md:text-base font-medium text-gray-900 dark:text-white" data-label="Nombre">
                                        {{ item.nombre }}
                                    </td>
                                    <td class="p-4 text-sm md:text-base font-medium text-gray-900 dark:text-white" data-label="N. Identificación">
                                        {{ item.ci }}
                                    </td>
                                    <td class="p-4 text-sm md:text-base font-medium text-gray-900 dark:text-white" data-label="Dirección">
                                        {{ item.direccion }}
                                    </td>
                                    <td class="p-4 text-sm md:text-base font-medium text-gray-900 dark:text-white" data-label="Teléfono">
                                        {{ item.telefono }}
                                    </td>
                                    <td class="p-0 border-0" data-label="Acciones">
                                        <TdTable
                                            :creado="UtilsServices.fecha(item.created_at)"
                                            :actualizado="UtilsServices.fecha(item.updated_at)"
                                            :model_path="model"
                                            :itemId="item.id ?? ''"
                                            :onDelete="destroyMessage"
                                        ></TdTable>
                                    </td>
                                </tr>
                            </template>
                        </TableLayout>
                    </div>

                    <!-- Pagination with improved styling -->
                    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4">
                        <Pagination
                            :current-page="datas.currentPage"
                            :total-pages="datas.totalPages"
                            :total-items="datas.totalItems"
                            :last-pages="datas.lastPages"
                            :per-page="datas.perPage"
                            @page-changed="fetchList"
                            @per-page-changed="handlePerPageChange"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Add custom transitions and animations */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Fade in animation for table rows */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

.animate-fadeIn-delayed {
    animation: fadeIn 0.5s ease-in-out 0.2s both;
}

/* Custom scrollbar for better visual appeal */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Responsive table adjustments */
@media (max-width: 640px) {
    table {
        display: block;
    }

    thead {
        display: none;
    }

    tbody tr {
        display: block;
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background-color: white;
    }

    .dark tbody tr {
        background-color: #1f2937;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    tbody td {
        display: flex;
        text-align: right;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        align-items: center;
    }

    .dark tbody td {
        border-bottom: 1px solid #374151;
    }

    tbody td:last-child {
        border-bottom: none;
    }

    tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        margin-right: auto;
        color: #4b5563;
        font-size: 0.875rem;
    }

    .dark tbody td::before {
        color: #9ca3af;
    }

    /* Special styling for the actions cell */
    tbody td[data-label="Acciones"] {
        background-color: #f9fafb;
        padding: 1rem;
    }

    .dark tbody td[data-label="Acciones"] {
        background-color: #111827;
    }
}
</style>
