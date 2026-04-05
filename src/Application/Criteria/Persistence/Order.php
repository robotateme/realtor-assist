<?php

declare(strict_types=1);

namespace Application\Criteria\Persistence;

final readonly class Order
{
    public function __construct(
        public string $field,
        public string $direction = 'ASC',
    ) {
    }
}
