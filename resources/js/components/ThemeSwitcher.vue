<script setup lang="ts">
import { ref, onMounted } from 'vue';
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
  Eye
} from 'lucide-vue-next';

// Get the appearance composable
const {
  appearance,
  fontSize,
  contrast,
  updateAppearance,
  updateFontSize,
  updateContrast
} = useAppearance();

// Dropdown state
const isOpen = ref(false);

// Toggle dropdown
const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

// Close dropdown when clicking outside
const closeDropdown = (event: MouseEvent) => {
  const target = event.target as HTMLElement;
  if (!target.closest('#theme-switcher-dropdown') && !target.closest('#theme-switcher-button')) {
    isOpen.value = false;
  }
};

// Add click event listener to close dropdown when clicking outside
onMounted(() => {
  document.addEventListener('click', closeDropdown);
});

// Theme options
const themeOptions = [
  { value: 'light', label: 'Claro', icon: Sun },
  { value: 'dark', label: 'Oscuro', icon: Moon },
  { value: 'system', label: 'Sistema', icon: Monitor },
  { value: 'auto', label: 'Auto (Hora)', icon: Clock },
  { value: 'children', label: 'Niños', icon: Baby },
  { value: 'youth', label: 'Jóvenes', icon: Users },
  { value: 'adults', label: 'Adultos', icon: UserCog },
];

// Font size options
const fontSizeOptions = [
  { value: 'normal', label: 'Normal' },
  { value: 'large', label: 'Grande' },
  { value: 'x-large', label: 'Extra Grande' },
];

// Contrast options
const contrastOptions = [
  { value: 'normal', label: 'Normal' },
  { value: 'high', label: 'Alto Contraste' },
];

// Get current theme icon
const getCurrentThemeIcon = () => {
  const option = themeOptions.find(option => option.value === appearance.value);
  return option ? option.icon : Sun;
};
</script>

<template>
  <div class="relative">
    <!-- Theme switcher button -->
    <button
      id="theme-switcher-button"
      type="button"
      @click.stop="toggleDropdown"
      class="flex items-center justify-center p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700"
      aria-expanded="false"
    >
      <span class="sr-only">Cambiar tema</span>
      <component :is="getCurrentThemeIcon()" class="w-5 h-5" />
    </button>

    <!-- Theme switcher dropdown -->
    <div
      id="theme-switcher-dropdown"
      v-show="isOpen"
      class="absolute right-0 z-50 mt-2 w-72 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-gray-700"
      role="menu"
      aria-orientation="vertical"
      aria-labelledby="theme-switcher-button"
      tabindex="-1"
    >
      <div class="py-1" role="none">
        <!-- Theme section -->
        <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">
          Tema
        </div>
        <div class="grid grid-cols-2 gap-2 p-2">
          <button
            v-for="option in themeOptions"
            :key="option.value"
            @click="updateAppearance(option.value); isOpen = false"
            :class="[
              'flex items-center gap-2 px-3 py-2 text-sm rounded-md transition-colors',
              appearance === option.value
                ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
            ]"
          >
            <component :is="option.icon" class="w-4 h-4" />
            <span>{{ option.label }}</span>
          </button>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

        <!-- Font size section -->
        <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 flex items-center gap-2">
          <Type class="w-3 h-3" /> Tamaño de Texto
        </div>
        <div class="p-2">
          <div class="flex items-center justify-between gap-2">
            <button
              v-for="option in fontSizeOptions"
              :key="option.value"
              @click="updateFontSize(option.value); isOpen = false"
              :class="[
                'flex-1 px-3 py-2 text-sm rounded-md transition-colors',
                fontSize === option.value
                  ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              {{ option.label }}
            </button>
          </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

        <!-- Contrast section -->
        <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400 flex items-center gap-2">
          <Eye class="w-3 h-3" /> Contraste
        </div>
        <div class="p-2">
          <div class="flex items-center justify-between gap-2">
            <button
              v-for="option in contrastOptions"
              :key="option.value"
              @click="updateContrast(option.value); isOpen = false"
              :class="[
                'flex-1 px-3 py-2 text-sm rounded-md transition-colors',
                contrast === option.value
                  ? 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900 dark:text-indigo-100'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              {{ option.label }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Animation for dropdown */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

#theme-switcher-dropdown {
  animation: fadeIn 0.2s ease-out;
}
</style>
