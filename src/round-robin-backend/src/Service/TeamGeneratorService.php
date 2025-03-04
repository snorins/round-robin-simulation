<?php

namespace App\Service;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Faker\Factory;

readonly class TeamGeneratorService
{
    public function __construct(
        private TeamRepository $teamRepository,
    ) {}

    /**
     * @return Team[]
     */
    public function generate(int $count): array
    {
        $teamNames = [];
        $teams = [];

        for ($index = 0; $index < $count; $index++) {
            do {
                $name = $this->generateName();
                // Prevent names getting duplicated.
            } while (in_array($name, $teamNames));

            $team = $this->teamRepository->findOneBy(['name' => $name]);

            if (!$team) {
                $team = new Team();
                $team->setName($name);
            }

            $teams[] = $team;
            $teamNames[] = $name;
        }

        return $teams;
    }

    /**
     * @param Team[] $teams
     * @return string[]
     */
    public function getNames(array $teams): array
    {
        $names = [];

        foreach ($teams as $team) {
            $names[] = $team->getName();
        }

        if (count($names) % 2 === 1) {
            $names[] = Team::NAME_NO_OPPONENT;
        }

        return $names;
    }

    /**
     * @param string $name
     * @param Team[] $teams
     * @return Team|null
     */
    public function findOneByName(string $name, array $teams): ?Team
    {
        return array_find($teams, fn(Team $team) => $team->getName() === $name);
    }

    private function generateName(): string
    {
        $fakerGenerator = Factory::create();

        $firstPart = $fakerGenerator->lastName();
        $lastPart = $fakerGenerator->companySuffix();

        return "$firstPart $lastPart";
    }
}
