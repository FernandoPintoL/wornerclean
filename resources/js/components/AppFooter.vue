<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

const visitCount = ref(0);


// Function to increment the visit count
const incrementVisitCount = async () => {
  try {
    const response = await axios.post('/api/page-visits/increment');
    visitCount.value = response.data.count;
  } catch (error) {
    console.error('Error incrementing visit count:', error);
  }
};

onMounted(() => {
  // Increment the visit count when the component is mounted
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
        Visitas: {{ visitCount }}
      </div>
    </div>
  </footer>
</template>
