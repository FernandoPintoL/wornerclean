<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { useAppearance } from '@/composables/useAppearance';
import {
  Sun,
  Moon,
  Monitor,
  Clock,
  Baby,
  Users,
  UserCog,
  Type,
  Eye,
  Check
} from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';

// Get the appearance composable
const {
  appearance,
  fontSize,
  contrast,
  updateAppearance,
  updateFontSize,
  updateContrast
} = useAppearance();

// Breadcrumbs for the page
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'APARIENCIA',
    href: '/appearance',
  },
];

// Theme options
const themeOptions = [
  { value: 'light', label: 'Claro', icon: Sun, description: 'Tema claro estándar para uso general.' },
  { value: 'dark', label: 'Oscuro', icon: Moon, description: 'Tema oscuro para reducir la fatiga visual, especialmente en entornos con poca luz.' },
  { value: 'system', label: 'Sistema', icon: Monitor, description: 'Sigue la preferencia de tema de su sistema (claro u oscuro).' },
  { value: 'auto', label: 'Auto (Hora)', icon: Clock, description: 'Cambia automáticamente entre claro (día) y oscuro (noche) según la hora local.' },
  { value: 'children', label: 'Niños', icon: Baby, description: 'Tema colorido con esquinas redondeadas y texto más grande, diseñado para niños.' },
  { value: 'youth', label: 'Jóvenes', icon: Users, description: 'Tema moderno y vibrante con una barra lateral oscura, diseñado para adolescentes y jóvenes adultos.' },
  { value: 'adults', label: 'Adultos', icon: UserCog, description: 'Tema profesional y limpio con elementos de diseño sutiles, diseñado para adultos.' },
];

// Font size options
const fontSizeOptions = [
  { value: 'normal', label: 'Normal', description: 'Tamaño de texto estándar.' },
  { value: 'large', label: 'Grande', description: 'Texto más grande para mejor legibilidad.' },
  { value: 'x-large', label: 'Extra Grande', description: 'Texto muy grande para máxima legibilidad.' },
];

// Contrast options
const contrastOptions = [
  { value: 'normal', label: 'Normal', description: 'Contraste estándar.' },
  { value: 'high', label: 'Alto Contraste', description: 'Mayor contraste para mejor visibilidad.' },
];

// Preview text for font size and contrast
const previewText = ref('Este es un texto de ejemplo para mostrar cómo se ve el tamaño de fuente y el contraste seleccionados.');
</script>

<template>
  <Head title="Apariencia" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4 sm:p-6 lg:p-8 transition-all duration-300">
      <div class="w-full max-w-7xl mx-auto">
        <!-- Main content card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 transition-all duration-300 animate-fadeIn">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Configuración de Apariencia</h1>

          <!-- Theme selection section -->
          <section class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
              <Sun class="h-5 w-5 text-amber-500" />
              <Moon class="h-5 w-5 text-indigo-400" />
              <span>Tema</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div
                v-for="option in themeOptions"
                :key="option.value"
                @click="updateAppearance(option.value)"
                :class="[
                  'relative p-4 rounded-lg border-2 transition-all duration-200 cursor-pointer',
                  appearance === option.value
                    ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-400'
                    : 'border-gray-200 hover:border-indigo-300 dark:border-gray-700 dark:hover:border-indigo-600'
                ]"
              >
                <div class="flex items-start gap-3">
                  <div class="flex-shrink-0 p-2 rounded-full bg-indigo-100 dark:bg-indigo-900/30">
                    <component :is="option.icon" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
                  </div>
                  <div class="flex-grow">
                    <h3 class="font-medium text-gray-900 dark:text-white">{{ option.label }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ option.description }}</p>
                  </div>
                  <div v-if="appearance === option.value" class="absolute top-2 right-2 text-indigo-600 dark:text-indigo-400">
                    <Check class="h-5 w-5" />
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Font size section -->
          <section class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
              <Type class="h-5 w-5 text-indigo-500" />
              <span>Tamaño de Texto</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
              <div
                v-for="option in fontSizeOptions"
                :key="option.value"
                @click="updateFontSize(option.value)"
                :class="[
                  'relative p-4 rounded-lg border-2 transition-all duration-200 cursor-pointer',
                  fontSize === option.value
                    ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-400'
                    : 'border-gray-200 hover:border-indigo-300 dark:border-gray-700 dark:hover:border-indigo-600'
                ]"
              >
                <div class="flex items-start gap-3">
                  <div class="flex-grow">
                    <h3 class="font-medium text-gray-900 dark:text-white">{{ option.label }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ option.description }}</p>
                  </div>
                  <div v-if="fontSize === option.value" class="absolute top-2 right-2 text-indigo-600 dark:text-indigo-400">
                    <Check class="h-5 w-5" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Font size preview -->
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
              <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Vista previa:</h4>
              <p class="text-gray-900 dark:text-white">{{ previewText }}</p>
            </div>
          </section>

          <!-- Contrast section -->
          <section class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center gap-2">
              <Eye class="h-5 w-5 text-indigo-500" />
              <span>Contraste</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
              <div
                v-for="option in contrastOptions"
                :key="option.value"
                @click="updateContrast(option.value)"
                :class="[
                  'relative p-4 rounded-lg border-2 transition-all duration-200 cursor-pointer',
                  contrast === option.value
                    ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-400'
                    : 'border-gray-200 hover:border-indigo-300 dark:border-gray-700 dark:hover:border-indigo-600'
                ]"
              >
                <div class="flex items-start gap-3">
                  <div class="flex-grow">
                    <h3 class="font-medium text-gray-900 dark:text-white">{{ option.label }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ option.description }}</p>
                  </div>
                  <div v-if="contrast === option.value" class="absolute top-2 right-2 text-indigo-600 dark:text-indigo-400">
                    <Check class="h-5 w-5" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Contrast preview -->
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
              <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Vista previa:</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                  <p class="text-gray-900 dark:text-white">Texto sobre fondo claro</p>
                </div>
                <div class="p-3 bg-gray-900 dark:bg-white rounded border border-gray-700 dark:border-gray-300">
                  <p class="text-white dark:text-gray-900">Texto sobre fondo oscuro</p>
                </div>
              </div>
            </div>
          </section>

          <!-- Information section -->
          <section class="mt-10 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <h2 class="text-lg font-medium text-blue-800 dark:text-blue-300 mb-2">Información</h2>
            <p class="text-blue-700 dark:text-blue-400 text-sm">
              Estas configuraciones se guardarán en su navegador y se aplicarán cada vez que visite la aplicación.
              Puede cambiarlas en cualquier momento desde esta página o usando el selector de tema en la barra de navegación.
            </p>
          </section>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Fade in animation */
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
  animation: fadeIn 0.4s ease-in-out;
}
</style>
