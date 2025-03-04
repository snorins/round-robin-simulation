<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import type { TournamentCreateResponse } from '../../../types/tournament.type.ts';
import { ApiService } from '../../../services/api.service.ts';
import InputLabel from '../../reusable/InputLabel.vue';
import TextInput from '../../reusable/input/TextInput.vue';
import NumberInput from '../../reusable/input/NumberInput.vue';
import { isErrorResponse } from '../../../helpers/api.helper.ts';
import type { JsonApiErrors } from '../../../types/api.type.ts';
import { API_ENDPOINT } from '../../../constants/api.constant.ts';
import { TOURNAMENT_TEAM_COUNT } from '../../../constants/tournament.constant.ts';
import LoaderButton from '../../reusable/button/LoaderButton.vue';

const isLoading = ref<boolean>(false);
const teamCount = ref<number>(3);
const tournamentName = ref<string>('');

const errors = ref<JsonApiErrors>({});
const hasGlobalError = ref<boolean>(false);

const router = useRouter();

const submit = (): void => {
  if (isLoading.value) {
    return;
  }

  isLoading.value = true;

  const requestBody = {
    name: tournamentName.value,
    teamCount: String(teamCount.value),
  };

  ApiService
      .post<TournamentCreateResponse>(
          API_ENDPOINT.simulateTournament,
          requestBody,
      )
      .then((response) => {
        if (isErrorResponse(response)) {
          errors.value = response.errors;
        } else {
          router.push(`/tournaments/${response.data.id}`);
        }
      })
      .catch(() => hasGlobalError.value = true)
      .finally(() => isLoading.value = false);
};
</script>

<template>
  <form class="flex flex-col gap-8" @submit.prevent="submit">
    <div>
      <InputLabel
          for="tournament-name"
          text="Tournament name:"
      />
      <TextInput
          v-model="tournamentName"
          id="tournament-name"
          :error-message="errors.name?.message"
          placeholder="Colosseum"
      />
    </div>

    <div class="max-w-xs">
      <NumberInput
          v-model="teamCount"
          id="team-count"
          :min="TOURNAMENT_TEAM_COUNT.MIN"
          :max="TOURNAMENT_TEAM_COUNT.MAX"
          placeholder="3"
          :has-error="Boolean(errors.teamCount)"
          label-text="Choose the amount of teams that will play in this tournament:"
      />
    </div>

    <LoaderButton
        type="submit"
        :is-loading
        default-text="Create & Simulate Tournament"
        loading-text="Simulating..."
    />
  </form>

  <p v-if="hasGlobalError" class="mt-2 text-sm text-red-700">
    Something went wrong. Please try again later.
  </p>
</template>
