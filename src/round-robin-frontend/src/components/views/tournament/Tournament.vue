<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref } from 'vue';
import { ApiService } from '../../../services/api.service.ts';
import type { TournamentViewResponse } from '../../../types/tournament.type.ts';
import InfoAlert from '../../reusable/alert/InfoAlert.vue';
import { API_ENDPOINT } from '../../../constants/api.constant.ts';
import Link from '../../reusable/link/Link.vue';
import GenericTable from '../../reusable/table/GenericTable.vue';

const route = useRoute();
const router = useRouter();

const tournamentId = Number(route.params.id);
const isLoading = ref<boolean>(true);

const tournamentView = ref<TournamentViewResponse>();

ApiService
    .get<TournamentViewResponse>(`${API_ENDPOINT.tournament}/${tournamentId}`)
    .then(({ data }) => tournamentView.value = data)
    .finally(() => isLoading.value = false);

const highlightTeamGames = (teamId: number): void => {
  router.push({
    path: `/tournaments/${tournamentId}/games`,
    query: { teamId },
  });
};
</script>

<template>
  <div v-if="isLoading">Loading...</div>
  <div v-else-if="!tournamentView">Could not find this tournament!</div>
  <div v-else>
    <div class="flex justify-between">
      <p>Tournament — {{ tournamentView.name }}</p>
      <Link :to="`/tournaments/${tournamentId}/games`" text="Analyze games" />
    </div>
    <br>

    <InfoAlert>
      <span class="font-medium">Be aware that the ranking is determined by:</span>
      <ul class="mt-1.5 list-disc list-inside">
        <li>first and foremost how many times the team has won in total</li>
        <li>if teams have the same amount of wins — total score</li>
      </ul>
    </InfoAlert>

    <p class="mb-4 text-sm">Leaderboard</p>
    <div class="relative overflow-x-auto mb-8">
      <GenericTable :column-names="['Place', 'Team name', 'Wins', 'Total score']">
        <tr
            class="border-b border-gray-200 cursor-pointer hover:bg-gray-50"
            v-for="(team, index) in tournamentView.teams"
            :key="team.id"
            @click="highlightTeamGames(team.id)"
        >
          <td class="px-6 py-4">#{{ index + 1 }}</td>
          <td class="px-6 py-4">{{ team.name }}</td>
          <td class="px-6 py-4">{{ team.wins }}</td>
          <td class="px-6 py-4">{{ team.score }}</td>
        </tr>
      </GenericTable>
    </div>
  </div>
</template>
