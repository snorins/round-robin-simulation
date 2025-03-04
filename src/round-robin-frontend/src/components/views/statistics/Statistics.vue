<script setup lang="ts">
import { ref } from 'vue';
import { ApiService } from '../../../services/api.service.ts';
import { API_ENDPOINT } from '../../../constants/api.constant.ts';
import type { Statistics } from '../../../types/statistics.type.ts';

const statistics = ref<Statistics>({
  tournaments: [],
  teams: [],
});

ApiService
    .get<Statistics>(API_ENDPOINT.statistics)
    .then(({ data }) => statistics.value = data);
</script>

<template>
  <p v-if="statistics.tournaments.length < 1 && statistics.teams.length < 1">
    No stats to show right now...
  </p>

  <div v-if="statistics.tournaments.length > 0">
    <p class="mb-4 text-sm">Highest total score tournaments</p>
    <div class="relative overflow-x-auto">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
          <th scope="col" class="px-6 py-3">Tournament name</th>
          <th scope="col" class="px-6 py-3">Total score</th>
        </tr>
        </thead>
        <tbody>
        <tr
            class="border-b border-gray-200"
            v-for="team in statistics.tournaments"
            :key="team.id"
        >
          <td class="px-6 py-4">{{ team.name }}</td>
          <td class="px-6 py-4">{{ team.score }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div v-if="statistics.teams.length > 0">
    <p class="mb-4 text-sm">Highest total score teams</p>
    <div class="relative overflow-x-auto">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
          <th scope="col" class="px-6 py-3">Team name</th>
          <th scope="col" class="px-6 py-3">Total score</th>
        </tr>
        </thead>
        <tbody>
        <tr
            class="border-b border-gray-200"
            v-for="team in statistics.teams"
            :key="team.id"
        >
          <td class="px-6 py-4">{{ team.name }}</td>
          <td class="px-6 py-4">{{ team.score }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
