# Form Components Documentation

This documentation explains how to use the form components to create consistent and responsive forms across the application.

## Overview

The form components system consists of:

1. **Shared CSS Styles**: Common styles for all forms in the application
2. **BaseForm Component**: A template component for form layouts
3. **FormField Component**: A reusable component for form fields

These components work together to provide a consistent look and feel for all forms in the application, while also making it easier to create and maintain forms.

## Installation

The components are already installed in the project. To use them, you need to:

1. Import the components in your form file
2. Use the components in your template

## Usage

### Basic Form Structure

Here's a basic example of how to use the form components:

```vue
<script setup lang="ts">
import { ref, reactive } from 'vue';
import BaseForm from '@/components/BaseForm.vue';
import FormField from '@/components/FormField.vue';

// Define props, form data, and methods here
const modelPath = 'your-model-path';
const form = reactive({
    field1: '',
    field2: '',
    // Add more fields as needed
});
const errors = reactive({
    field1Error: '',
    field2Error: '',
    // Add more error fields as needed
});

// Handle form submission
const handleSubmit = async () => {
    // Your form submission logic here
};
</script>

<template>
    <BaseForm
        :modelPath="modelPath"
        :formTitle="'Your Form Title'"
        @submit="handleSubmit"
    >
        <template #form-fields>
            <FormField
                type="text"
                label="Field 1"
                name="field1"
                v-model="form.field1"
                placeholder="Enter field 1"
                :required="true"
                :error="errors.field1Error"
                animationDelay="0.1s"
            />
            
            <FormField
                type="text"
                label="Field 2"
                name="field2"
                v-model="form.field2"
                placeholder="Enter field 2"
                :required="true"
                :error="errors.field2Error"
                animationDelay="0.2s"
            />
            
            <!-- Add more form fields as needed -->
        </template>
    </BaseForm>
</template>
```

### BaseForm Component

The `BaseForm` component provides a consistent layout for all forms in the application. It includes:

- A header with the model name and creation/update dates
- A form divider with a title
- A loading overlay
- A submit button

#### Props

| Prop | Type | Required | Default | Description |
|------|------|----------|---------|-------------|
| model | Object | No | null | The model object being edited (null for create) |
| isCreate | Boolean | No | true | Whether this is a create form (true) or edit form (false) |
| modelPath | String | Yes | - | The model path (e.g., 'cliente', 'empleado') |
| formTitle | String | Yes | - | The title to display in the form divider |
| permissions | Object | No | { crear: false, editar: false, eliminar: false } | Permissions for the form |

#### Events

| Event | Description |
|-------|-------------|
| submit | Emitted when the form is submitted |
| cancel | Emitted when the form is cancelled |

#### Slots

| Slot | Description |
|------|-------------|
| form-fields | The form fields to display in the form |

#### Methods

| Method | Description |
|--------|-------------|
| setLoading(value: boolean) | Set the loading state of the form |

### FormField Component

The `FormField` component provides a consistent look and feel for form fields. It supports different types of inputs, including text, email, password, select, and textarea.

#### Props

| Prop | Type | Required | Default | Description |
|------|------|----------|---------|-------------|
| type | String | No | 'text' | The type of input (text, email, password, select, textarea, etc.) |
| label | String | Yes | - | The label for the field |
| name | String | Yes | - | The name of the field (used for id and name attributes) |
| modelValue | Any | No | '' | The value of the field (v-model) |
| placeholder | String | No | '' | The placeholder text for the field |
| required | Boolean | No | false | Whether the field is required |
| error | String | No | '' | The error message for the field |
| options | Array | No | [] | Options for select fields (array of { value, label } objects) |
| fullWidth | Boolean | No | false | Whether the field should span the full width |
| animationDelay | String | No | '0s' | The delay for the animation (for staggered animations) |

#### Events

| Event | Description |
|-------|-------------|
| update:modelValue | Emitted when the value of the field changes |

## Example: Cliente Form

Here's an example of how to use the form components for the Cliente form:

```vue
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
    // Other props...
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

// Reactive data for error handling
const datas = reactive({
    ciError: '',
    nombreError: '',
    telefonoError: '',
    direccionError: '',
    tipo_cliente_idError: '',
    generalError: '',
});

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
            
            <!-- Add more form fields as needed -->
        </template>
    </BaseForm>
</template>
```

## Best Practices

1. **Use Animation Delays**: Use the `animationDelay` prop to create staggered animations for form fields.
2. **Handle Errors**: Use the `error` prop to display error messages for form fields.
3. **Use Full Width for Select Fields**: Use the `fullWidth` prop for select fields and textareas to make them span the full width of the form.
4. **Clear Errors on Submit**: Clear all error fields before submitting the form to ensure a clean state.
5. **Use the BaseForm Reference**: Use the `baseFormRef` to control the loading state of the form.

## Customization

The form components are designed to be customizable. You can:

1. **Add New Field Types**: Extend the `FormField` component to support new field types.
2. **Customize Styles**: Modify the CSS styles in the `form-styles.css` file.
3. **Add New Components**: Create new components that work with the existing form components.

## Troubleshooting

If you encounter any issues with the form components, check the following:

1. **Import Paths**: Make sure the import paths are correct.
2. **Props**: Make sure all required props are provided.
3. **v-model**: Make sure the v-model binding is correct.
4. **Error Handling**: Make sure error handling is properly implemented.

## Conclusion

The form components provide a consistent and responsive way to create forms in the application. By using these components, you can ensure that all forms have the same look and feel, while also making it easier to create and maintain forms.
