<?php

namespace App\Controller;

use App\Repository\TeamScoreRepository;
use App\Trait\JsonApiTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiStatisticsController extends AbstractController
{
    use JsonApiTrait;

    #[Route(path: '/api/statistics', methods: ['GET'])]
    public function index(TeamScoreRepository $teamScoreRepository): JsonResponse
    {
        return $this->getSuccessResponse([
            'tournaments' => $teamScoreRepository->getTopScoreTournaments(),
            'teams' => $teamScoreRepository->getTopScoreTeams(),
        ]);
    }
}
