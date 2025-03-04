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
        $teamShiftingIndexes = $this->getTeamShiftingIndexes($teamNames);

        foreach (range(1, $this->getRoundCount($teamNames)) as $round) {
            $shiftedIndexes = array_merge([0], $teamShiftingIndexes);

            [$teamOneIndexes, $teamTwoIndexes] = $this->getTeamPairingIndexes(
                $shiftedIndexes,
            );

            $this->generateGames(
                teamOneIndexes: $teamOneIndexes,
                teamTwoIndexes: $teamTwoIndexes,
                tournament: $tournament,
                teamNames: $teamNames,
                teams: $teams,
                round: $round,
            );

            $teamShiftingIndexes[] = array_shift($teamShiftingIndexes);
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
     * Returns all indexes that will be shifted around.
     *
     * Zeroth element always stays in the same
     * (first) position so it's not returned.
     *
     * @param string[] $teamNames
     * @return int[]
     */
    private function getTeamShiftingIndexes(array $teamNames): array
    {
        $indexes = array_keys($teamNames);
        array_shift($indexes);

        return $indexes;
    }

    /**
     * Returns the amount of rounds to play based on how many teams
     * will participate in a tournament.
     *
     * @param string[] $teamNames All team names. Including 'Bye'
     * @return int
     */
    private function getRoundCount(array $teamNames): int
    {
        return count($teamNames) - 1;
    }

    /**
     * Pairs will be matched by taking each of the team names
     * using the same array index from both arrays.
     *
     * Example.
     *
     * $teamOneIndexes = [0, 1, 2];
     * $teamTwoIndexes = [5, 4, 3];
     *
     * $teamNames = [
     *     0 => 'Real Madrid',
     *     1 => 'Barcelona',
     *     2 => 'Man City',
     *     3 => 'Man United',
     *     4 => 'Chelsea',
     *     5 => 'Liverpool',
     * ];
     *
     * $pairs = [
     *     0 => 5,
     *     1 => 4,
     *     2 => 3,
     * ];
     *
     * @param int[] $shiftedIndexes
     * @return array
     */
    private function getTeamPairingIndexes(array $shiftedIndexes): array
    {
        $middleIndex = count($shiftedIndexes) / 2;

        $teamOneIndexes = array_slice($shiftedIndexes, 0, $middleIndex);
        $teamTwoIndexes = array_reverse(array_slice($shiftedIndexes, $middleIndex));

        return [
            $teamOneIndexes,
            $teamTwoIndexes,
        ];
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

    private function generateScore(): int
    {
        return rand(10, 200);
    }
}
