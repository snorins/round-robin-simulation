<script setup lang="ts">
import { ref } from 'vue';
import { ApiService } from '../../../services/api.service.ts';
import type { Team } from '../../../types/team.type.ts';
import type { PaginatedEntries } from '../../../types/pagination.type.ts';
import { useRoute, useRouter } from 'vue-router';
import Paginator from '../../reusable/pagination/Paginator.vue';
import { API_ENDPOINT } from '../../../constants/api.constant.ts';

const route = useRoute();
const router = useRouter();

const page = ref<number>(Number(route.query.page) || 1);
const paginatedTeams = ref<PaginatedEntries<Team>>();

const fetchTeams = (page: number): void => {
  if (page < 1 || (paginatedTeams.value && page > paginatedTeams.value.pages)) {
    return;
  }

  ApiService
      .get<PaginatedEntries<Team>>(API_ENDPOINT.teams + `?page=${page}`)
      .then(({ data }) => paginatedTeams.value = data);

  router.push({ query: { page } });
};

fetchTeams(page.value);
</script>

<template>
  <div v-if="!paginatedTeams || paginatedTeams.entries.length < 1">
    Could not find any teams...
  </div>
  <div v-else class="relative overflow-x-auto min-h-[570px]">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr>
        <th scope="col" class="px-6 py-3">Id</th>
        <th scope="col" class="px-6 py-3">Name</th>
        <th scope="col" class="px-6 py-3">Total Wins</th>
        <th scope="col" class="px-6 py-3">Total Score</th>
      </tr>
      </thead>
      <tbody>
      <tr
          class="bg-white border-b border-gray-200"
          v-for="team in paginatedTeams.entries"
          :key="team.id"
      >
        <th class="px-6 py-4">{{ team.id }}</th>
        <td class="px-6 py-4">{{ team.name }}</td>
        <td class="px-6 py-4">{{ team.wins }}</td>
        <td class="px-6 py-4">{{ team.score }}</td>
      </tr>
      </tbody>
    </table>
  </div>

  <Paginator
      v-if="paginatedTeams"
      :data="paginatedTeams"
      @page-change="fetchTeams"
  />
</template>
