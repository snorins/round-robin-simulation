<script setup lang="ts">
import NavLink from '../reusable/link/NavLink.vue';
</script>

<template>
  <nav class="mx-auto max-w-[39rem] bg-white border-gray-200">
    <ul class="flex justify-between py-8 border-bottom">
      <li>
        <NavLink to="/" text="Simulate" />
      </li>
      <li>
        <NavLink
            to="/tournaments"
            text="Tournaments"
            :classes="{ 'text-blue-700': $route.path.includes('tournaments') }"
        />
      </li>
      <li>
        <NavLink to="/teams" text="Teams" />
      </li>
      <li>
        <NavLink to="/statistics" text="Statistics" />
      </li>
    </ul>
  </nav>

  <section class="flex flex-col gap-8 mx-auto max-w-[39rem] my-16">
    <slot />
  </section>
</template>
