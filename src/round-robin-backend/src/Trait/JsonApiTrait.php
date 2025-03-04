<?php

namespace App\Trait;

use App\Structure\ApiErrors;
use Symfony\Component\HttpFoundation\JsonResponse;

trait JsonApiTrait
{
    private ApiErrors $apiErrors;

    public function __construct()
    {
        $this->apiErrors = new ApiErrors();
    }

    private function getSuccessResponse(array $data): JsonResponse
    {
        return $this->json([
            'data' => $data,
            'errors' => [],
        ]);
    }

    private function getErrorResponse(): JsonResponse
    {
        return $this->json([
            'errors' => $this->apiErrors->getAll(),
            'data' => [],
        ]);
    }
}
