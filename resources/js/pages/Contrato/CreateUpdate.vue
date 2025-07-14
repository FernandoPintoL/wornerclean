<script setup lang="ts">
import HeaderForm from '@/Componentes/Header-Form.vue';
import AlertService from '@/Services/AlertService.js';
import UtilsServices from '@/Services/UtilsServices';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, reactive, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import { Pencil, DiamondPlus, Plus } from 'lucide-vue-next';
import { ContratoNegocio } from '@/Negocio/ContratoNegocio';
import { Contrato } from '@/Data/Contrato';
import { Cliente } from '@/Data/Cliente';
import { Servicio } from '@/Data/Servicio';
import SearchableSelect from '@/Componentes/SearchableSelect.vue';
import ContratoIncidenciasManager from '@/Componentes/ContratoIncidenciasManager.vue';
import Swal from 'sweetalert2';

const negocio = new ContratoNegocio();
const model_path = negocio.model;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: model_path.toUpperCase(),
        href: '/' + model_path,
    },
];

const props = defineProps({
    model: Object as () => Contrato | null,
    isCreate: {
        type: Boolean,
        default: false,
    },
    crear: {
        type: Boolean,
        default: false,
    },
    editar: {
        type: Boolean,
        default: false,
    },
    eliminar: {
        type: Boolean,
        default: false,
    },
});

let use_model: Contrato;
const clientesList = ref<Cliente[]>([]);
const serviciosList = ref<Servicio[]>([]);
const showClienteModal = ref(false);
const showServicioModal = ref(false);
const newClienteNombre = ref('');
const newServicioNombre = ref('');
const isLoadingClientes = ref(false);
const isLoadingServicios = ref(false);

const form = useForm({
    id: props.model != null ? props.model.id : '',
    cliente_id: props.model != null ? props.model.cliente_id : '',
    servicio_id: props.model != null ? props.model.servicio_id : '',
    descripcion: props.model != null ? props.model.descripcion : '',
    costo_total: props.model != null ? props.model.costo_total : '',
    estado: props.model != null ? props.model.estado : '',
    fecha_inicio: props.model != null ? props.model.fecha_inicio : '',
    fecha_fin: props.model != null ? props.model.fecha_fin : '',
    estado_pago: props.model != null ? props.model.estado_pago : '',
    monto_pagado: props.model != null ? props.model.monto_pagado : '',
    monto_saldo: props.model != null ? props.model.monto_saldo : '',
});

// Cargar clientes
const loadClientes = async () => {
    try {
        isLoadingClientes.value = true;
        const response = await negocio.negocioCliente.consultar({
            query: '',
            is_query_table: true,
            perPage: 100 // Cargar un número razonable de clientes
        });
        if (response.isSuccess && response.data && response.data.data) {
            clientesList.value = response.data.data;
        } else {
            AlertService.error('Error al cargar los clientes');
        }
    } catch (error) {
        console.error('Error al cargar clientes:', error);
        AlertService.error('Error al cargar los clientes');
    } finally {
        isLoadingClientes.value = false;
    }
};

// Cargar servicios
const loadServicios = async () => {
    try {
        isLoadingServicios.value = true;
        const response = await negocio.negocioServicio.consultar({
            query: '',
            is_query_table: true,
            perPage: 100 // Cargar un número razonable de servicios
        });
        if (response.isSuccess && response.data && response.data.data) {
            serviciosList.value = response.data.data;
        } else {
            AlertService.error('Error al cargar los servicios');
        }
    } catch (error) {
        console.error('Error al cargar servicios:', error);
        AlertService.error('Error al cargar los servicios');
    } finally {
        isLoadingServicios.value = false;
    }
};

// Crear nuevo cliente
const createCliente = async () => {
    if (!newClienteNombre.value.trim()) {
        AlertService.error('El nombre del cliente es requerido');
        return;
    }

    try {
        datas.isLoad = true;
        const nuevoCliente: Cliente = {
            nombre: newClienteNombre.value,
            ci: '', // Estos campos son requeridos pero se pueden completar después
            direccion: '',
            telefono: '',
            tipo_cliente: 'regular'
        };

        const response = await negocio.negocioCliente.guardar(nuevoCliente);
        if (response.isSuccess) {
            AlertService.success('Cliente creado con éxito');
            await loadClientes();

            // Seleccionar el cliente recién creado
            if (response.data && response.data.id) {
                form.cliente_id = response.data.id;
            }

            // Cerrar el modal
            showClienteModal.value = false;
            newClienteNombre.value = '';
        } else {
            AlertService.error(response.message || 'Error al crear el cliente');
        }
    } catch (error) {
        console.error('Error al crear cliente:', error);
        AlertService.error('Error al crear el cliente');
    } finally {
        datas.isLoad = false;
    }
};

// Crear nuevo servicio
const createServicio = async () => {
    if (!newServicioNombre.value.trim()) {
        AlertService.error('El nombre del servicio es requerido');
        return;
    }

    try {
        datas.isLoad = true;
        const nuevoServicio: Servicio = {
            nombre: newServicioNombre.value,
            descripcion: '',
            precio: 0,
            frecuencia: 'mensual',
            estado: 'activo'
        };

        const response = await negocio.negocioServicio.guardar(nuevoServicio);
        if (response.isSuccess) {
            AlertService.success('Servicio creado con éxito');
            await loadServicios();

            // Seleccionar el servicio recién creado
            if (response.data && response.data.id) {
                form.servicio_id = response.data.id;
            }

            // Cerrar el modal
            showServicioModal.value = false;
            newServicioNombre.value = '';
        } else {
            AlertService.error(response.message || 'Error al crear el servicio');
        }
    } catch (error) {
        console.error('Error al crear servicio:', error);
        AlertService.error('Error al crear el servicio');
    } finally {
        datas.isLoad = false;
    }
};

// Manejar la creación de un nuevo cliente desde el SearchableSelect
const handleCreateCliente = (searchQuery : string) => {
    // Usar SweetAlert2 para crear un nuevo cliente
    Swal.fire({
        title: 'Crear Nuevo Cliente',
        html: `
            <div class="mb-4">
                <label for="swal-cliente-nombre" class="block text-sm font-medium text-gray-700 mb-1 text-left">Nombre del Cliente *</label>
                <input id="swal-cliente-nombre" class="swal2-input w-full" value="${searchQuery || ''}" placeholder="Nombre del cliente">
            </div>
            <div class="mb-4">
                <label for="swal-cliente-ci" class="block text-sm font-medium text-gray-700 mb-1 text-left">CI/RUC</label>
                <input id="swal-cliente-ci" class="swal2-input w-full" placeholder="CI o RUC del cliente">
            </div>
            <div class="mb-4">
                <label for="swal-cliente-telefono" class="block text-sm font-medium text-gray-700 mb-1 text-left">Teléfono</label>
                <input id="swal-cliente-telefono" class="swal2-input w-full" placeholder="Teléfono del cliente">
            </div>
            <div class="mb-4">
                <label for="swal-cliente-direccion" class="block text-sm font-medium text-gray-700 mb-1 text-left">Dirección</label>
                <input id="swal-cliente-direccion" class="swal2-input w-full" placeholder="Direccion del cliente">
            </div>
            <p class="text-xs text-gray-500 mt-2 text-left">* Campo obligatorio</p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Crear',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const nombreInput = document.getElementById('swal-cliente-nombre') as HTMLInputElement | null;
            const ciInput = document.getElementById('swal-cliente-ci') as HTMLInputElement | null;
            const telefonoInput = document.getElementById('swal-cliente-telefono') as HTMLInputElement | null;
            const direccionInput = document.getElementById('swal-cliente-direccion') as HTMLInputElement | null;

            const nombre = nombreInput?.value ?? '';
            const ci = ciInput?.value ?? '';
            const telefono = telefonoInput?.value ?? '';
            const direccion = direccionInput?.value ?? '';

            if (!nombre.trim()) {
                Swal.showValidationMessage('El nombre del cliente es obligatorio');
                return false;
            }

            return {
                nombre: nombre,
                ci: ci || '',
                telefono: telefono || '',
                direccion: direccion || '',
                tipo_cliente: 'regular'
            };
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Crear el cliente con los datos del formulario
            createClienteFromModal(result.value);
        }
    });
};

// Función para crear un cliente desde el modal
const createClienteFromModal = async (clienteData : Cliente) => {
    try {
        datas.isLoad = true;
        const response = await negocio.negocioCliente.guardar(clienteData);
        if (response.isSuccess) {
            AlertService.success('Cliente creado con éxito');
            await loadClientes();

            // Seleccionar el cliente recién creado
            if (response.data && response.data.id) {
                form.cliente_id = response.data.id;
            }
        } else {
            AlertService.error(response.message || 'Error al crear el cliente');
        }
    } catch (error) {
        console.error('Error al crear cliente:', error);
        AlertService.error('Error al crear el cliente');
    } finally {
        datas.isLoad = false;
    }
};

// Manejar la creación de un nuevo servicio desde el SearchableSelect
const handleCreateServicio = (searchQuery : string) => {
    // Usar SweetAlert2 para crear un nuevo servicio
    Swal.fire({
        title: 'Crear Nuevo Servicio',
        html: `
            <div class="mb-4">
                <label for="swal-servicio-nombre" class="block text-sm font-medium text-gray-700 mb-1 text-left">Nombre del Servicio *</label>
                <input id="swal-servicio-nombre" class="swal2-input w-full" value="${searchQuery || ''}" placeholder="Nombre del servicio">
            </div>
            <div class="mb-4">
                <label for="swal-servicio-descripcion" class="block text-sm font-medium text-gray-700 mb-1 text-left">Descripción</label>
                <textarea id="swal-servicio-descripcion" class="swal2-textarea w-full" placeholder="Descripción del servicio"></textarea>
            </div>
            <div class="mb-4">
                <label for="swal-servicio-precio" class="block text-sm font-medium text-gray-700 mb-1 text-left">Precio</label>
                <input id="swal-servicio-precio" type="number" step="0.01" min="0" class="swal2-input w-full" placeholder="Precio del servicio">
            </div>
            <div class="mb-4">
                <label for="swal-servicio-frecuencia" class="block text-sm font-medium text-gray-700 mb-1 text-left">Frecuencia</label>
                <select id="swal-servicio-frecuencia" class="swal2-select w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <option value="diaria">Diaria</option>
                    <option value="semanal">Semanal</option>
                    <option value="quincenal">Quincenal</option>
                    <option value="mensual" selected>Mensual</option>
                    <option value="bimestral">Bimestral</option>
                    <option value="trimestral">Trimestral</option>
                    <option value="semestral">Semestral</option>
                    <option value="anual">Anual</option>
                    <option value="unica">Única vez</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="swal-servicio-estado" class="block text-sm font-medium text-gray-700 mb-1 text-left">Estado</label>
                <select id="swal-servicio-estado" class="swal2-select w-full border border-gray-300 rounded-md shadow-sm p-2">
                    <option value="activo" selected>Activo</option>
                    <option value="inactivo">Inactivo</option>
                    <option value="suspendido">Suspendido</option>
                </select>
            </div>
            <p class="text-xs text-gray-500 mt-2 text-left">* Campo obligatorio</p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Crear',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const nombreInput = document.getElementById('swal-servicio-nombre') as HTMLInputElement | null;
            const descripcionInput = document.getElementById('swal-servicio-descripcion') as HTMLInputElement | null;
            const precioStrInput = document.getElementById('swal-servicio-precio') as HTMLInputElement | null;
            const frecuenciaInput = document.getElementById('swal-servicio-frecuencia') as HTMLSelectElement | null;
            const estadoInput = document.getElementById('swal-servicio-estado') as HTMLSelectElement | null;
            const precio = precioStrInput ? parseFloat(precioStrInput.value) : 0;

            if (!nombreInput?.value.trim()) {
                Swal.showValidationMessage('El nombre del servicio es obligatorio');
                return false;
            }

            return {
                nombre: nombreInput?.value,
                descripcion: descripcionInput?.value || '',
                precio: precio,
                frecuencia: frecuenciaInput?.value || 'mensual',
                estado: estadoInput?.value || 'activo'
            };
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Crear el servicio con los datos del formulario
            createServicioFromModal(result.value);
        }
    });
};

// Función para crear un servicio desde el modal
const createServicioFromModal = async (servicioData : Servicio) => {
    try {
        datas.isLoad = true;
        const response = await negocio.negocioServicio.guardar(servicioData);
        if (response.isSuccess) {
            AlertService.success('Servicio creado con éxito');
            await loadServicios();

            // Seleccionar el servicio recién creado
            if (response.data && response.data.id) {
                form.servicio_id = response.data.id;
                // Actualizar el costo total con el precio del nuevo servicio
                updateCostoTotal();
            }
        } else {
            AlertService.error(response.message || 'Error al crear el servicio');
        }
    } catch (error) {
        console.error('Error al crear servicio:', error);
        AlertService.error('Error al crear el servicio');
    } finally {
        datas.isLoad = false;
    }
};

// Función para actualizar el costo total basado en el servicio seleccionado
const updateCostoTotal = () => {
    if (form.servicio_id) {
        const selectedServicio = serviciosList.value.find(servicio => servicio.id === Number(form.servicio_id));
        if (selectedServicio) {
            form.costo_total = selectedServicio.precio.toString();
        }
    }
};

// Watcher para el servicio_id
watch(() => form.servicio_id, (newValue) => {
    if (newValue) {
        updateCostoTotal();
    }
}, { immediate: true });

onMounted(async () => {
    // Cargar datos iniciales
    await Promise.all([loadClientes(), loadServicios()]);

    if (props.model) {
        form.id = props.model.id;
        form.cliente_id = props.model.cliente_id;
        form.servicio_id = props.model.servicio_id;
        form.descripcion = props.model.descripcion;
        form.costo_total = props.model.costo_total;
        form.estado = props.model.estado;
        form.fecha_inicio = props.model.fecha_inicio;
        form.fecha_fin = props.model.fecha_fin;
        form.estado_pago = props.model.estado_pago;
        form.monto_pagado = props.model.monto_pagado;
        form.monto_saldo = props.model.monto_saldo;
    } else {
        // Establecer fecha inicial como hoy
        const today = new Date();
        // Establecer fecha final como un mes después
        const nextMonth = new Date();
        nextMonth.setMonth(nextMonth.getMonth() + 1);

        // Formatear fechas para input type="date" (YYYY-MM-DD)
        form.fecha_inicio = today.toISOString().split('T')[0];
        form.fecha_fin = nextMonth.toISOString().split('T')[0];

        // Actualizar costo total si ya hay un servicio seleccionado
        if (form.servicio_id) {
            updateCostoTotal();
        }
    }
});

const datas = reactive({
    isLoad: false,
    cliente_idError: '',
    servicio_idError: '',
    descripcionError: '',
    costo_totalError: '',
    estadoError: '',
    fecha_inicioError: '',
    fecha_finError: '',
    estado_pagoError: '',
    monto_pagadoError: '',
    monto_saldoError: '',
    generalError: '',
});
function cargarUseModel() {
    use_model = {
        id: Number(form.id) ?? 0,
        cliente_id: Number(form.cliente_id),
        servicio_id: Number(form.servicio_id),
        descripcion: form.descripcion,
        costo_total: Number(form.costo_total),
        estado: form.estado,
        fecha_inicio: form.fecha_inicio,
        fecha_fin: form.fecha_fin,
        estado_pago: form.estado_pago,
        monto_pagado: Number(form.monto_pagado) ?? 0,
        monto_saldo: Number(form.monto_saldo) ?? 0
    };
}


const create_model = async () => {
    try {
        cargarUseModel();
        console.log(use_model);
        const response = await negocio.guardar(use_model);
        console.log(response);
        if (response.isSuccess) {
            await AlertService.success('La operación se completó con éxito.').then(() => {
                window.location.href = route(model_path + '.index');
            });
            form.reset();
        } else {
            await AlertService.error(response.message);
        }
    } catch (error) {
        handleErrors(error);
    }
};

const editar_model = async () => {
    try {
        cargarUseModel();
        const response = await negocio.actualizar(use_model, form.id ?? 0);
        if (response.isSuccess) {
            await AlertService.success('La operación se completó con éxito.').then(() => {
                window.location.href = route(model_path + '.index');
            });
        } else {
            await AlertService.error(response.message);
        }
    } catch (error) {
        handleErrors(error);
    }
};

const submit_create = async () => {
    if (props.isCreate) {
        await create_model();
    } else {
        await editar_model();
    }
};


const handleErrors = (error: any) => {
    if (error.response.status === 403) {
        AlertService.error('No tiene permiso para realizar esta acción.');
        return;
    }
    if (error.response.data && error.response.data.statusCode === 422) {
        const errors = error.response.data.messageError;
        for (const key in errors) {
            if (key === 'cliente_id') {
                datas.cliente_idError = errors[key][0];
            } else if (key === 'servicio_id') {
                datas.servicio_idError = errors[key][0];
            } else if (key === 'descripcion') {
                datas.descripcionError = errors[key][0];
            } else if (key === 'costo_total') {
                datas.costo_totalError = errors[key][0];
            } else if (key === 'estado') {
                datas.estadoError = errors[key][0];
            } else if (key === 'fecha_inicio') {
                datas.fecha_inicioError = errors[key][0];
            } else if (key === 'fecha_fin') {
                datas.fecha_finError = errors[key][0];
            } else if (key === 'estado_pago') {
                datas.estado_pagoError = errors[key][0];
            } else if (key === 'monto_pagado') {
                datas.monto_pagadoError = errors[key][0];
            } else if (key === 'monto_saldo') {
                datas.monto_saldoError = errors[key][0];
            }
        }
    } else {
        AlertService.error(error.response.data.message);
        datas.generalError = 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.';
    }
};
</script>

<template>
    <Head :title="UtilsServices.capitalizeFirstLetter(model_path)" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 sm:p-6 lg:p-8 transition-all duration-300">
            <div class="w-full max-w-7xl mx-auto">
                <!-- Header card with shadow and rounded corners -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 transition-all duration-300 animate-fadeIn">
                    <HeaderForm
                        :model_path="model_path"
                        :fecha_creado="props.model ? props.model.created_at : ''"
                        :fecha_actualizado="props.model ? props.model.updated_at : ''"
                        :isCreate="props.isCreate"
                        :id_model="props.model ? String(props.model.id) : ''"
                        class="mb-4"
                    />

                    <!-- Form divider with gradient -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white dark:bg-gray-800 px-4 text-sm text-gray-500 dark:text-gray-400">
                                Información del Contrato
                            </span>
                        </div>
                    </div>

                    <!-- Loading overlay -->
                    <div v-if="datas.isLoad" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 animate-fadeIn">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl max-w-md w-full">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                                <p class="mt-4 text-gray-700 dark:text-gray-300 font-medium">Procesando solicitud...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form with responsive grid layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Cliente -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.1s; z-index: 1000 !important;">
                            <label
                                for="cliente_id"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.cliente_idError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Cliente *</label>
                            <div class="relative" style="z-index: 1000 !important;">
                                <SearchableSelect
                                    v-model="form.cliente_id"
                                    :options="clientesList"
                                    :label="'Seleccionar cliente'"
                                    :placeholder="'Buscar cliente...'"
                                    :valueKey="'id'"
                                    :labelKey="'nombre'"
                                    :required="true"
                                    :error="datas.cliente_idError"
                                    :disabled="isLoadingClientes"
                                    @create-new="handleCreateCliente"
                                />
                                <div v-if="isLoadingClientes" class="absolute inset-y-0 right-0 flex items-center pr-10 pointer-events-none">
                                    <div class="w-5 h-5 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.cliente_idError" />
                        </div>

                        <!-- Servicio -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.2s;">
                            <label
                                for="servicio_id"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.servicio_idError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Servicio *</label>
                            <div class="relative">
                                <SearchableSelect
                                    v-model="form.servicio_id"
                                    :options="serviciosList"
                                    :label="'Seleccionar servicio'"
                                    :placeholder="'Buscar servicio...'"
                                    :valueKey="'id'"
                                    :labelKey="'nombre'"
                                    :required="true"
                                    :error="datas.servicio_idError"
                                    :disabled="isLoadingServicios"
                                    @create-new="handleCreateServicio"
                                />
                                <div v-if="isLoadingServicios" class="absolute inset-y-0 right-0 flex items-center pr-10 pointer-events-none">
                                    <div class="w-5 h-5 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.servicio_idError" />
                        </div>

                        <!-- Descripción -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.3s; z-index: 1 !important;">
                            <label
                                for="descripcion"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.descripcionError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Descripción *</label>
                            <div class="relative">
                                <textarea
                                    name="descripcion"
                                    id="descripcion"
                                    v-model="form.descripcion"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.descripcionError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Descripción del contrato"
                                    rows="3"
                                    required
                                ></textarea>
                                <div class="absolute top-3 right-3 flex items-center pr-3 pointer-events-none" v-if="datas.descripcionError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.descripcionError" />
                        </div>

                        <!-- Costo Total -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.4s;">
                            <label
                                for="costo_total"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.costo_totalError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Costo Total *</label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="costo_total"
                                    id="costo_total"
                                    v-model="form.costo_total"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.costo_totalError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-gray-100 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Costo total del contrato (automático)"
                                    step="0.01"
                                    min="0"
                                    readonly
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.costo_totalError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.costo_totalError" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Este valor se establece automáticamente según el precio del servicio seleccionado.
                            </p>
                        </div>

                        <!-- Fecha Inicio -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.5s;">
                            <label
                                for="fecha_inicio"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.fecha_inicioError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Fecha Inicio *</label>
                            <div class="relative">
                                <input
                                    type="date"
                                    name="fecha_inicio"
                                    id="fecha_inicio"
                                    v-model="form.fecha_inicio"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.fecha_inicioError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.fecha_inicioError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.fecha_inicioError" />
                        </div>

                        <!-- Fecha Fin -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.6s;">
                            <label
                                for="fecha_fin"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.fecha_finError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Fecha Fin *</label>
                            <div class="relative">
                                <input
                                    type="date"
                                    name="fecha_fin"
                                    id="fecha_fin"
                                    v-model="form.fecha_fin"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.fecha_finError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.fecha_finError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.fecha_finError" />
                        </div>

                        <!-- Estado Pago -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.7s;">
                            <label
                                for="estado_pago"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.estado_pagoError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Estado Pago *</label>
                            <div class="relative">
                                <select
                                    name="estado_pago"
                                    id="estado_pago"
                                    v-model="form.estado_pago"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.estado_pagoError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    required
                                >
                                    <option value="">Seleccione un estado de pago</option>
                                    <option value="pagado">Pagado</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="parcial">Parcial</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.estado_pagoError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.estado_pagoError" />
                        </div>

                        <!-- Estado -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.8s;">
                            <label
                                for="estado"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.estadoError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Estado *</label>
                            <div class="relative">
                                <select
                                    name="estado"
                                    id="estado"
                                    v-model="form.estado"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.estadoError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    required
                                >
                                    <option value="">Seleccione un estado</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="finalizado">Finalizado</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.estadoError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.estadoError" />
                        </div>
                    </div>


                    <!-- Submit button with improved styling -->
                    <div class="mt-8 flex justify-center sm:justify-end animate-fadeIn" style="animation-delay: 0.7s;">
                        <button
                            type="submit"
                            @click="submit_create"
                            :class="[
                                'flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-base font-medium text-white shadow-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2',
                                props.isCreate
                                    ? 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:ring-blue-500 dark:from-blue-700 dark:to-indigo-700 dark:hover:from-blue-800 dark:hover:to-indigo-800'
                                    : 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:ring-green-500 dark:from-green-700 dark:to-emerald-700 dark:hover:from-green-800 dark:hover:to-emerald-800',
                                datas.isLoad ? 'opacity-75 cursor-not-allowed' : 'hover:scale-105'
                            ]"
                            :disabled="datas.isLoad"
                        >
                            <span>{{ props.isCreate ? 'Crear' : 'Actualizar' }} {{ model_path }}</span>
                            <DiamondPlus v-if="props.isCreate" class="h-5 w-5 text-white" />
                            <Pencil v-else class="h-5 w-5 text-white" />
                        </button>
                    </div>

                    <!-- Incidencias section (only shown when editing a contract) -->
                    <div v-if="!props.isCreate && props.model && props.model.id" class="mt-8 animate-fadeIn" style="animation-delay: 0.8s;">
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white dark:bg-gray-800 px-4 text-sm text-gray-500 dark:text-gray-400">
                                    Gestión de Incidencias
                                </span>
                            </div>
                        </div>

                        <ContratoIncidenciasManager :contrato-id="Number(props.model.id)" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear nuevo cliente -->
        <div v-if="showClienteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay de fondo -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Centrar modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-indigo-900">
                                <Plus class="h-6 w-6 text-indigo-600 dark:text-indigo-300" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Crear Nuevo Cliente
                                </h3>
                                <div class="mt-4">
                                    <label for="newClienteNombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nombre del Cliente
                                    </label>
                                    <input
                                        type="text"
                                        id="newClienteNombre"
                                        v-model="newClienteNombre"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ingrese el nombre del cliente"
                                    />
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Los demás campos se pueden completar más tarde.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                        <button
                            type="button"
                            @click="createCliente"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Crear
                        </button>
                        <button
                            type="button"
                            @click="showClienteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear nuevo servicio -->
        <div v-if="showServicioModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay de fondo -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Centrar modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-indigo-900">
                                <Plus class="h-6 w-6 text-indigo-600 dark:text-indigo-300" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Crear Nuevo Servicio
                                </h3>
                                <div class="mt-4">
                                    <label for="newServicioNombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nombre del Servicio
                                    </label>
                                    <input
                                        type="text"
                                        id="newServicioNombre"
                                        v-model="newServicioNombre"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Ingrese el nombre del servicio"
                                    />
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        Los demás campos se pueden completar más tarde.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                        <button
                            type="button"
                            @click="createServicio"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Crear
                        </button>
                        <button
                            type="button"
                            @click="showServicioModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700"
                        >
                            Cancelar
                        </button>
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

/* Fade in animation for form elements */
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
    animation: fadeIn 0.4s ease-in-out forwards;
}

/* Spin animation for loading indicator */
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

/* Form group hover effects */
.form-group:hover label:not(.text-red-600):not(.dark\:text-red-400) {
    color: #4f46e5;
}

.dark .form-group:hover label:not(.text-red-600):not(.dark\:text-red-400) {
    color: #818cf8;
}

/* Input focus styles */
input:focus, select:focus {
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
    outline: 2px solid transparent;
    outline-offset: 2px;
}

.dark input:focus, .dark select:focus {
    box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.2);
}

/* Error styles */
.label-error {
    color: #ef4444 !important;
}

.input-error {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
}

.dark .input-error {
    border-color: #f87171 !important;
    background-color: rgba(239, 68, 68, 0.1) !important;
}

/* Select element text color */
select {
    color: #000000 !important; /* Black text in light mode */
}

.dark select {
    color: #ffffff !important; /* White text in dark mode */
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .form-group {
        margin-bottom: 1.5rem;
    }

    input, select {
        padding: 0.75rem;
        font-size: 1rem;
    }

    button {
        width: 100%;
    }
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
</style>
