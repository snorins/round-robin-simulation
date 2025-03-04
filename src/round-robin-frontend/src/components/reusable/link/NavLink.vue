<script setup lang="ts">
defineProps<{
  to: string;
  text: string;
  classes?: Record<string, boolean>;
}>();
</script>

<template>
  <RouterLink :to class="block hover:text-blue-700" :class="classes">
    {{ text }}
  </RouterLink>
</template>
