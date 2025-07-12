<script setup lang="ts">
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';

// Define props for the component
const props = defineProps({
    // Field type (text, email, password, select, textarea, etc.)
    type: {
        type: String,
        default: 'text'
    },
    // Field label
    label: {
        type: String,
        required: true
    },
    // Field name (used for id and name attributes)
    name: {
        type: String,
        required: true
    },
    // Field value (v-model)
    modelValue: {
        type: [String, Number, Boolean, Array, Object],
        default: ''
    },
    // Field placeholder
    placeholder: {
        type: String,
        default: ''
    },
    // Whether the field is required
    required: {
        type: Boolean,
        default: false
    },
    // Error message
    error: {
        type: String,
        default: ''
    },
    // Options for select fields
    options: {
        type: Array,
        default: () => []
    },
    // Whether the field should span the full width (for selects, textareas, etc.)
    fullWidth: {
        type: Boolean,
        default: false
    },
    // Animation delay for staggered animations
    animationDelay: {
        type: String,
        default: '0s'
    }
});

// Define emits
const emit = defineEmits(['update:modelValue']);

// Computed classes for the label
const labelClasses = computed(() => [
    'mb-2 block text-sm font-medium transition-colors duration-200',
    props.error
        ? 'text-red-600 dark:text-red-400'
        : 'text-gray-700 dark:text-gray-300'
]);

// Computed classes for the input
const inputClasses = computed(() => [
    'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
    props.error
        ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
        : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500'
]);

// Handle input changes
const handleInput = (event) => {
    emit('update:modelValue', event.target.value);
};
</script>

<template>
    <div
        :class="[
            'form-group animate-fadeIn',
            fullWidth ? 'col-span-1 md:col-span-2' : ''
        ]"
        :style="{ 'animation-delay': animationDelay }"
    >
        <label :for="name" :class="labelClasses">
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </label>

        <div class="relative">
            <!-- Text, Email, Password, Number inputs -->
            <input
                v-if="['text', 'email', 'password', 'number', 'date', 'time', 'tel'].includes(type)"
                :type="type"
                :id="name"
                :name="name"
                :value="modelValue"
                @input="handleInput"
                :placeholder="placeholder"
                :required="required"
                :class="inputClasses"
            />

            <!-- Select input -->
            <template v-else-if="type === 'select'">
                <select
                    :id="name"
                    :name="name"
                    :value="modelValue"
                    @input="handleInput"
                    :required="required"
                    :class="[...inputClasses, 'appearance-none']"
                >
                    <option value="" disabled selected>{{ placeholder }}</option>
                    <option
                        v-for="(option, index) in options"
                        :key="index"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </template>

            <!-- Textarea input -->
            <textarea
                v-else-if="type === 'textarea'"
                :id="name"
                :name="name"
                :value="modelValue"
                @input="handleInput"
                :placeholder="placeholder"
                :required="required"
                :class="inputClasses"
                rows="4"
            ></textarea>

            <!-- Error icon -->
            <div
                v-if="error"
                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
                :class="{ 'right-8': type === 'select' }"
            >
                <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Error message -->
        <InputError v-if="error" class="mt-2" :message="error" />
    </div>
</template>

<style scoped>
/* Component-specific styles can be added here if needed */
</style>
