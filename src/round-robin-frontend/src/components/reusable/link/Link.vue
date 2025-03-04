<script setup lang="ts">
defineProps<{
  to: string;
  text: string;
}>();
</script>

<template>
  <RouterLink :to class="block underline hover:no-underline">
    {{ text }}
  </RouterLink>
</template>
