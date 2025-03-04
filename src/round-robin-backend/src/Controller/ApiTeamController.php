<?php

namespace App\Controller;

use App\Entity\Team;
use App\Helper\PaginationBuilder;
use App\Repository\TeamRepository;
use App\Repository\TeamScoreRepository;
use App\Trait\JsonApiTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class ApiTeamController extends AbstractController
{
    use JsonApiTrait;

    #[Route(path: '/api/teams', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] int $page,
        TeamRepository $teamRepository,
        TeamScoreRepository $scoreRepository,
    ): JsonResponse {
        $queryBuilder = $teamRepository
            ->createQueryBuilder('t')
            ->orderBy('t.id');

        $paginationBuilder = PaginationBuilder::init(
            queryBuilder: $queryBuilder,
            page: $page,
        );

        $paginationBuilder->mapEntries(fn(Team $team) => [
            'id' => $team->getId(),
            'name' => $team->getName(),
            'wins' => $teamRepository->getTotalWinCount($team),
            'score' => $scoreRepository->getTotalTeamScore($team),
        ]);

        return $this->getSuccessResponse(
            $paginationBuilder->getResult(),
        );
    }
}
