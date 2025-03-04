<?php

namespace App\Repository;

use App\Entity\Team;
use App\Entity\TeamScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function getTotalWinCount(Team $team): int
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('count(ts1.id)')
            ->from(TeamScore::class, 'ts1')
            ->join(
                join: TeamScore::class,
                alias: 'ts2',
                conditionType: Join::WITH,
                condition: 'ts1.game = ts2.game',
            )
            ->where('ts1.team = :team')
            ->andWhere('ts2.team <> :team')
            ->andWhere('ts1.score > ts2.score')
            ->setParameter('team', $team)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
