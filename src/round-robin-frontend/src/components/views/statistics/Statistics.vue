<script setup lang="ts">
import { ref } from 'vue';
import { ApiService } from '../../../services/api.service.ts';
import { API_ENDPOINT } from '../../../constants/api.constant.ts';
import type { Statistics } from '../../../types/statistics.type.ts';
import GenericTable from '../../reusable/table/GenericTable.vue';

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
      <GenericTable :column-names="['Tournament Name', 'Total Score']">
        <tr class="border-b border-gray-200" v-for="team in statistics.tournaments" :key="team.id">
          <td class="px-6 py-4">{{ team.name }}</td>
          <td class="px-6 py-4">{{ team.score }}</td>
        </tr>
      </GenericTable>
    </div>
  </div>

  <div v-if="statistics.teams.length > 0">
    <p class="mb-4 text-sm">Highest total score teams</p>
    <div class="relative overflow-x-auto">
      <GenericTable :column-names="['Team Name', 'Total Score']">
        <tr class="border-b border-gray-200" v-for="team in statistics.teams" :key="team.id">
          <td class="px-6 py-4">{{ team.name }}</td>
          <td class="px-6 py-4">{{ team.score }}</td>
        </tr>
      </GenericTable>
    </div>
  </div>
</template>
