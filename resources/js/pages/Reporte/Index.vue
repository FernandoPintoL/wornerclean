<template>
    <AppLayout title="Reportes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Reportes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- Tabs para los diferentes tipos de reportes -->
                        <div class="mb-6">
                            <div class="border-b border-gray-200 dark:border-gray-700">
                                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                                    <li class="mr-2" v-for="(tab, index) in tabs" :key="index">
                                        <a href="#" :class="[
                                            'inline-block p-4 rounded-t-lg',
                                            activeTab === tab.id
                                                ? 'text-blue-600 border-b-2 border-blue-600 dark:text-blue-500 dark:border-blue-500'
                                                : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300'
                                        ]" @click.prevent="activeTab = tab.id">
                                            {{ tab.name }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Filtros comunes para todos los reportes -->
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="fechaInicio"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Fecha Inicio
                                </label>
                                <input type="date" id="fechaInicio" v-model="filters.fechaInicio"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>
                            <div>
                                <label for="fechaFin"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Fecha Fin
                                </label>
                                <input type="date" id="fechaFin" v-model="filters.fechaFin"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            </div>

                            <!-- Filtros específicos para contratos -->
                            <div v-if="activeTab === 'contratos'">
                                <label for="estado"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Estado
                                </label>
                                <select id="estado" v-model="filters.estado"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Todos</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="finalizado">Finalizado</option>
                                </select>
                            </div>
                            <div v-if="activeTab === 'contratos'">
                                <label for="estadoPago"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Estado de Pago
                                </label>
                                <select id="estadoPago" v-model="filters.estadoPago"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Todos</option>
                                    <option value="pagado">Pagado</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="parcial">Parcial</option>
                                </select>
                            </div>

                            <!-- Filtros específicos para incidencias -->
                            <div v-if="activeTab === 'incidencias'">
                                <label for="estadoIncidencia"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Estado
                                </label>
                                <select id="estadoIncidencia" v-model="filters.estado"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Todos</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="resuelta">Resuelta</option>
                                    <option value="cerrada">Cerrada</option>
                                </select>
                            </div>

                            <!-- Filtros específicos para productos -->
                            <div v-if="activeTab === 'productos'">
                                <label for="categoria"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Categoría
                                </label>
                                <input type="text" id="categoria" v-model="filters.categoria"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="Categoría">
                            </div>

                            <div class="flex items-end">
                                <button @click="generateReport"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Generar Reporte
                                </button>
                            </div>
                        </div>

                        <!-- Sección de estadísticas -->
                        <div v-if="reportData.estadisticas" class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Estadísticas</h3>

                            <!-- Estadísticas para contratos -->
                            <div v-if="activeTab === 'contratos'" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Contratos</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.total }}</p>
                                </div>
                                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Contratos Activos</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.activos }}</p>
                                </div>
                                <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Contratos Pendientes de Pago</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.pendientes }}</p>
                                </div>
                                <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Monto Total</p>
                                    <p class="text-2xl font-bold">${{ reportData.estadisticas.montoTotal.toFixed(2) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Estadísticas para incidencias -->
                            <div v-if="activeTab === 'incidencias'" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Incidencias</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.total }}</p>
                                </div>
                                <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Pendientes</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.pendientes }}</p>
                                </div>
                                <div class="bg-orange-100 dark:bg-orange-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">En Proceso</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.enProceso }}</p>
                                </div>
                                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Resueltas</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.resueltas }}</p>
                                </div>
                            </div>

                            <!-- Estadísticas para productos -->
                            <div v-if="activeTab === 'productos'" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Productos</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.total }}</p>
                                </div>
                                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Stock Total</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.totalStock }}</p>
                                </div>
                                <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Valor Total</p>
                                    <p class="text-2xl font-bold">${{ reportData.estadisticas.valorTotal.toFixed(2) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Estadísticas para equipos de trabajo -->
                            <div v-if="activeTab === 'equipos'" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Equipos</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.total }}</p>
                                </div>
                                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Empleados</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.totalEmpleados }}</p>
                                </div>
                                <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Servicios</p>
                                    <p class="text-2xl font-bold">{{ reportData.estadisticas.totalServicios }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de resultados -->
                        <div v-if="reportData.items && reportData.items.length > 0" class="overflow-x-auto">
                            <!-- Tabla para contratos -->
                            <table v-if="activeTab === 'contratos'"
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ID</th>
                                        <th scope="col" class="px-6 py-3">Cliente</th>
                                        <th scope="col" class="px-6 py-3">Descripción</th>
                                        <th scope="col" class="px-6 py-3">Monto</th>
                                        <th scope="col" class="px-6 py-3">Estado</th>
                                        <th scope="col" class="px-6 py-3">Estado Pago</th>
                                        <th scope="col" class="px-6 py-3">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in reportData.items" :key="item.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ item.id }}</td>
                                        <td class="px-6 py-4">{{ item.cliente ? item.cliente.nombre : 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ item.descripcion }}</td>
                                        <td class="px-6 py-4">${{ item.monto }}</td>
                                        <td class="px-6 py-4">
                                            <span :class="[
                                                'px-2 py-1 rounded-full text-xs',
                                                item.estado === 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' :
                                                    item.estado === 'inactivo' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' :
                                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                            ]">
                                                {{ item.estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span :class="[
                                                'px-2 py-1 rounded-full text-xs',
                                                item.estado_pago === 'pagado' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' :
                                                    item.estado_pago === 'pendiente' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' :
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                            ]">
                                                {{ item.estado_pago }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ formatDate(item.created_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Tabla para incidencias -->
                            <table v-if="activeTab === 'incidencias'"
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ID</th>
                                        <th scope="col" class="px-6 py-3">Contrato</th>
                                        <th scope="col" class="px-6 py-3">Descripción</th>
                                        <th scope="col" class="px-6 py-3">Estado</th>
                                        <th scope="col" class="px-6 py-3">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in reportData.items" :key="item.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ item.id }}</td>
                                        <td class="px-6 py-4">{{ item.contrato ? item.contrato.descripcion : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">{{ item.descripcion }}</td>
                                        <td class="px-6 py-4">
                                            <span :class="[
                                                'px-2 py-1 rounded-full text-xs',
                                                item.estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' :
                                                    item.estado === 'en_proceso' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' :
                                                        item.estado === 'resuelta' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' :
                                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                            ]">
                                                {{ item.estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ formatDate(item.created_at) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Tabla para productos -->
                            <table v-if="activeTab === 'productos'"
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ID</th>
                                        <th scope="col" class="px-6 py-3">Nombre</th>
                                        <th scope="col" class="px-6 py-3">Descripción</th>
                                        <th scope="col" class="px-6 py-3">Categoría</th>
                                        <th scope="col" class="px-6 py-3">Precio</th>
                                        <th scope="col" class="px-6 py-3">Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in reportData.items" :key="item.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ item.id }}</td>
                                        <td class="px-6 py-4">{{ item.nombre }}</td>
                                        <td class="px-6 py-4">{{ item.descripcion }}</td>
                                        <td class="px-6 py-4">{{ item.categoria }}</td>
                                        <td class="px-6 py-4">${{ item.precio }}</td>
                                        <td class="px-6 py-4">{{ item.stock }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Tabla para equipos de trabajo -->
                            <table v-if="activeTab === 'equipos'"
                                class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ID</th>
                                        <th scope="col" class="px-6 py-3">Nombre</th>
                                        <th scope="col" class="px-6 py-3">Descripción</th>
                                        <th scope="col" class="px-6 py-3">Empleados</th>
                                        <th scope="col" class="px-6 py-3">Servicios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in reportData.items" :key="item.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ item.id || 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ item.nombre || 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ item.descripcion || 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ getEmpleadosCount(item) }}</td>
                                        <td class="px-6 py-4">{{ getServiciosCount(item) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mensaje cuando no hay datos -->
                        <div v-else-if="reportGenerated" class="text-center py-4">
                            <p class="text-gray-500 dark:text-gray-400">No se encontraron datos para los filtros
                                seleccionados.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import axios from 'axios';

const activeTab = ref('contratos');
const reportGenerated = ref(false);
const isLoading = ref(false);

const tabs = [
    { id: 'contratos', name: 'Contratos' },
    { id: 'incidencias', name: 'Incidencias' },
    { id: 'productos', name: 'Productos' },
    { id: 'equipos', name: 'Equipos de Trabajo' }
];

const filters = reactive({
    fechaInicio: '',
    fechaFin: '',
    estado: '',
    estadoPago: '',
    categoria: '',
    contratoId: ''
});

const reportData = reactive<{
    items: any[];
    estadisticas: any;
}>({
    items: [],
    estadisticas: null
});

const generateReport = async () => {
    isLoading.value = true;
    reportGenerated.value = true;

    try {
        let endpoint = '';

        switch (activeTab.value) {
            case 'contratos':
                endpoint = '/api/reportes/contratos';
                break;
            case 'incidencias':
                endpoint = '/api/reportes/incidencias';
                break;
            case 'productos':
                endpoint = '/api/reportes/productos';
                break;
            case 'equipos':
                endpoint = '/api/reportes/equipos-trabajo';
                break;
        }

        const response = await axios.post(endpoint, filters);

        if (response.data.isSuccess) {
            if (activeTab.value === 'contratos') {
                reportData.items = response.data.data.contratos;
                reportData.estadisticas = response.data.data.estadisticas;
            } else if (activeTab.value === 'incidencias') {
                reportData.items = response.data.data.incidencias;
                reportData.estadisticas = response.data.data.estadisticas;
            } else if (activeTab.value === 'productos') {
                reportData.items = response.data.data.productos;
                reportData.estadisticas = response.data.data.estadisticas;
            } else if (activeTab.value === 'equipos') {
                reportData.items = response.data.data.equipos;
                reportData.estadisticas = response.data.data.estadisticas;
            }
        } else {
            console.error('Error al generar el reporte:', response.data.message);
            reportData.items = [];
            reportData.estadisticas = null;
        }
    } catch (error) {
        console.error('Error al generar el reporte:', error);
        reportData.items = [];
        reportData.estadisticas = null;
    } finally {
        isLoading.value = false;
    }
};

const formatDate = (dateString: string) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString();
};

const getEmpleadosCount = (item: any) => {
    if (!item || !item.empleados) return 0;
    return Array.isArray(item.empleados) ? item.empleados.length : 0;
};

const getServiciosCount = (item: any) => {
    if (!item || !item.servicios) return 0;
    return Array.isArray(item.servicios) ? item.servicios.length : 0;
};
</script>
