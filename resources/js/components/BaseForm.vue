<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import HeaderForm from '@/Componentes/Header-Form.vue';
import UtilsServices from '@/Services/UtilsServices';
import { Pencil, DiamondPlus } from 'lucide-vue-next';

// Define props for the component
const props = defineProps({
    // The model object being edited (null for create)
    model: {
        type: Object,
        default: null
    },
    // Whether this is a create form (true) or edit form (false)
    isCreate: {
        type: Boolean,
        default: true
    },
    // The model path (e.g., 'cliente', 'empleado')
    modelPath: {
        type: String,
        required: true
    },
    // The title to display in the form divider
    formTitle: {
        type: String,
        required: true
    },
    // Permissions
    permissions: {
        type: Object,
        default: () => ({
            crear: false,
            editar: false,
            eliminar: false
        })
    }
});

// Define emits
const emit = defineEmits(['submit', 'cancel']);

// Loading state
const isLoading = ref(false);

// Breadcrumbs for the page
const breadcrumbs = computed(() => [
    {
        title: props.modelPath.toUpperCase(),
        href: '/' + props.modelPath,
    },
]);

// Handle form submission
const handleSubmit = () => {
    isLoading.value = true;
    emit('submit');
};

// Expose loading state to parent component
defineExpose({
    setLoading: (value: boolean) => {
        isLoading.value = value;
    }
});
</script>

<template>
    <Head :title="UtilsServices.capitalizeFirstLetter(modelPath)" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="form-container">
            <div class="w-full max-w-7xl mx-auto">
                <!-- Header card with shadow and rounded corners -->
                <div class="form-card animate-fadeIn">
                    <HeaderForm
                        :model_path="modelPath"
                        :fecha_creado="model ? model.created_at : ''"
                        :fecha_actualizado="model ? model.updated_at : ''"
                        :isCreate="isCreate"
                        :id_model="model ? String(model.id) : ''"
                        class="mb-4"
                    />

                    <!-- Form divider with gradient -->
                    <div class="form-divider">
                        <div class="form-divider-line">
                            <div class="form-divider-line-inner"></div>
                        </div>
                        <div class="form-divider-text">
                            <span class="form-divider-text-inner">
                                {{ formTitle }}
                            </span>
                        </div>
                    </div>

                    <!-- Loading overlay -->
                    <div v-if="isLoading" class="loading-overlay animate-fadeIn">
                        <div class="loading-container">
                            <div class="flex flex-col items-center">
                                <div class="loading-spinner animate-spin"></div>
                                <p class="loading-text">Procesando solicitud...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form content - slot for form fields -->
                    <div class="form-grid">
                        <slot name="form-fields"></slot>
                    </div>

                    <!-- Submit button with improved styling -->
                    <div class="form-submit-container animate-fadeIn" style="animation-delay: 0.6s;">
                        <button
                            type="submit"
                            @click="handleSubmit"
                            :class="[
                                'form-submit-button',
                                isCreate ? 'form-submit-create' : 'form-submit-update',
                                isLoading ? 'opacity-75 cursor-not-allowed' : ''
                            ]"
                            :disabled="isLoading"
                        >
                            <span>{{ isCreate ? 'Crear' : 'Actualizar' }} {{ modelPath }}</span>
                            <DiamondPlus v-if="isCreate" class="h-5 w-5 text-white" />
                            <Pencil v-else class="h-5 w-5 text-white" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
@import '../../css/form-styles.css';
</style>
