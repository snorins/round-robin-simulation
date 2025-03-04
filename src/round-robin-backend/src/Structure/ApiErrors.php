<?php

namespace App\Structure;

class ApiErrors
{
    /** @var ApiErrorRecord[] */
    private array $records = [];

    public function add(string $key, string $message, mixed $oldValue = null): void
    {
        $this->records[$key] = new ApiErrorRecord($message, $oldValue);
    }

    public function hasAny(): bool
    {
        return count($this->getAll()) > 0;
    }

    /**
     * @return ApiErrorRecord[]
     */
    public function getAll(): array
    {
        return $this->records;
    }
}
