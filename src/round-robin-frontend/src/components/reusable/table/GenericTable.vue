<script setup lang="ts">
defineProps<{
  columnNames: string[];
}>();
</script>

<template>
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
    <tr>
      <th scope="col" class="px-6 py-3" v-for="name in columnNames">
        {{ name }}
      </th>
    </tr>
    </thead>

    <tbody>
      <slot />
    </tbody>
  </table>
</template>
