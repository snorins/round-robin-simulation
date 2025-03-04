<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\TeamScore;
use App\Entity\Tournament;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class TournamentSimulatorService
{
    public function __construct(
        private GameRepository $gameRepository,
        private EntityManagerInterface $entityManager,
        private TeamGeneratorService $teamGeneratorService,
    ) {}

    /**
     * @param int $teamCount
     * @param Tournament $tournament
     * @return Game[]
     */
    public function run(int $teamCount, Tournament $tournament): array
    {
        $teams = $this->teamGeneratorService->generate($teamCount);
        $teamNames = $this->teamGeneratorService->getNames($teams);
        $teamIndexes = $this->getTeamIndexes($teamNames);

        $roundCount = count($teamNames) - 1;
        $middleIndex = $roundCount / 2 + 1;

        foreach (range(1, $roundCount) as $round) {
            $shiftedIndexes = array_merge([0], $teamIndexes);

            $teamOneIndexes = array_slice($shiftedIndexes, 0, $middleIndex);
            $teamTwoIndexes = array_reverse(array_slice($shiftedIndexes, $middleIndex));

            $this->generateGames(
                teamOneIndexes: $teamOneIndexes,
                teamTwoIndexes: $teamTwoIndexes,
                tournament: $tournament,
                teamNames: $teamNames,
                teams: $teams,
                round: $round,
            );

            $teamIndexes[] = array_shift($teamIndexes);
        }

        $this->entityManager->persist($tournament);

        foreach ($teams as $team) {
            $this->entityManager->persist($team);
        }

        // Insert everything using a single transaction.
        $this->entityManager->flush();

        return $this->gameRepository->findBy([
            'tournament' => $tournament,
        ]);
    }

    /**
     * @param int[] $teamOneIndexes
     * @param int[] $teamTwoIndexes
     * @param Tournament $tournament
     * @param string[] $teamNames
     * @param Team[] $teams
     * @param int $round
     * @return void
     */
    private function generateGames(
        array $teamOneIndexes,
        array $teamTwoIndexes,
        Tournament $tournament,
        array $teamNames,
        array $teams,
        int $round,
    ): void {
        foreach ($teamOneIndexes as $index => $teamOneIndex) {
            $teamOneName = $teamNames[$teamOneIndex];
            $teamTwoName = $teamNames[$teamTwoIndexes[$index]];

            if (in_array(Team::NAME_NO_OPPONENT, [$teamOneName, $teamTwoName])) {
                continue;
            }

            $game = new Game();
            $game->setTournament($tournament);

            $teamOne = $this->teamGeneratorService->findOneByName($teamOneName, $teams);
            $teamTwo = $this->teamGeneratorService->findOneByName($teamTwoName, $teams);

            $teamOneScore = new TeamScore();
            $teamOneScore->setGame($game);
            $teamOneScore->setTeam($teamOne);
            $teamOneScore->setScore($this->generateScore());
            $teamOneScore->setTournament($tournament);

            $teamTwoScore = new TeamScore();
            $teamTwoScore->setGame($game);
            $teamTwoScore->setTeam($teamTwo);
            $teamTwoScore->setScore($this->generateScore());
            $teamTwoScore->setTournament($tournament);

            $game->addTeamScore($teamOneScore);
            $game->addTeamScore($teamTwoScore);
            $game->setRound($round);

            $this->entityManager->persist($teamOneScore);
            $this->entityManager->persist($teamTwoScore);
            $this->entityManager->persist($game);
        }
    }

    /**
     * Returns all the team indexes from
     * name array — except the first one.
     *
     * These will be indexes that will be swapped around.
     * First element always stays in the same position.
     *
     * @param string[] $teamNames
     * @return int[]
     */
    private function getTeamIndexes(array $teamNames): array
    {
        $indexes = array_keys($teamNames);
        array_shift($indexes);

        return $indexes;
    }

    private function generateScore(): int
    {
        return rand(10, 200);
    }
}
