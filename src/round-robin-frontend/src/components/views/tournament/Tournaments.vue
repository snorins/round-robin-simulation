<script setup lang="ts">
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { ApiService } from '../../../services/api.service.ts';
import type { Tournament } from '../../../types/tournament.type.ts';
import type { PaginatedEntries } from '../../../types/pagination.type.ts';
import Paginator from '../../reusable/pagination/Paginator.vue';
import { API_ENDPOINT } from '../../../constants/api.constant.ts';

const route = useRoute();
const router = useRouter();

const page = ref<number>(Number(route.query.page) || 1);
const paginatedTournaments = ref<PaginatedEntries<Tournament>>();

const loadData = (page: number): void => {
  if (page < 1 || (paginatedTournaments.value && page > paginatedTournaments.value.pages)) {
    return;
  }

  ApiService
      .get<PaginatedEntries<Tournament>>(API_ENDPOINT.tournaments + `?page=${page}`)
      .then(({ data }) => paginatedTournaments.value = data);

  router.push({ query: { page } });
};

loadData(page.value);
</script>

<template>
  <div v-if="!paginatedTournaments || paginatedTournaments.entries.length < 1">
    Could not find any tournaments...
  </div>
  <div v-else class="relative overflow-x-auto min-h-[570px]">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr>
        <th scope="col" class="px-6 py-3">Id</th>
        <th scope="col" class="px-6 py-3">Name</th>
        <th scope="col" class="px-6 py-3">Team Count</th>
      </tr>
      </thead>
      <tbody>
      <tr
          class="bg-white border-b border-gray-200 cursor-pointer hover:bg-gray-50"
          v-for="tournament in paginatedTournaments.entries"
          :key="tournament.id"
          @click="$router.push(`/tournaments/${tournament.id}`)"
      >
        <th class="px-6 py-4">{{ tournament.id }}</th>
        <td class="px-6 py-4">{{ tournament.name }}</td>
        <td class="px-6 py-4">{{ tournament.teamCount }}</td>
      </tr>
      </tbody>
    </table>
  </div>

  <Paginator
      v-if="paginatedTournaments"
      :data="paginatedTournaments"
      @page-change="loadData"
  />
</template>
