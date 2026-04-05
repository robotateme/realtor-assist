<?php

namespace Application\Adapter\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface QueryAdapter
{
    /**
     * @param list<string> $associations
     */
    public function include(array $associations): self;

    public function where(string $column, mixed $operatorOrValue, mixed $value = null): self;

    public function orderBy(string $column, string $direction = 'asc'): self;

    public function limit(int $limit): self;

    public function first(): ?Model;

    /**
     * @return Collection<int, Model>
     */
    public function get(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;
}
