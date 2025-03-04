<script setup lang="ts">
const props = defineProps<{
  id: string;
  min: number;
  max: number;
  labelText: string;
  hasError: boolean;
  placeholder?: string;
}>();

const model = defineModel<number>({
  required: true,
});

const decrement = (): void => {
  if (model.value <= props.min) {
    model.value = props.max;
  } else {
    model.value--;
  }
};

const increment = (): void => {
  if (model.value >= props.max) {
    model.value = props.min;
  } else {
    model.value++;
  }
};
</script>

<template>
  <label :for="`${id}-label`" class="block mb-2 text-sm font-medium text-gray-900">
    {{ labelText }}
  </label>
  <div class="relative flex items-center max-w-[8rem]">
    <button
        type="button"
        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none"
        @click="decrement"
    >
      <svg
          class="w-3 h-3 text-gray-900"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 18 2"
      >
        <path
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M1 1h16"
        />
      </svg>
    </button>
    <input
        :id="`${id}-input`"
        type="text"
        v-model="model"
        class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5"
        :placeholder
        :maxlength="max.toString().length"
        required
    />
    <button
        type="button"
        class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none"
        @click="increment"
    >
      <svg
          class="w-3 h-3 text-gray-900"
          aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 18 18"
      >
        <path
            stroke="currentColor"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9 1v16M1 9h16"
        />
      </svg>
    </button>
  </div>
  <p
      id="helper-text-explanation"
      class="mt-2 text-sm text-gray-500"
      :class="{ 'text-red-700': hasError }"
  >
    Please select a number from {{ min }} to {{ max }}.
  </p>
</template>
