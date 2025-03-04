<script setup lang="ts">
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import { ApiService } from '../../../services/api.service.ts';
import type { TournamentView } from '../../../types/tournament.type.ts';
import Link from '../../reusable/link/Link.vue';
import { API_ENDPOINT } from '../../../constants/api.constant.ts';

const route = useRoute();

const tournamentId = Number(route.params.id);
const highlightTeamId = Number(route.query.teamId);
const isLoading = ref<boolean>(true);

const tournamentStructure = ref<TournamentView>();

ApiService
    .get<TournamentView>(`${API_ENDPOINT.tournament}/${tournamentId}`)
    .then(({ data }) => tournamentStructure.value = data)
    .finally(() => isLoading.value = false);
</script>

<template>
  <div v-if="isLoading">Loading...</div>
  <div v-else-if="!tournamentStructure">Could not find tournament games!</div>
  <div v-else class="flex flex-col gap-6">
    <div class="flex justify-end">
      <Link :to="`/tournaments/${tournamentId}`" text="Back to leaderboard" />
    </div>
    <div v-for="round in tournamentStructure.rounds" :key="round.current">
      <p class="text-center">Round {{ round.current }}</p>

      <div v-for="game in round.games" class="grid grid-cols-10">
        <div
            class="relative col-span-4 place-self-end"
            :class="{ 'bg-yellow-200': game.teamOne.id === highlightTeamId }"
        >
          {{ `${game.teamOne.name}` }}
        </div>
        <div class="grid grid-cols-3 col-span-2 place-self-center">
          <span
              class="text-right underline-offset-4 min-w-[32px]"
              :class="{ 'underline': game.teamOne.score > game.teamTwo.score }"
          >
            {{ game.teamOne.score }}
          </span>
          <span class="text-center">|</span>
          <span
              class="underline-offset-4 min-w-[32px]"
              :class="{ 'underline': game.teamTwo.score > game.teamOne.score }"
          >
            {{ game.teamTwo.score }}
          </span>
        </div>
        <div
            class="relative col-span-4 max-w-fit"
            :class="{ 'bg-yellow-200': game.teamTwo.id === highlightTeamId }"
        >
          {{ `${game.teamTwo.name}` }}
        </div>
      </div>
    </div>
  </div>
</template>
