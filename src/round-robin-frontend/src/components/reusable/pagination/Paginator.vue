<script setup lang="ts">
import type { PaginatedEntries } from '../../../types/pagination.type.ts';

defineProps<{
  data: PaginatedEntries<any>
}>();

defineEmits<{
  pageChange: [page: number]
}>();
</script>

<template>
  <div v-if="data.entries.length > 0 && data.pages > 1" class="flex flex-col items-center">
    <span class="text-sm text-gray-700">
      Showing <span class="font-semibold text-gray-900">{{ data.from }}</span>
      to <span class="font-semibold text-gray-900">{{ data.to }}</span> of
      <span class="font-semibold text-gray-900">{{ data.total }}</span>
      Entries
    </span>
    <div class="inline-flex mt-2 xs:mt-0">
      <button
          @click="$emit('pageChange',data.page - 1)"
          class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white rounded-s"
          :class="data.page === 1 ? 'cursor-not-allowed bg-gray-700' : 'cursor-pointer bg-gray-800 hover:bg-gray-900'"
      >
        <svg
            class="w-3.5 h-3.5 me-2 rtl:rotate-180"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 14 10"
        >
          <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 5H1m0 0 4 4M1 5l4-4"
          />
        </svg>
        Prev
      </button>
      <button
          @click="$emit('pageChange', data.page + 1)"
          class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white border-0 border-s border-gray-700 rounded-e"
          :class="data.pages === data.page ? 'cursor-not-allowed bg-gray-700' : 'cursor-pointer bg-gray-800 hover:bg-gray-900'"
      >
        Next
        <svg
            class="w-3.5 h-3.5 ms-2 rtl:rotate-180"
            aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 14 10"
        >
          <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M1 5h12m0 0L9 1m4 4L9 9"
          />
        </svg>
      </button>
    </div>
  </div>
</template>
