<?php

namespace App\Controller;

use App\Entity\Tournament;
use App\Helper\PaginationBuilder;
use App\Repository\TournamentRepository;
use App\Service\TournamentSimulatorService;
use App\Service\TournamentApiService;
use App\Trait\JsonApiTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class ApiTournamentController extends AbstractController
{
    use JsonApiTrait;

    #[Route(path: '/api/tournaments', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] int $page,
        TournamentRepository $repository,
    ): JsonResponse {
        $queryBuilder = $repository
            ->createQueryBuilder('t')
            ->orderBy('t.id');

        $paginationBuilder = PaginationBuilder::init(
            queryBuilder: $queryBuilder,
            page: $page,
        );

        $paginationBuilder->mapEntries(fn(Tournament $tournament) => [
            'id' => $tournament->getId(),
            'name' => $tournament->getName(),
            'createdAt' => $tournament->getCreatedAt(),
            'teamCount' => $repository->getTeamCount($tournament),
        ]);

        return $this->getSuccessResponse(
            $paginationBuilder->getResult(),
        );
    }

    #[Route(path: '/api/tournament/create', methods: ['POST'])]
    public function create(
        Request $request,
        TournamentRepository $tournamentRepository,
        TournamentSimulatorService $tournamentSimulator,
    ): JsonResponse {
        $name = $request->getPayload()->get('name');
        $teamCount = $request->getPayload()->get('teamCount');

        if (strlen($name) > Tournament::NAME_LENGTH_MAX) {
            $this->apiErrors->add('name', 'Name can not be longer than ' . Tournament::NAME_LENGTH_MAX . ' characters');
        }

        if ($tournamentRepository->count(['name' => $name]) > 0) {
            $this->apiErrors->add('name', "Name \"$name\" is already being used.");
        }

        if ($teamCount < Tournament::TEAMS_IN_TOURNAMENT_MIN || $teamCount > Tournament::TEAMS_IN_TOURNAMENT_MAX) {
            $this->apiErrors->add('teamCount', "\"$teamCount\" is not a valid number of teams.");
        }

        if ($this->apiErrors->hasAny()) {
            return $this->getErrorResponse();
        }

        $tournament = new Tournament();
        $tournament->setName($name);

        $tournamentSimulator->run(
            teamCount: $teamCount,
            tournament: $tournament,
        );

        // Simulate a little more heavy processing.
        sleep(seconds: 1);

        return $this->getSuccessResponse([
            'id' => $tournament->getId(),
        ]);
    }

    #[Route(path: '/api/tournament/{id}', methods: ['GET'])]
    public function show(int $id, TournamentApiService $tournamentApiService): JsonResponse
    {
        $tournamentMeta = $tournamentApiService->retrieveInformation($id);

        return $this->getSuccessResponse($tournamentMeta);
    }
}
