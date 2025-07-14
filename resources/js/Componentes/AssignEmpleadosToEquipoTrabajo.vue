<script setup lang="ts">
import { ref, onMounted, watch, computed } from 'vue';
import { EmpleadoEquipoTrabajoData } from '@/Data/EmpleadoEquipoTrabajo';
import { EmpleadoData } from '@/Data/Empleado';
import AlertService from '@/Services/AlertService.js';
import { Trash2, UserPlus, Check, X } from 'lucide-vue-next';

const props = defineProps({
  equipoTrabajoId: {
    type: Number,
    required: true
  },
  readOnly: {
    type: Boolean,
    default: false
  }
});

const empleadoEquipoTrabajoData = new EmpleadoEquipoTrabajoData();
const empleadoData = new EmpleadoData();

const empleados = ref<any[]>([]);
const asignados = ref<any[]>([]);
const loading = ref(false);
const showAssignModal = ref(false);
const selectedEmpleado = ref<any>(null);
const ocupacion = ref('');
const estado = ref('activo');
const searchQuery = ref('');

// Computed property for filtered employees
const filteredEmpleados = computed(() => {
  if (!searchQuery.value) return empleados.value;

  const query = searchQuery.value.toLowerCase();
  return empleados.value.filter(empleado =>
    empleado.nombre.toLowerCase().includes(query) ||
    empleado.ci.toLowerCase().includes(query) ||
    empleado.puesto.toLowerCase().includes(query)
  );
});

// Load employees assigned to this work team
const loadAssignedEmpleados = async () => {
  try {
    loading.value = true;
    const response = await empleadoEquipoTrabajoData.getEmpleadosByEquipoTrabajo(props.equipoTrabajoId);
    if (response.isSuccess) {
      asignados.value = response.data;
    } else {
      AlertService.error('Error al cargar los empleados asignados');
    }
  } catch (error) {
    console.error('Error loading assigned employees:', error);
    AlertService.error('Error al cargar los empleados asignados');
  } finally {
    loading.value = false;
  }
};

// Load all employees
const loadAllEmpleados = async () => {
  try {
    loading.value = true;
    const response = await empleadoData.query({ query: '', is_query_table: false });
    if (response.isSuccess) {
      empleados.value = response.data;
    } else {
      AlertService.error('Error al cargar los empleados');
    }
  } catch (error) {
    console.error('Error loading employees:', error);
    AlertService.error('Error al cargar los empleados');
  } finally {
    loading.value = false;
  }
};

// Open modal to assign an employee
const openAssignModal = () => {
  showAssignModal.value = true;
  loadAllEmpleados();
};

// Close the assign modal
const closeAssignModal = () => {
  showAssignModal.value = false;
  selectedEmpleado.value = null;
  ocupacion.value = '';
  estado.value = 'activo';
  searchQuery.value = '';
};

// Select an employee from the list
const selectEmpleado = (empleado: any) => {
  selectedEmpleado.value = empleado;
  // Pre-fill occupation with employee's position if available
  if (empleado.puesto) {
    ocupacion.value = empleado.puesto;
  }
};

// Check if an employee is already assigned
const isEmpleadoAssigned = (empleadoId: number) => {
  return asignados.value.some(asignado => asignado.id === empleadoId);
};

// Assign the selected employee to the work team
const assignEmpleado = async () => {
  if (!selectedEmpleado.value) {
    AlertService.error('Debe seleccionar un empleado');
    return;
  }

  if (!ocupacion.value) {
    AlertService.error('Debe ingresar una ocupación');
    return;
  }

  try {
    loading.value = true;
    const data = {
      empleado_id: selectedEmpleado.value.id,
      equipo_trabajo_id: props.equipoTrabajoId,
      ocupacion: ocupacion.value,
      estado: estado.value
    };

    const response = await empleadoEquipoTrabajoData.assignEmpleadoToEquipoTrabajo(data);
    if (response.isSuccess) {
      AlertService.success('Empleado asignado correctamente');
      closeAssignModal();
      loadAssignedEmpleados();
    } else {
      AlertService.error(response.message || 'Error al asignar el empleado');
    }
  } catch (error) {
    console.error('Error assigning employee:', error);
    AlertService.error('Error al asignar el empleado');
  } finally {
    loading.value = false;
  }
};

// Remove an employee from the work team
const removeEmpleado = async (id: number) => {
  try {
    if (confirm('¿Está seguro de eliminar este empleado del equipo de trabajo?')) {
      loading.value = true;
      const response = await empleadoEquipoTrabajoData.removeEmpleadoFromEquipoTrabajo(id);
      if (response.isSuccess) {
        AlertService.success('Empleado removido correctamente');
        loadAssignedEmpleados();
      } else {
        AlertService.error(response.message || 'Error al remover el empleado');
      }
    }
  } catch (error) {
    console.error('Error removing employee:', error);
    AlertService.error('Error al remover el empleado');
  } finally {
    loading.value = false;
  }
};

// Watch for changes in equipoTrabajoId
watch(() => props.equipoTrabajoId, (newVal) => {
  if (newVal) {
    loadAssignedEmpleados();
  } else {
    asignados.value = [];
  }
}, { immediate: true });

onMounted(() => {
  if (props.equipoTrabajoId) {
    loadAssignedEmpleados();
  }
});
</script>

<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 animate-fadeIn">
    <!-- Header with title and add button -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
        Empleados Asignados
      </h2>
      <button
        v-if="!readOnly"
        @click="openAssignModal"
        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:shadow-md"
      >
        <UserPlus class="h-5 w-5" />
        <span>Asignar Empleado</span>
      </button>
    </div>

    <!-- Loading indicator -->
    <div v-if="loading" class="flex justify-center my-8">
      <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- Empty state -->
    <div v-else-if="asignados.length === 0" class="text-center py-8">
      <div class="text-gray-500 dark:text-gray-400">
        <p class="text-lg">No hay empleados asignados a este equipo de trabajo.</p>
        <p v-if="!readOnly" class="mt-2">Haga clic en "Asignar Empleado" para agregar empleados al equipo.</p>
      </div>
    </div>

    <!-- Employees list -->
    <div v-else class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Empleado
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Ocupación
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Estado
            </th>
            <th v-if="!readOnly" scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
              Acciones
            </th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="empleado in asignados" :key="empleado.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div>
                  <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ empleado.nombre }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    CI: {{ empleado.ci }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900 dark:text-white">{{ empleado.pivot.ocupacion }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="[
                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                empleado.pivot.estado === 'activo'
                  ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                  : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
              ]">
                {{ empleado.pivot.estado }}
              </span>
            </td>
            <td v-if="!readOnly" class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button
                @click="removeEmpleado(empleado.pivot.id)"
                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150"
              >
                <Trash2 class="h-5 w-5" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal for assigning employees -->
    <div v-if="showAssignModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col">
        <!-- Modal header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Asignar Empleado al Equipo de Trabajo</h3>
          <button @click="closeAssignModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <X class="h-5 w-5" />
          </button>
        </div>

        <!-- Modal body -->
        <div class="p-6 flex-1 overflow-y-auto">
          <!-- Search input -->
          <div class="mb-6">
            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar Empleado</label>
            <input
              type="text"
              id="search"
              v-model="searchQuery"
              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              placeholder="Buscar por nombre, CI o puesto..."
            />
          </div>

          <!-- Employees list -->
          <div class="mb-6 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden">
            <div class="max-h-60 overflow-y-auto">
              <div
                v-for="empleado in filteredEmpleados"
                :key="empleado.id"
                @click="selectEmpleado(empleado)"
                :class="[
                  'p-3 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0',
                  selectedEmpleado && selectedEmpleado.id === empleado.id
                    ? 'bg-indigo-50 dark:bg-indigo-900/30'
                    : 'hover:bg-gray-50 dark:hover:bg-gray-700',
                  isEmpleadoAssigned(empleado.id)
                    ? 'opacity-50 cursor-not-allowed'
                    : ''
                ]"
                :disabled="isEmpleadoAssigned(empleado.id)"
              >
                <div class="flex justify-between items-center">
                  <div>
                    <div class="font-medium text-gray-900 dark:text-white">{{ empleado.nombre }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      CI: {{ empleado.ci }} | Puesto: {{ empleado.puesto }}
                    </div>
                  </div>
                  <div v-if="isEmpleadoAssigned(empleado.id)" class="text-sm text-indigo-600 dark:text-indigo-400">
                    Ya asignado
                  </div>
                  <div v-else-if="selectedEmpleado && selectedEmpleado.id === empleado.id" class="text-indigo-600 dark:text-indigo-400">
                    <Check class="h-5 w-5" />
                  </div>
                </div>
              </div>
              <div v-if="filteredEmpleados.length === 0" class="p-4 text-center text-gray-500 dark:text-gray-400">
                No se encontraron empleados con ese criterio de búsqueda
              </div>
            </div>
          </div>

          <!-- Assignment form -->
          <div v-if="selectedEmpleado" class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-4">
            <div>
              <label for="ocupacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ocupación *</label>
              <input
                type="text"
                id="ocupacion"
                v-model="ocupacion"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Ej: Técnico de Limpieza, Supervisor, etc."
              />
            </div>
            <div>
              <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
              <select
                id="estado"
                v-model="estado"
                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              >
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
          <button
            @click="closeAssignModal"
            class="mr-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
          >
            Cancelar
          </button>
          <button
            @click="assignEmpleado"
            :disabled="!selectedEmpleado || !ocupacion"
            :class="[
              'px-4 py-2 rounded-md text-white',
              !selectedEmpleado || !ocupacion
                ? 'bg-indigo-400 cursor-not-allowed'
                : 'bg-indigo-600 hover:bg-indigo-700'
            ]"
          >
            Asignar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Animation for fade in */
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
  animation: fadeIn 0.3s ease-out forwards;
}

/* Animation for spin */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
