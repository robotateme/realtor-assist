<?php

declare(strict_types=1);

namespace Application\Criteria\Persistence;

final readonly class Filter
{
    /**
     * @param scalar|array<int, mixed>|\Traversable<int, mixed>|null $value
     */
    public function __construct(
        public string $column,
        public FilterEnum $filter,
        public mixed $value,
    ) {
    }
}
