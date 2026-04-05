<?php

declare(strict_types=1);

namespace Application\Criteria\Persistence;

final readonly class Criteria
{
    /**
     * @param list<Filter> $filters
     * @param list<Order> $orders
     * @param int|null $limit
     */
    public function __construct(
        private array $filters = [],
        private array $orders = [],
        private ?int  $limit = null,
    ) {
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return Order[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
