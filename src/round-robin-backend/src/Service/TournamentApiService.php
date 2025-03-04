<?php

namespace App\Service;

use App\Entity\Team;
use App\Entity\Tournament;
use App\Repository\TeamRepository;
use App\Repository\TournamentRepository;

readonly class TournamentApiService
{
    public function __construct(
        private TeamRepository $teamRepository,
        private TournamentRepository $tournamentRepository,
    ) {}

    public function retrieveInformation(int $id): array
    {
        $tournament = $this->tournamentRepository->find($id);
        $tournamentTeams = $this->getTournamentTeams($tournament);

        $teams = [];
        foreach ($tournamentTeams as $team) {
            $teams[] = [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'wins' => 0,
                'score' => 0,
            ];
        }

        $teamCount = count($tournamentTeams);
        $roundCount = $this->getRoundCount($teamCount);

        $rounds = [];
        foreach (range(1, $roundCount) as $round) {
            $rounds[] = [
                'current' => $round,
                'games' => [],
            ];
        }

        foreach ($tournament->getGames() as $game) {
            [$teamOneScore, $teamTwoScore] = $game->getTeamScores()->toArray();

            $teamOne = $teamOneScore->getTeam();
            $teamTwo = $teamTwoScore->getTeam();

            $keyTeamOne = array_find_key($teams, fn(array $team) => $team['id'] === $teamOne->getId());
            $keyTeamTwo = array_find_key($teams, fn(array $team) => $team['id'] === $teamTwo->getId());

            $teams[$keyTeamOne]['score'] += $teamOneScore->getScore();
            $teams[$keyTeamTwo]['score'] += $teamTwoScore->getScore();

            if ($teamOneScore->getScore() > $teamTwoScore->getScore()) {
                $teams[$keyTeamOne]['wins']++;
            }

            if ($teamTwoScore->getScore() > $teamOneScore->getScore()) {
                $teams[$keyTeamTwo]['wins']++;
            }

            $roundIndex = $this->findRoundIndex(
                gameRound: $game->getRound(),
                rounds: $rounds,
            );

            $rounds[$roundIndex]['games'][] = [
                'id' => $game->getId(),
                'teamOne' => [
                    'id' => $teamOne->getId(),
                    'name' => $teamOne->getName(),
                    'score' => $teamOneScore->getScore(),
                ],
                'teamTwo' => [
                    'id' => $teamTwo->getId(),
                    'name' => $teamTwo->getName(),
                    'score' => $teamTwoScore->getScore(),
                ],
            ];
        }

        return [
            'name' => $tournament->getName(),
            'teams' => $this->getSortedTeams($teams),
            'rounds' => $rounds,
        ];
    }

    /**
     * @param Tournament $tournament
     * @return Team[]
     */
    private function getTournamentTeams(Tournament $tournament): array
    {
        $teamIds = $this->tournamentRepository->getTeamIds($tournament);

        return $this->teamRepository->findBy(['id' => $teamIds]);
    }

    private function getRoundCount(int $teamCount): int
    {
        if ($teamCount % 2 === 0) {
            return $teamCount - 1;
        }

        return $teamCount;
    }

    private function findRoundIndex(int $gameRound, array $rounds): int
    {
        return array_find_key($rounds, function (array $round) use ($gameRound) {
            $currentRound = $round['current'] ?? null;

            return $currentRound === $gameRound;
        });
    }

    /**
     * Ranking is determined by:
     *  - first and foremost how many times the team has won in total
     *  - if teams have the same amount of wins — total score
     *
     * @param array $teams
     * @return array
     */
    private function getSortedTeams(array $teams): array
    {
        usort($teams, function ($a, $b) {
            if ($a['wins'] === $b['wins']) {
                return $b['score'] <=> $a['score'];
            }

            return $b['wins'] <=> $a['wins'];
        });

        return $teams;
    }
}
