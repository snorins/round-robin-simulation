<?php

namespace App\Repository;

use App\Entity\TeamScore;
use App\Entity\Tournament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tournament>
 */
class TournamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    /**
     * @param Tournament $tournament
     * @return int[]
     */
    public function getTeamIds(Tournament $tournament): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('distinct t.id')
            ->from(TeamScore::class, 'ts')
            ->where('ts.tournament = :tournament')
            ->leftJoin('ts.team', 't')
            ->setParameter('tournament', $tournament)
            ->orderBy('t.id')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * @param Tournament $tournament
     * @return int
     */
    public function getTeamCount(Tournament $tournament): int
    {
        return count($this->getTeamIds($tournament));
    }
}
