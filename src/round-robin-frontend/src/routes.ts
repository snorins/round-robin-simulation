import type { RouteRecordRaw } from 'vue-router';
import TournamentCreate from './components/views/tournament/TournamentCreate.vue';
import Tournaments from './components/views/tournament/Tournaments.vue';
import Tournament from './components/views/tournament/Tournament.vue';
import TournamentGames from './components/views/tournament/TournamentGames.vue';
import Teams from './components/views/team/Teams.vue';
import Statistics from './components/views/statistics/Statistics.vue';

export const routes: RouteRecordRaw[] = [
    {
        path: '/',
        component: TournamentCreate
    },
    {
        path: '/tournaments',
        children: [
            {
                path: '',
                component: Tournaments
            },
            {
                path: ':id',
                component: Tournament,
            },
            {
                path: ':id/games',
                component: TournamentGames,
            }
        ],
    },
    {
        path: '/teams',
        component: Teams,
    },
    {
        path: '/statistics',
        component: Statistics,
    }
];
