<script setup lang="ts">
defineProps<{
  for: string;
  text: string;
}>();
</script>

<template>
  <label :for class="block mb-2 text-sm font-medium text-gray-900">
    {{ text }}
  </label>
</template>
