<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AlertService from '@/Services/AlertService.js';
import BaseForm from '@/components/BaseForm.vue';
import FormField from '@/components/FormField.vue';
import { ClienteNegocio } from '@/Negocio/ClienteNegocio';
import type { Cliente } from '@/Data/Cliente';

// Create an instance of the business logic class
const negocio = new ClienteNegocio();
const model_path = negocio.model;

// Define props for the component
const props = defineProps({
    model: Object as () => Cliente | null,
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

// Reference to the BaseForm component
const baseFormRef = ref(null);

// Form data using Inertia's useForm
const form = useForm({
    id: props.model != null ? props.model.id : '',
    ci: props.model != null ? props.model.ci : '',
    nombre: props.model != null ? props.model.nombre : '',
    telefono: props.model != null ? props.model.telefono : '',
    direccion: props.model != null ? props.model.direccion : '',
    tipo_cliente: props.model != null ? props.model.tipo_cliente : '',
});

// Reactive data for error handling and loading state
const datas = reactive({
    ciError: '',
    nombreError: '',
    telefonoError: '',
    direccionError: '',
    tipo_cliente_idError: '',
    generalError: '',
});

// Initialize form data when component is mounted
onMounted(() => {
    if (props.model) {
        form.id = props.model.id;
        form.ci = props.model.ci;
        form.nombre = props.model.nombre;
        form.telefono = props.model.telefono;
        form.direccion = props.model.direccion;
        form.tipo_cliente = props.model.tipo_cliente;
    }
});

// Prepare model data for API calls
function cargarUseModel(): Cliente {
    return {
        id: Number(form.id) ?? 0,
        ci: form.ci,
        nombre: form.nombre,
        telefono: form.telefono,
        direccion: form.direccion,
        tipo_cliente: form.tipo_cliente,
    };
}

// Create a new model
const create_model = async () => {
    try {
        const use_model = cargarUseModel();
        const response = await negocio.guardar(use_model);

        if (response.isSuccess) {
            await AlertService.success('La operación se completó con éxito.').then(() => {
                window.location.href = route(model_path + '.index');
            });
            form.reset();
        } else {
            baseFormRef.value.setLoading(false);
            await AlertService.error(response.data.message);
        }
    } catch (error) {
        baseFormRef.value.setLoading(false);
        handleErrors(error);
    }
};

// Update an existing model
const editar_model = async () => {
    try {
        const use_model = cargarUseModel();
        const response = await negocio.actualizar(use_model, form.id ?? 0);

        if (response.isSuccess) {
            await AlertService.success('La operación se completó con éxito.').then(() => {
                window.location.href = route(model_path + '.index');
            });
        } else {
            baseFormRef.value.setLoading(false);
            await AlertService.error(response.message);
        }
    } catch (error) {
        baseFormRef.value.setLoading(false);
        handleErrors(error);
    }
};

// Handle form submission
const handleSubmit = async () => {
    // Clear previous errors
    datas.ciError = '';
    datas.nombreError = '';
    datas.telefonoError = '';
    datas.direccionError = '';
    datas.tipo_cliente_idError = '';
    datas.generalError = '';

    if (props.isCreate) {
        await create_model();
    } else {
        await editar_model();
    }
};

// Handle API errors
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
            } else if (key === 'direccion') {
                datas.direccionError = errors[key][0];
            } else if (key === 'tipo_cliente') {
                datas.tipo_cliente_idError = errors[key][0];
            }
        }
    } else {
        AlertService.error(error.response.data.message);
        datas.generalError = 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.';
    }
};

// Options for the tipo_cliente select field
const tipoClienteOptions = [
    { value: 'regular', label: 'Regular' },
    { value: 'vip', label: 'VIP' }
];
</script>

<template>
    <BaseForm
        ref="baseFormRef"
        :model="props.model"
        :isCreate="props.isCreate"
        :modelPath="model_path"
        :formTitle="'Información del Cliente'"
        :permissions="{
            crear: props.crear,
            editar: props.editar,
            eliminar: props.eliminar
        }"
        @submit="handleSubmit"
    >
        <template #form-fields>
            <!-- Nombre -->
            <FormField
                type="text"
                label="Nombre"
                name="nombre"
                v-model="form.nombre"
                placeholder="Nombre del cliente"
                :required="true"
                :error="datas.nombreError"
                animationDelay="0.1s"
            />

            <!-- CI -->
            <FormField
                type="text"
                label="CI"
                name="ci"
                v-model="form.ci"
                placeholder="CI del cliente"
                :required="true"
                :error="datas.ciError"
                animationDelay="0.2s"
            />

            <!-- Teléfono -->
            <FormField
                type="tel"
                label="Teléfono"
                name="telefono"
                v-model="form.telefono"
                placeholder="Teléfono del cliente"
                :required="true"
                :error="datas.telefonoError"
                animationDelay="0.3s"
            />

            <!-- Dirección -->
            <FormField
                type="text"
                label="Dirección"
                name="direccion"
                v-model="form.direccion"
                placeholder="Dirección del cliente"
                :required="true"
                :error="datas.direccionError"
                animationDelay="0.4s"
            />

            <!-- Tipo de Cliente -->
            <FormField
                type="select"
                label="Tipo de Cliente"
                name="tipo_cliente"
                v-model="form.tipo_cliente"
                placeholder="Seleccione un tipo de cliente"
                :required="true"
                :error="datas.tipo_cliente_idError"
                :options="tipoClienteOptions"
                :fullWidth="true"
                animationDelay="0.5s"
            />
        </template>
    </BaseForm>
</template>
