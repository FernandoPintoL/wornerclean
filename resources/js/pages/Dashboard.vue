<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

// Define props to receive dashboard data from the controller
const props = defineProps({
    dashboardData: {
        type: Object,
        required: true,
        default: () => ({
            contratos: {},
            incidencias: {},
            productos: {},
            equipos: {}
        })
    }
});

// Format currency values
const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB'
    }).format(value || 0);
};

// Computed properties for card colors
const contratoCardColor = computed(() => {
    const activos = props.dashboardData.contratos.activos || 0;
    const total = props.dashboardData.contratos.total || 1;
    const ratio = activos / total;

    if (ratio >= 0.7) return 'bg-green-100 dark:bg-green-800';
    if (ratio >= 0.4) return 'bg-yellow-100 dark:bg-yellow-800';
    return 'bg-red-100 dark:bg-red-800';
});

const incidenciaCardColor = computed(() => {
    const pendientes = props.dashboardData.incidencias.pendientes || 0;
    const total = props.dashboardData.incidencias.total || 1;
    const ratio = pendientes / total;

    if (ratio <= 0.3) return 'bg-green-100 dark:bg-green-800';
    if (ratio <= 0.6) return 'bg-yellow-100 dark:bg-yellow-800';
    return 'bg-red-100 dark:bg-red-800';
});

const productoCardColor = computed(() => {
    const bajoStock = props.dashboardData.productos.bajoStock || 0;
    const total = props.dashboardData.productos.total || 1;
    const ratio = bajoStock / total;

    if (ratio <= 0.1) return 'bg-green-100 dark:bg-green-800';
    if (ratio <= 0.3) return 'bg-yellow-100 dark:bg-yellow-800';
    return 'bg-red-100 dark:bg-red-800';
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <!-- Tarjetas de estadísticas -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Tarjeta de Contratos -->
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 shadow-sm"
                     :class="contratoCardColor">
                    <div class="flex flex-col h-full">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Contratos</h3>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Total</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ dashboardData.contratos.total || 0 }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Activos</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ dashboardData.contratos.activos || 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Monto Total</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ formatCurrency(dashboardData.contratos.montoTotal) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Incidencias -->
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 shadow-sm"
                     :class="incidenciaCardColor">
                    <div class="flex flex-col h-full">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Incidencias</h3>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Total</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ dashboardData.incidencias.total || 0 }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Pendientes</p>
                                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ dashboardData.incidencias.pendientes || 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-sm text-gray-600 dark:text-gray-300">En Proceso</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ dashboardData.incidencias.enProceso || 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Productos o Equipos (dependiendo de permisos) -->
                <div v-if="Object.keys(dashboardData.productos).length > 0"
                     class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 shadow-sm"
                     :class="productoCardColor">
                    <div class="flex flex-col h-full">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Productos</h3>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Total</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ dashboardData.productos.total || 0 }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Bajo Stock</p>
                                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ dashboardData.productos.bajoStock || 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Valor Total</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ formatCurrency(dashboardData.productos.valorTotal) }}</p>
                        </div>
                    </div>
                </div>

                <div v-else-if="Object.keys(dashboardData.equipos).length > 0"
                     class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 shadow-sm bg-blue-100 dark:bg-blue-800">
                    <div class="flex flex-col h-full">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Equipos de Trabajo</h3>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Total Equipos</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ dashboardData.equipos.total || 0 }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-300">Empleados</p>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ dashboardData.equipos.totalEmpleados || 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <p class="text-sm text-gray-600 dark:text-gray-300">Servicios Asignados</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ dashboardData.equipos.totalServicios || 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de información detallada -->
            <div class="relative flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border p-4 shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Resumen de Actividad</h2>

                <!-- Contratos -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Estado de Contratos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Activos</p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ dashboardData.contratos.activos || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Inactivos</p>
                            <p class="text-xl font-bold text-gray-600 dark:text-gray-400">{{ dashboardData.contratos.inactivos || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Finalizados</p>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ dashboardData.contratos.finalizados || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pagos Pendientes</p>
                            <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ dashboardData.contratos.pendientes || 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Incidencias -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Estado de Incidencias</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pendientes</p>
                            <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ dashboardData.incidencias.pendientes || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">En Proceso</p>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ dashboardData.incidencias.enProceso || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Resueltas</p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ dashboardData.incidencias.resueltas || 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Productos o Equipos (dependiendo de permisos) -->
                <div v-if="Object.keys(dashboardData.productos).length > 0" class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Inventario de Productos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Productos</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ dashboardData.productos.total || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Stock</p>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ dashboardData.productos.totalStock || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Productos Bajo Stock</p>
                            <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ dashboardData.productos.bajoStock || 0 }}</p>
                        </div>
                    </div>
                </div>

                <div v-if="Object.keys(dashboardData.equipos).length > 0" class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Equipos de Trabajo</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Equipos</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ dashboardData.equipos.total || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Empleados</p>
                            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ dashboardData.equipos.totalEmpleados || 0 }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Servicios</p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ dashboardData.equipos.totalServicios || 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Mensaje informativo -->
                <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <span class="font-bold">Información:</span> Este dashboard muestra un resumen de la actividad del sistema.
                        Para ver informes detallados, visite la sección de Reportes.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
