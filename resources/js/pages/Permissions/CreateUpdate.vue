<script setup lang="ts">
import HeaderForm from '@/Componentes/Header-Form.vue';
import AlertService from '@/Services/AlertService.js';
import UtilsServices from '@/Services/UtilsServices';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, reactive } from 'vue';
import { route } from 'ziggy-js';
import { Pencil, DiamondPlus } from 'lucide-vue-next';
import { PermissionNegocio } from '@/Negocio/PermissionNegocio';
import { Permission } from '@/Data/Permission';

const negocio = new PermissionNegocio();
const model_path = negocio.model;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: model_path.toUpperCase(),
        href: '/' + model_path,
    },
];

const props = defineProps({
    model: Object as () => Permission | null,
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

let use_model: Permission;
const form = useForm({
    id: props.model != null ? props.model.id : '',
    name: props.model != null ? props.model.name : '',
    guard_name: props.model != null ? props.model.guard_name : 'web',
});

onMounted(() => {
    if (props.model) {
        form.id = props.model.id;
        form.name = props.model.name;
        form.guard_name = props.model.guard_name;
    }
});

const datas = reactive({
    isLoad: false,
    nameError: '',
    guard_nameError: '',
    generalError: '',
});
function cargarUseModel() {
    use_model = {
        id: Number(form.id) ?? 0,
        name: form.name,
        guard_name: form.guard_name
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
            if (key === 'name') {
                datas.nameError = errors[key][0];
            } else if (key === 'guard_name') {
                datas.guard_nameError = errors[key][0];
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
                                Información del Permiso
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
                                for="name"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.nameError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Nombre *</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    v-model="form.name"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.nameError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Nombre del permiso"
                                    required
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.nameError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.nameError" />
                        </div>

                        <!-- Guard Name -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.2s;">
                            <label
                                for="guard_name"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.guard_nameError
                                        ? 'text-red-600 dark:text-red-400'
                                        : 'text-gray-700 dark:text-gray-300'
                                ]"
                            >Guard Name</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="guard_name"
                                    id="guard_name"
                                    v-model="form.guard_name"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.guard_nameError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
                                    ]"
                                    placeholder="Guard name (default: web)"
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" v-if="datas.guard_nameError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.guard_nameError" />
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
