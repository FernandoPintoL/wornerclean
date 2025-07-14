<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { useAssets } from '@/composables/useAssets';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();

const { asset } = useAssets();
</script>

<template>
    <img :src="asset('/assets/logo.png')" alt="WornerClean Logo" :class="className" />
</template>
