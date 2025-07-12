# Form Components Implementation Guide

This guide provides step-by-step instructions for implementing the new form components across the application.

## Overview

The form components system has been created to provide a consistent look and feel for all forms in the application. The system consists of:

1. **Shared CSS Styles**: Common styles for all forms in the application
2. **BaseForm Component**: A template component for form layouts
3. **FormField Component**: A reusable component for form fields

This guide will help you implement these components in both existing and new forms.

## Prerequisites

Before you begin, make sure you have:

1. Reviewed the `README.md` file to understand the components and their usage
2. Familiarized yourself with the existing form structure in the application

## Implementation Steps

### Step 1: Import the CSS Styles

The shared CSS styles are located in `resources/css/form-styles.css`. You need to import these styles in your application's main CSS file or in the individual form components.

If you're using a main CSS file, add the following import:

```css
@import './form-styles.css';
```

If you're importing the styles in individual components, add the following to the component's `<style>` section:

```vue
<style>
@import '../../css/form-styles.css';
</style>
```

### Step 2: Update Existing Forms

To update an existing form to use the new components, follow these steps:

1. **Identify the form structure**: Analyze the existing form to identify the form fields, validation, and submission logic.
2. **Create a new version of the form**: Create a new version of the form that uses the BaseForm and FormField components.
3. **Test the new form**: Test the new form to ensure it works correctly.
4. **Replace the old form**: Once the new form is working correctly, replace the old form with the new one.

Here's an example of how to update the Cliente CreateUpdate form:

#### Original Form (Cliente/CreateUpdate.vue)

```vue
<script setup lang="ts">
// Imports and setup code...
</script>

<template>
    <Head :title="UtilsServices.capitalizeFirstLetter(model_path)" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 sm:p-6 lg:p-8 transition-all duration-300">
            <div class="w-full max-w-7xl mx-auto">
                <!-- Form content... -->
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Form styles... */
</style>
```

#### Updated Form (Cliente/CreateUpdateWithBase.vue)

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

// Rest of the script...
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
            <!-- Form fields... -->
        </template>
    </BaseForm>
</template>
```

### Step 3: Create New Forms

To create a new form using the new components, follow these steps:

1. **Create a new form file**: Create a new Vue file for your form.
2. **Import the components**: Import the BaseForm and FormField components.
3. **Set up the form data and validation**: Set up the form data and validation logic.
4. **Create the form template**: Create the form template using the BaseForm and FormField components.
5. **Test the form**: Test the form to ensure it works correctly.

Here's a template for creating a new form:

```vue
<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import AlertService from '@/Services/AlertService.js';
import BaseForm from '@/components/BaseForm.vue';
import FormField from '@/components/FormField.vue';
import { YourModelNegocio } from '@/Negocio/YourModelNegocio';
import type { YourModel } from '@/Data/YourModel';

// Create an instance of the business logic class
const negocio = new YourModelNegocio();
const model_path = negocio.model;

// Define props for the component
const props = defineProps({
    model: Object as () => YourModel | null,
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
    // Add your form fields here
});

// Reactive data for error handling
const datas = reactive({
    // Add your error fields here
    generalError: '',
});

// Handle form submission
const handleSubmit = async () => {
    // Clear previous errors
    // Your error clearing code here
    
    if (props.isCreate) {
        await create_model();
    } else {
        await editar_model();
    }
};

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

// Prepare model data for API calls
function cargarUseModel(): YourModel {
    return {
        id: Number(form.id) ?? 0,
        // Add your model fields here
    };
}

// Handle API errors
const handleErrors = (error: any) => {
    // Your error handling code here
};
</script>

<template>
    <BaseForm
        ref="baseFormRef"
        :model="props.model"
        :isCreate="props.isCreate"
        :modelPath="model_path"
        :formTitle="'Your Form Title'"
        :permissions="{
            crear: props.crear,
            editar: props.editar,
            eliminar: props.eliminar
        }"
        @submit="handleSubmit"
    >
        <template #form-fields>
            <!-- Add your form fields here -->
            <FormField
                type="text"
                label="Field 1"
                name="field1"
                v-model="form.field1"
                placeholder="Enter field 1"
                :required="true"
                :error="datas.field1Error"
                animationDelay="0.1s"
            />
            
            <!-- Add more form fields as needed -->
        </template>
    </BaseForm>
</template>
```

### Step 4: Update the Application's CSS

To ensure that the form styles are applied correctly, you may need to update the application's CSS. Here are some tips:

1. **Add the form styles to the application's main CSS file**: This will ensure that the styles are available to all components.
2. **Use the form classes in your components**: Use the form classes (e.g., `form-container`, `form-card`, etc.) in your components to ensure consistent styling.
3. **Customize the styles as needed**: Customize the styles to match your application's design.

## Best Practices

1. **Use the BaseForm component for all forms**: Use the BaseForm component for all forms to ensure a consistent layout.
2. **Use the FormField component for all form fields**: Use the FormField component for all form fields to ensure consistent styling.
3. **Handle errors properly**: Use the error props to display error messages for form fields.
4. **Use animation delays**: Use animation delays to create staggered animations for form fields.
5. **Clear errors on submit**: Clear all error fields before submitting the form to ensure a clean state.
6. **Use the BaseForm reference**: Use the BaseForm reference to control the loading state of the form.

## Troubleshooting

If you encounter any issues with the form components, check the following:

1. **Import paths**: Make sure the import paths are correct.
2. **Props**: Make sure all required props are provided.
3. **v-model**: Make sure the v-model binding is correct.
4. **Error handling**: Make sure error handling is properly implemented.

## Conclusion

By following this guide, you should be able to implement the new form components across the application. This will provide a consistent look and feel for all forms, while also making it easier to create and maintain forms.

If you have any questions or need further assistance, please refer to the `README.md` file or contact the development team.
