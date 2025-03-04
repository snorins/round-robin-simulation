<?php

namespace App\Structure;

class ApiErrorRecord
{
    public function __construct(
        public string $message,
        public string|int|null $oldValue = null,
    ) {}
}
