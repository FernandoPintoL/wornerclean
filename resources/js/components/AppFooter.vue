<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
const visitCount = ref(0);
const moduleVisits = ref<Record<string, number>>({});
const showAllVisits = ref(false);
const currentModule = computed(() => {
  // Extract the module name from the current URL
  const url = window.location.pathname;
  if (url === '/') return 'home';
  if (url.startsWith('/dashboard')) return 'dashboard';

  // Extract the first segment of the path (e.g., /cliente/1 -> cliente)
  const segments = url.split('/').filter(Boolean);
  return segments.length > 0 ? segments[0] : 'global';
});

// Function to increment the visit count for the current module
const incrementVisitCount = async () => {
  try {
    const response = await axios.post('/api/page-visits/increment', {
      module: currentModule.value
    });
    visitCount.value = response.data.count;

    // Update the module visits
    fetchAllVisitCounts();
  } catch (error) {
    console.error('Error incrementing visit count:', error);
  }
};

// Function to fetch all visit counts
const fetchAllVisitCounts = async () => {
  try {
    const response = await axios.get('/api/page-visits/all');
    moduleVisits.value = response.data.counts;
  } catch (error) {
    console.error('Error fetching visit counts:', error);
  }
};

onMounted(() => {
  // Increment the visit count for the current module when the component is mounted
  incrementVisitCount();
});
</script>

<template>
  <footer class="w-full py-4 px-6 bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="container mx-auto flex justify-between items-center">
      <div class="text-sm text-gray-600 dark:text-gray-400">
        &copy; {{ new Date().getFullYear() }} Worner Clean. Todos los derechos reservados al Tecnologia Web / Grupo18SC.
      </div>
      <div class="text-sm text-gray-500 dark:text-gray-500">
        <div class="flex items-center">
          <span class="mr-2">Visitas {{ currentModule }}: {{ visitCount }}</span>
          <button
            @click="showAllVisits = !showAllVisits"
            class="text-blue-500 hover:text-blue-700 text-xs"
            v-if="Object.keys(moduleVisits).length > 0"
          >
            {{ showAllVisits ? 'Ocultar detalles' : 'Ver detalles' }}
          </button>
        </div>
        <div v-if="showAllVisits" class="mt-2 text-xs">
          <div v-for="(count, module) in moduleVisits" :key="module" class="flex justify-between">
            <span class="font-medium">{{ module }}:</span>
            <span>{{ count }}</span>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
