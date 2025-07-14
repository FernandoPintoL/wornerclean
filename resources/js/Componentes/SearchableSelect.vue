<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { Search, ChevronDown, ChevronUp, Check } from 'lucide-vue-next';

const props = defineProps({
  options: {
    type: Array,
    required: true,
    default: () => []
  },
  modelValue: {
    type: [String, Number, Object, null], // Permite también Object para evitar el warning si alguna vez se pasa un objeto
    default: ''
  },
  label: {
    type: String,
    default: 'Seleccionar'
  },
  placeholder: {
    type: String,
    default: 'Buscar...'
  },
  valueKey: {
    type: String,
    default: 'id'
  },
  labelKey: {
    type: String,
    default: 'name'
  },
  required: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue', 'change', 'create-new']);

const isOpen = ref(false);
const searchQuery = ref('');
const selectedOption = ref<OptionType | null>(null);
const dropdownRef = ref(null);

interface OptionType {
    [key: string]: any;
}

// Find the initially selected option
onMounted(() => {
    console.log(props.options);
  if (props.modelValue && Array.isArray(props.options) && props.options.length > 0) {
      const option = (props.options as OptionType[]).find(
          (opt) => opt && opt[props.valueKey] === props.modelValue
      );
      if (option) {
          selectedOption.value = option as OptionType;
      }
  }

  // Add click outside listener
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

// Filter options based on a search query
const filteredOptions = computed<OptionType[]>(() => {
    if (!searchQuery.value) return props.options as OptionType[];

    const query = searchQuery.value.toLowerCase();
    return (props.options as OptionType[]).filter(option =>
        option &&
        typeof option[props.labelKey as string] === 'string' &&
        option[props.labelKey as string].toLowerCase().includes(query)
    );
});

// Handle selection
const selectOption = (option : any) => {
  if (!option) return;

  selectedOption.value = option;
  emit('update:modelValue', option[props.valueKey] !== undefined ? option[props.valueKey] : null);
  emit('change', option);
  isOpen.value = false;
  searchQuery.value = '';
};

// Toggle dropdown
const toggleDropdown = () => {
    if (!props.disabled) {
        isOpen.value = !isOpen.value;
        if (isOpen.value) {
            searchQuery.value = '';
            // Focus a l'input de cerca al abrir
            setTimeout(() => {
                if (dropdownRef.value) {
                    const searchInput = (dropdownRef.value as HTMLElement).querySelector('input');
                    if (searchInput) (searchInput as HTMLInputElement).focus();
                }
            }, 100);
        }
    }
};

// Close dropdown when clicking outside
const handleClickOutside = (event: MouseEvent) => {
    if (
        dropdownRef.value &&
        !(dropdownRef.value as HTMLElement).contains(event.target as Node)
    ) {
        isOpen.value = false;
    }
};

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
  if (newValue === '' || newValue === null || newValue === undefined) {
    selectedOption.value = null;
    return;
  }

  if (props.options && props.options.length > 0) {
      const option = (props.options as OptionType[]).find(opt => opt && opt[props.valueKey] === newValue);
    if (option) {
      selectedOption.value = option;
    }
  }
});

// Get display text for the selected option
const selectedText = computed(() => {
  if (selectedOption.value && selectedOption.value[props.labelKey] !== undefined) {
    return selectedOption.value[props.labelKey];
  }
  return props.label;
});
</script>

<template>
  <div class="relative w-full dropdown-container" ref="dropdownRef">
    <!-- Select Button -->
    <button
      type="button"
      @click="toggleDropdown"
      :disabled="disabled"
      class="flex items-center justify-between w-full px-4 py-3 text-sm bg-white border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-300"
      :class="[
        error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300',
        disabled ? 'opacity-75 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-gray-600 hover:shadow-md'
      ]"
    >
      <span
        :class="{
          'text-gray-400 dark:text-gray-300': !selectedOption,
          'text-gray-900 dark:text-white font-medium': selectedOption
        }"
      >{{ selectedText }}</span>
      <div class="flex items-center">
        <div v-if="selectedOption" class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></div>
        <ChevronDown v-if="!isOpen" class="w-4 h-4 ml-2 text-indigo-500" />
        <ChevronUp v-else class="w-4 h-4 ml-2 text-indigo-500" />
      </div>
    </button>

    <!-- Backdrop overlay -->
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 z-[999] backdrop-blur-sm transition-opacity duration-300" @click="isOpen = false"></div>

    <!-- Dropdown Menu -->
    <div
      v-if="isOpen"
      class="dropdown-menu absolute left-0 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl z-[1000] dark:bg-gray-700 dark:border-gray-600 animate-fadeIn overflow-hidden"
    >
      <!-- Search Input -->
      <div class="relative p-3 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800">
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
          <Search class="w-4 h-4 text-indigo-500 dark:text-indigo-400" />
        </div>
        <input
          type="text"
          v-model="searchQuery"
          :placeholder="placeholder"
          class="w-full pl-10 pr-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-300 shadow-sm"
        />
      </div>

      <!-- Options List -->
      <ul class="py-1 max-h-60 overflow-y-auto">
        <template v-if="filteredOptions.length === 0">
          <li class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
            No se encontraron resultados
          </li>
          <li
            @click="$emit('create-new', searchQuery)"
            class="flex items-center px-4 py-3 text-sm cursor-pointer bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-medium transition-colors duration-200"
          >
            <span class="flex-grow flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Crear nuevo
            </span>
          </li>
        </template>
        <li
          v-for="option in filteredOptions"
          :key="option[valueKey]"
          @click="selectOption(option)"
          class="flex items-center px-4 py-3 text-sm cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors duration-200 border-b border-gray-100 dark:border-gray-700 last:border-0"
          :class="{
            'bg-indigo-100 dark:bg-indigo-900/30': selectedOption && option[valueKey] === selectedOption[valueKey],
            'text-gray-900 dark:text-white': true
          }"
        >
          <span class="flex-grow">{{ option[labelKey] }}</span>
          <Check
            v-if="selectedOption && option[valueKey] === selectedOption[valueKey]"
            class="w-4 h-4 text-indigo-500"
          />
        </li>
      </ul>
    </div>
    <div v-if="error" class="text-xs text-red-500 mt-1">{{ error }}</div>
  </div>
</template>
<style scoped>
.dropdown-container {
    position: relative;
    isolation: isolate; /* Crea un nuevo contexto de apilamiento */
}

.dropdown-menu {
    position: absolute !important;
    top: calc(100% + 5px) !important;
    left: 0 !important;
    right: 0 !important;
    transform: none !important;
    will-change: transform;
    max-height: 300px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
    z-index: 1000 !important;
    animation: dropdownFadeIn 0.2s ease-out;
}

@keyframes dropdownFadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
