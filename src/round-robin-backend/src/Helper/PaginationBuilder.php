<?php

namespace App\Helper;

use Closure;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationBuilder
{
    private const int DEFAULT_PAGE_LIMIT = 10;

    private function __construct(
        private array $entries = [],
        private readonly int $total = 0,
        private readonly int $page = 1,
    ) {}

    public static function init(QueryBuilder $queryBuilder, int $page): self
    {
        $offset = ($page - 1) * self::DEFAULT_PAGE_LIMIT;

        $queryBuilder->setMaxResults(self::DEFAULT_PAGE_LIMIT);
        $queryBuilder->setFirstResult($offset);

        $paginator = new Paginator($queryBuilder);

        return new self(
            entries: iterator_to_array($paginator),
            total: $paginator->count(),
            page: $page,
        );
    }

    public function mapEntries(Closure $closure): void
    {
        $transformedEntries = array_map($closure, $this->entries);

        $this->entries = $transformedEntries;
    }

    public function getResult(): array
    {
        return [
            'entries' => $this->entries,
            ...$this->getMetadata(),
        ];
    }

    private function getMetadata(): array
    {
        $limit = self::DEFAULT_PAGE_LIMIT;
        $pages = ceil($this->total / $limit);

        $from = ($this->page - 1) * $limit;

        $to = $this->page * $limit;
        if ($to > $this->total) {
            $to = $this->total;
        }

        return [
            'total' => $this->total,
            'page' => $this->page,
            'limit' => $limit,
            'pages' => intval($pages),
            'from' => $from + 1,
            'to' => $to,
        ];
    }
}
