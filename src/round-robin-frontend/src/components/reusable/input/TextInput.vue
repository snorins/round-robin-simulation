<script setup lang="ts">
defineProps<{
  id: string;
  errorMessage: string;
  placeholder?: string;
}>();

const model = defineModel<string>({ required: true });
</script>

<template>
  <input
      v-model="model"
      :id
      :placeholder
      type="text"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
      required
  />
  <p v-if="errorMessage" class="mt-2 text-sm text-red-700">
    {{ errorMessage }}
  </p>
</template>
