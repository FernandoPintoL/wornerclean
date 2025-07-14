<script setup lang="ts">
import { ref, onMounted, watch, reactive } from 'vue';
import { ContratoIncidencias, ContratoIncidenciasData } from '@/Data/ContratoIncidencias';
import { Incidencias } from '@/Data/Incidencias';
import AlertService from '@/Services/AlertService.js';
import { Plus, Pencil, Trash2, Check, X, AlertCircle } from 'lucide-vue-next';
import Swal from 'sweetalert2';

const props = defineProps({
    contratoId: {
        type: Number,
        required: true
    }
});

const incidenciasData = new ContratoIncidenciasData();
const incidencias = ref<ContratoIncidencias[]>([]);
const tiposIncidencias = ref<Incidencias[]>([]);
const isLoading = ref(false);
const showForm = ref(false);
const isEditing = ref(false);

// Form data
const form = reactive({
    id: null as number | null,
    contrato_id: props.contratoId,
    incidencia_id: null as number | null,
    estado: 'pendiente',
    descripcion: '',
    fecha_solucion: null as string | null
});

// Reset form
const resetForm = () => {
    form.id = null;
    form.contrato_id = props.contratoId;
    form.incidencia_id = null;
    form.estado = 'pendiente';
    form.descripcion = '';
    form.fecha_solucion = null;
    isEditing.value = false;
};

// Load incidencias
const loadIncidencias = async () => {
    try {
        isLoading.value = true;
        const response = await incidenciasData.getIncidenciasByContrato(props.contratoId);
        if (response.isSuccess) {
            incidencias.value = response.data;
        } else {
            AlertService.error('Error al cargar las incidencias');
        }
    } catch (error) {
        console.error('Error loading incidencias:', error);
        AlertService.error('Error al cargar las incidencias');
    } finally {
        isLoading.value = false;
    }
};

// Load tipos de incidencias
const loadTiposIncidencias = async () => {
    try {
        isLoading.value = true;
        // We need to create an instance of the API service for Incidencias
        const response = await fetch('/api/incidencias/query', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                query: '',
                is_query_table: false
            })
        });

        const data = await response.json();
        if (data.isSuccess) {
            tiposIncidencias.value = data.data;
        } else {
            AlertService.error('Error al cargar los tipos de incidencias');
        }
    } catch (error) {
        console.error('Error loading tipos de incidencias:', error);
        AlertService.error('Error al cargar los tipos de incidencias');
    } finally {
        isLoading.value = false;
    }
};

// Add incidencia
const addIncidencia = async () => {
    if (!form.incidencia_id) {
        AlertService.error('Debe seleccionar un tipo de incidencia');
        return;
    }

    if (!form.descripcion) {
        AlertService.error('Debe ingresar una descripción');
        return;
    }

    try {
        isLoading.value = true;
        const data: ContratoIncidencias = {
            contrato_id: props.contratoId,
            incidencia_id: form.incidencia_id,
            estado: form.estado,
            descripcion: form.descripcion,
            fecha_solucion: form.fecha_solucion
        };

        const response = await incidenciasData.addIncidenciaToContrato(data);
        if (response.isSuccess) {
            AlertService.success('Incidencia agregada correctamente');
            resetForm();
            showForm.value = false;
            await loadIncidencias();
        } else {
            AlertService.error(response.message || 'Error al agregar la incidencia');
        }
    } catch (error) {
        console.error('Error adding incidencia:', error);
        AlertService.error('Error al agregar la incidencia');
    } finally {
        isLoading.value = false;
    }
};

// Edit incidencia
const editIncidencia = (incidencia: ContratoIncidencias) => {
    form.id = incidencia.id || null;
    form.contrato_id = incidencia.contrato_id;
    form.incidencia_id = incidencia.incidencia_id;
    form.estado = incidencia.estado || 'pendiente';
    form.descripcion = incidencia.descripcion || '';
    form.fecha_solucion = incidencia.fecha_solucion || null;
    isEditing.value = true;
    showForm.value = true;
};

// Update incidencia
const updateIncidencia = async () => {
    if (!form.incidencia_id) {
        AlertService.error('Debe seleccionar un tipo de incidencia');
        return;
    }

    if (!form.descripcion) {
        AlertService.error('Debe ingresar una descripción');
        return;
    }

    try {
        isLoading.value = true;
        const data: ContratoIncidencias = {
            id: form.id || undefined,
            contrato_id: props.contratoId,
            incidencia_id: form.incidencia_id,
            estado: form.estado,
            descripcion: form.descripcion,
            fecha_solucion: form.fecha_solucion
        };

        const response = await incidenciasData.updateContratoIncidencia(data);
        if (response.isSuccess) {
            AlertService.success('Incidencia actualizada correctamente');
            resetForm();
            showForm.value = false;
            await loadIncidencias();
        } else {
            AlertService.error(response.message || 'Error al actualizar la incidencia');
        }
    } catch (error) {
        console.error('Error updating incidencia:', error);
        AlertService.error('Error al actualizar la incidencia');
    } finally {
        isLoading.value = false;
    }
};

// Delete incidencia
const deleteIncidencia = async (id: number) => {
    try {
        const result = await Swal.fire({
            title: '¿Está seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            isLoading.value = true;
            const response = await incidenciasData.removeIncidenciaFromContrato(id);
            if (response.isSuccess) {
                AlertService.success('Incidencia eliminada correctamente');
                await loadIncidencias();
            } else {
                AlertService.error(response.message || 'Error al eliminar la incidencia');
            }
        }
    } catch (error) {
        console.error('Error deleting incidencia:', error);
        AlertService.error('Error al eliminar la incidencia');
    } finally {
        isLoading.value = false;
    }
};

// Get incidencia name by id
const getIncidenciaName = (id: number): string => {
    const incidencia = tiposIncidencias.value.find(i => i.id === id);
    return incidencia ? incidencia.nombre : 'Desconocido';
};

// Format date
const formatDate = (date: string | null | undefined): string => {
    if (!date) return 'No definida';
    return new Date(date).toLocaleDateString();
};

// Get status badge class
const getStatusBadgeClass = (status: string | undefined): string => {
    switch (status) {
        case 'pendiente':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
        case 'en_proceso':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
        case 'resuelto':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
        case 'cancelado':
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
};

// Watch for changes in contratoId
watch(() => props.contratoId, (newId) => {
    if (newId) {
        form.contrato_id = newId;
        loadIncidencias();
    }
});

// Load data on mount
onMounted(() => {
    loadIncidencias();
    loadTiposIncidencias();
});
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6 transition-all duration-300">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Incidencias del Contrato</h2>
            <button
                @click="() => { showForm = !showForm; if (showForm) resetForm(); }"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200"
            >
                <Plus v-if="!showForm" class="h-5 w-5" />
                <X v-else class="h-5 w-5" />
                <span>{{ showForm ? 'Cancelar' : 'Nueva Incidencia' }}</span>
            </button>
        </div>

        <!-- Form for adding/editing incidencias -->
        <div v-if="showForm" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4 animate-fadeIn">
            <h3 class="text-lg font-medium mb-3 text-gray-800 dark:text-white">
                {{ isEditing ? 'Editar Incidencia' : 'Agregar Nueva Incidencia' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de Incidencia *
                    </label>
                    <select
                        v-model="form.incidencia_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        required
                    >
                        <option :value="null">Seleccione un tipo</option>
                        <option v-for="tipo in tiposIncidencias" :key="tipo.id" :value="tipo.id">
                            {{ tipo.nombre }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Estado
                    </label>
                    <select
                        v-model="form.estado"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    >
                        <option value="pendiente">Pendiente</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="resuelto">Resuelto</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción *
                    </label>
                    <textarea
                        v-model="form.descripcion"
                        rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                        placeholder="Describa la incidencia"
                        required
                    ></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Fecha de Solución
                    </label>
                    <input
                        type="date"
                        v-model="form.fecha_solucion"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                    />
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    @click="isEditing ? updateIncidencia() : addIncidencia()"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200"
                    :disabled="isLoading"
                >
                    <div v-if="isLoading" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></div>
                    <Check v-else class="h-5 w-5" />
                    <span>{{ isEditing ? 'Actualizar' : 'Guardar' }}</span>
                </button>
            </div>
        </div>

        <!-- Loading state -->
        <div v-if="isLoading && !incidencias.length" class="flex justify-center items-center py-8">
            <div class="animate-spin h-8 w-8 border-4 border-indigo-500 border-t-transparent rounded-full"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Cargando incidencias...</span>
        </div>

        <!-- Empty state -->
        <div v-else-if="!incidencias.length" class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <AlertCircle class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay incidencias</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                No se han registrado incidencias para este contrato.
            </p>
            <div class="mt-6">
                <button
                    @click="() => { showForm = true; resetForm(); }"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <Plus class="-ml-1 mr-2 h-5 w-5" />
                    Nueva Incidencia
                </button>
            </div>
        </div>

        <!-- Incidencias list -->
        <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Tipo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Descripción
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Fecha Solución
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <tr v-for="incidencia in incidencias" :key="incidencia.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ incidencia.incidencia ? incidencia.incidencia.nombre : getIncidenciaName(incidencia.incidencia_id) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ incidencia.descripcion }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="['px-2 inline-flex text-xs leading-5 font-semibold rounded-full', getStatusBadgeClass(incidencia.estado)]">
                                {{ incidencia.estado ? incidencia.estado.charAt(0).toUpperCase() + incidencia.estado.slice(1) : 'Pendiente' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ formatDate(incidencia.fecha_solucion) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <button
                                    @click="editIncidencia(incidencia)"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    title="Editar"
                                >
                                    <Pencil class="h-5 w-5" />
                                </button>
                                <button
                                    @click="deleteIncidencia(incidencia.id as number)"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                    title="Eliminar"
                                >
                                    <Trash2 class="h-5 w-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
/* Fade in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out forwards;
}
</style>
