<script setup lang="ts">
// Define props to allow customization of the loader
const props = defineProps({
    size: {
        type: String,
        default: 'md', // sm, md, lg
    },
    text: {
        type: String,
        default: 'Cargando...',
    },
    fullScreen: {
        type: Boolean,
        default: false,
    },
    showText: {
        type: Boolean,
        default: true,
    },
});

// Compute the size classes based on the size prop
const sizeClasses = {
    sm: 'h-4 w-4',
    md: 'h-6 w-6',
    lg: 'h-8 w-8',
};

const spinnerSize = sizeClasses[props.size] || sizeClasses.md;
</script>

<template>
    <div :class="[
        'loader-container transition-all duration-300',
        fullScreen ? 'fixed inset-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm' : 'flex'
    ]">
        <div :class="[
            'flex flex-col items-center justify-center p-4 animate-fadeIn',
            fullScreen ? 'h-full w-full' : '',
        ]">
            <div :class="[
                'flex flex-col items-center justify-center',
                fullScreen ? 'bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6' : ''
            ]">
                <!-- Spinner with gradient and improved animation -->
                <div class="relative">
                    <!-- Background circle -->
                    <svg :class="['animate-spin', spinnerSize]" viewBox="0 0 24 24">
                        <circle
                            class="opacity-25 stroke-gray-300 dark:stroke-gray-600"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke-width="4"
                            fill="none"
                        ></circle>
                        <!-- Animated gradient spinner -->
                        <circle
                            class="spinner-path opacity-75"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke-width="4"
                            stroke-linecap="round"
                            fill="none"
                            stroke-dasharray="63"
                            stroke-dashoffset="63"
                        ></circle>
                    </svg>
                </div>

                <!-- Loading text with proper dark mode support -->
                <span
                    v-if="showText"
                    class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors duration-200"
                >
                    {{ text }}
                </span>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Spinner animation */
.spinner-path {
    animation: dash 1.5s ease-in-out infinite;
}

@keyframes dash {
    0% {
        stroke-dashoffset: 63;
    }
    50% {
        stroke-dashoffset: 0;
    }
    100% {
        stroke-dashoffset: -63;
    }
}

/* Fade in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

/* Define gradient for the spinner */
svg {
    --spinner-gradient-from: #4f46e5;
    --spinner-gradient-to: #8b5cf6;
}

.dark svg {
    --spinner-gradient-from: #6366f1;
    --spinner-gradient-to: #a78bfa;
}

.spinner-path {
    stroke: var(--spinner-gradient-from);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .loader-container.fixed .flex {
        padding: 1rem;
    }
}
</style>
