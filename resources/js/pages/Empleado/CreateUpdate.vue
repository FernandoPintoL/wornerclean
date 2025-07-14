<script setup lang="ts">
import HeaderForm from '@/Componentes/Header-Form.vue';
import AlertService from '@/Services/AlertService.js';
import UtilsServices from '@/Services/UtilsServices';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, reactive, computed } from 'vue';
import { route } from 'ziggy-js';
import { Pencil, DiamondPlus, Shield } from 'lucide-vue-next';
import { EmpleadoNegocio } from '@/Negocio/EmpleadoNegocio';
import { Empleado } from '@/Data/Empleado';
import { UserNegocio } from '@/Negocio/UserNegocio';
import { Usuario } from '@/Data/User';
import { ref } from 'vue';
import axios from 'axios';

const negocio = new EmpleadoNegocio();
const userNegocio = new UserNegocio();
const model_path = negocio.model;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: model_path.toUpperCase(),
        href: '/' + model_path,
    },
];

const props = defineProps({
    model: Object as () => Empleado | null,
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

let use_model: Empleado;
const isSystemUser = ref(false);
const roles = ref([]);
const selectedRoles = ref([]);
const loadingRoles = ref(false);
const roleSearch = ref('');

// Computed property for filtered roles based on search
const filteredRoles = computed(() => {
    if (!roleSearch.value) {
        return roles.value;
    }

    const searchTerm = roleSearch.value.toLowerCase();
    return roles.value.filter(role =>
        role.name.toLowerCase().includes(searchTerm)
    );
});

// Fetch all roles
const fetchRoles = async () => {
    try {
        loadingRoles.value = true;
        const response = await axios.get('/api/roles/query', {
            params: { perPage: 100 } // Get all roles
        });
        if (response.data.isSuccess) {
            roles.value = response.data.data;
        } else {
            AlertService.error('Error al cargar los roles');
        }
    } catch (error) {
        console.error('Error fetching roles:', error);
        AlertService.error('Error al cargar los roles');
    } finally {
        loadingRoles.value = false;
    }
};

// Fetch roles for an employee
const fetchEmployeeRoles = async (employeeId) => {
    if (!employeeId) return;

    try {
        loadingRoles.value = true;
        const response = await axios.get(`/api/empleado/${employeeId}/roles`);
        if (response.data.isSuccess) {
            selectedRoles.value = response.data.data;
        } else {
            AlertService.error('Error al cargar los roles del empleado');
        }
    } catch (error) {
        console.error('Error fetching employee roles:', error);
        AlertService.error('Error al cargar los roles del empleado');
    } finally {
        loadingRoles.value = false;
    }
};

const form = useForm({
    id: props.model != null ? props.model.id : '',
    ci: props.model != null ? props.model.ci : '',
    nombre: props.model != null ? props.model.nombre : '',
    telefono: props.model != null ? props.model.telefono : '',
    puesto: props.model != null ? props.model.puesto : '',
    estado: props.model != null ? props.model.estado : '',
    user_id: props.model != null ? props.model.user_id : '',
    // User fields
    user_name: '',
    user_email: '',
    user_usernick: '',
    user_password: '',
});

onMounted(() => {
    if (props.model) {
        form.id = props.model.id;
        form.nombre = props.model.nombre;
        form.ci = props.model.ci;
        form.telefono = props.model.telefono;
        form.puesto = props.model.puesto;
        form.estado = props.model.estado;
        form.user_id = props.model.user_id;

        // If the employee has a user_id, set isSystemUser to true
        if (props.model.user_id) {
            isSystemUser.value = true;
            // TODO: Ideally, we would fetch the user data here to populate the user fields
        }

        // Fetch roles for this employee
        if (props.model.id) {
            fetchEmployeeRoles(props.model.id);
        }
    }

    // Fetch all available roles
    fetchRoles();
});

const datas = reactive({
    isLoad: false,
    nombreError: '',
    ciError: '',
    telefonoError: '',
    puestoError: '',
    estadoError: '',
    userIdError: '',
    generalError: '',
    // User field errors
    userNameError: '',
    userEmailError: '',
    userNicknameError: '',
    userPasswordError: '',
});
function cargarUseModel() {
    use_model = {
        id: Number(form.id) ?? 0,
        nombre: form.nombre,
        ci: form.ci,
        telefono: form.telefono,
        puesto: form.puesto,
        estado: form.estado
    };
    if( isSystemUser.value) {
        use_model.user_id = form.user_id ? Number(form.user_id) : 0;
    } else {
        use_model.user_id = null; // If not a system user, set user_id to null
    }
}

const createUser = async (): Promise<number | null> => {
    try {
        const user: Usuario = {
            name: form.nombre,
            email: form.user_email,
            usernick: form.user_usernick,
            password: form.user_password,
            createdAt: new Date().toISOString(),
            updatedAt: new Date().toISOString()
        };

        const response = await userNegocio.guardar(user);
        console.log(response);
        if (response.isSuccess && response.data && response.data.id) {
            return response.data.id;
        } else {
            await AlertService.error('Error al crear el usuario: ' + response.message);
            return null;
        }
    } catch (error) {
        handleUserErrors(error);
        return null;
    }
};

const assignRolesToEmployee = async (employeeId) => {
    try {
        if (selectedRoles.value.length === 0) return;

        const response = await axios.post(`/api/empleado/${employeeId}/roles`, {
            roles: selectedRoles.value
        });

        if (!response.data.isSuccess) {
            AlertService.warning('El empleado se guardó correctamente, pero hubo un problema al asignar los roles.');
        }
    } catch (error) {
        console.error('Error assigning roles:', error);
        AlertService.warning('El empleado se guardó correctamente, pero hubo un problema al asignar los roles.');
    }
};

const create_model = async () => {
    try {
        // If system user is selected, create user first
        if (isSystemUser.value) {
            const userId = await createUser();
            if (!userId) {
                return; // Stop if user creation failed
            }
            form.user_id = userId;
        }

        cargarUseModel();
        const response = await negocio.guardar(use_model);
        if (response.isSuccess) {
            // Assign roles to the newly created employee
            await assignRolesToEmployee(response.data.id);

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
        // Handle user creation/update if isSystemUser is true
        if (isSystemUser.value) {
            if (!form.user_id) {
                // If no user_id exists, create a new user
                const userId = await createUser();
                if (!userId) {
                    return; // Stop if user creation failed
                }
                form.user_id = userId;
            } else {
                // TODO: If user_id exists, we could update the user here
                // This would require a new function to update user data
                // For now, we'll just continue with the employee update
            }
        } else {
            // If isSystemUser is false but the employee had a user_id before,
            // we could handle this case (e.g., disassociate the user)
            // For now, we'll just continue with the employee update
        }

        cargarUseModel();
        const response = await negocio.actualizar(use_model, form.id ?? 0);
        if (response.isSuccess) {
            // Assign roles to the updated employee
            await assignRolesToEmployee(form.id);

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

const handleUserErrors = (error: any) => {
    if (error.response.status === 403) {
        AlertService.error('No tiene permiso para realizar esta acción.');
        return;
    }
    if (error.response.data && error.response.data.statusCode === 422) {
        const errors = error.response.data.messageError;
        for (const key in errors) {
            if (key === 'name') {
                datas.userNameError = errors[key][0];
            } else if (key === 'email') {
                datas.userEmailError = errors[key][0];
            } else if (key === 'nickname') {
                datas.userNicknameError = errors[key][0];
            } else if (key === 'password') {
                datas.userPasswordError = errors[key][0];
            }
        }
    } else {
        AlertService.error(error.response.data.message);
        datas.generalError = 'Ocurrió un error inesperado al crear el usuario. Por favor, inténtelo de nuevo.';
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
            if (key === 'nombre') {
                datas.nombreError = errors[key][0];
            } else if (key === 'ci') {
                datas.ciError = errors[key][0];
            } else if (key === 'telefono') {
                datas.telefonoError = errors[key][0];
            } else if (key === 'puesto') {
                datas.puestoError = errors[key][0];
            } else if (key === 'estado') {
                datas.estadoError = errors[key][0];
            } else if (key === 'user_id') {
                datas.userIdError = errors[key][0];
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
                                Información del Empleado
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
                        <!-- Nombre -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.1s;">
                            <label
                                for="nombre"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.nombreError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Nombre *</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="nombre"
                                    id="nombre"
                                    v-model="form.nombre"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.nombreError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Nombre del empleado"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.nombreError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.nombreError" />
                        </div>

                        <!-- CI -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.2s;">
                            <label
                                for="ci"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.ciError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >CI *</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="ci"
                                    id="ci"
                                    v-model="form.ci"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.ciError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="CI del empleado"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.ciError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.ciError" />
                        </div>

                        <!-- Teléfono -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.3s;">
                            <label
                                for="telefono"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.telefonoError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Teléfono *</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="telefono"
                                    id="telefono"
                                    v-model="form.telefono"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.telefonoError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Teléfono del empleado"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.telefonoError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.telefonoError" />
                        </div>

                        <!-- Puesto -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.5s;">
                            <label
                                for="puesto"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.puestoError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Puesto *</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="puesto"
                                    id="puesto"
                                    v-model="form.puesto"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.puestoError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Puesto del empleado"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.puestoError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.puestoError" />
                        </div>

                        <!-- Estado -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.6s;">
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
                                    id="estado"
                                    name="estado"
                                    v-model="form.estado"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.estadoError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                >
                                    <option value="" disabled selected>Seleccione un estado</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                                <div class="absolute inset-y-0 right-8 flex items-center pr-3 pointer-events-none" v-if="datas.estadoError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.estadoError" />
                        </div>
                    </div>

                    <!-- System User Toggle -->
                    <div class="col-span-1 md:col-span-2 mt-6 mb-4 animate-fadeIn" style="animation-delay: 0.7s;">
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white dark:bg-gray-800 px-4 text-sm text-gray-500 dark:text-gray-400">
                                    Información de Usuario del Sistema
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="isSystemUser" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">¿Es usuario del sistema?</span>
                            </label>
                        </div>
                    </div>

                    <!-- User Form Fields (conditional) -->
                    <div v-if="isSystemUser" class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn" style="animation-delay: 0.8s;">
                        <!-- User Name -->
                        <div class="form-group hidden">
                            <label
                                for="user_name"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.userNameError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Nombre de Usuario *</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="user_name"
                                    id="user_name"
                                    v-model="form.user_name"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.userNameError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Nombre de usuario"
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.userNameError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.userNameError" />
                        </div>

                        <!-- User Email -->
                        <div class="form-group">
                            <label
                                for="user_email"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.userEmailError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Email *</label>
                            <div class="relative">
                                <input
                                    type="email"
                                    name="user_email"
                                    id="user_email"
                                    v-model="form.user_email"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.userEmailError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Email del usuario"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.userEmailError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.userEmailError" />
                        </div>

                        <!-- User Nickname -->
                        <div class="form-group">
                            <label
                                for="user_nickname"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.userNicknameError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Nickname *</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="user_nickname"
                                    id="user_nickname"
                                    v-model="form.user_usernick"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.userNicknameError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Nickname del usuario"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.userNicknameError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.userNicknameError" />
                        </div>

                        <!-- User Password -->
                        <div class="form-group">
                            <label
                                for="user_password"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.userPasswordError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Contraseña *</label>
                            <div class="relative">
                                <input
                                    type="password"
                                    name="user_password"
                                    id="user_password"
                                    v-model="form.user_password"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.userPasswordError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Contraseña del usuario"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.userPasswordError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.userPasswordError" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">La contraseña debe tener al menos 6 caracteres</p>
                        </div>
                    </div>

                    <!-- Roles Section -->
                    <div class="mt-8 animate-fadeIn" style="animation-delay: 0.9s;">
                        <!-- Form divider with gradient -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white dark:bg-gray-800 px-4 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                    <Shield class="h-4 w-4 mr-2" />
                                    Roles y Permisos
                                </span>
                            </div>
                        </div>

                        <!-- Loading state for roles -->
                        <div v-if="loadingRoles" class="flex justify-center items-center py-8">
                            <div class="w-10 h-10 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                            <p class="ml-3 text-gray-600 dark:text-gray-400">Cargando roles...</p>
                        </div>

                        <!-- Roles list -->
                        <div v-else-if="roles.length > 0" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    Seleccione los roles que desea asignar a este empleado:
                                </p>

                                <!-- Search roles -->
                                <div class="relative mb-4">
                                    <input
                                        type="text"
                                        placeholder="Buscar roles..."
                                        class="w-full rounded-lg border border-gray-300 bg-white p-2.5 pl-10 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                        v-model="roleSearch"
                                    />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Grid of roles -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-2">
                                    <div
                                        v-for="role in filteredRoles"
                                        :key="role.id"
                                        class="flex items-center p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-150"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="'role-' + role.id"
                                            :value="role.id"
                                            v-model="selectedRoles"
                                            class="h-5 w-5 rounded-md border-2 border-gray-300 checked:border-indigo-600 checked:bg-indigo-600 focus:ring-2 focus:ring-indigo-500 transition-colors duration-200"
                                        />
                                        <label
                                            :for="'role-' + role.id"
                                            class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer"
                                        >
                                            {{ role.name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- No roles found -->
                        <div v-else class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 text-center">
                            <p class="text-yellow-700 dark:text-yellow-400">
                                No se encontraron roles disponibles. Por favor, cree algunos roles primero.
                            </p>
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
