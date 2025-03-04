<?php

namespace App\Repository;

use App\Entity\Team;
use App\Entity\TeamScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeamScore>
 */
class TeamScoreRepository extends ServiceEntityRepository
{
    private const int STATISTICS_RESULTS_MAX = 3;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamScore::class);
    }

    public function getTotalTeamScore(Team $team): int
    {
        return $this->createQueryBuilder('ts')
            ->select('sum(ts.score)')
            ->where('ts.team = :team')
            ->setParameter('team', $team)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTopScoreTeams(int $max = self::STATISTICS_RESULTS_MAX): array
    {
        return $this->createQueryBuilder('ts')
            ->select('(t.id) as id')
            ->addSelect('(t.name) as name')
            ->addSelect('sum(ts.score) as score')
            ->join('ts.team', 't')
            ->groupBy('t.id')
            ->orderBy('score', 'desc')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    public function getTopScoreTournaments(int $max = self::STATISTICS_RESULTS_MAX): array
    {
        return $this->createQueryBuilder('ts')
            ->select('(t.id) as id')
            ->addSelect('(t.name) as name')
            ->addSelect('sum(ts.score) as score')
            ->join('ts.tournament', 't')
            ->groupBy('t.id')
            ->orderBy('score', 'desc')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }
}
