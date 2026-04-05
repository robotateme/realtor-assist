<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Adapter\Persistence\QueryAdapter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Override;

final class EloquentQueryAdapter implements QueryAdapter
{
    public function __construct(
        private readonly Builder $builder,
    ) {
    }

    /**
     * @param list<string> $associations
     */
    #[Override]
    public function include(array $associations): QueryAdapter
    {
        $this->builder->with($associations);

        return $this;
    }

    #[Override]
    public function where(string $column, mixed $operatorOrValue, mixed $value = null): QueryAdapter
    {
        if (func_num_args() === 2) {
            $this->builder->where($column, $operatorOrValue);

            return $this;
        }

        $this->builder->where($column, $operatorOrValue, $value);

        return $this;
    }

    #[Override]
    public function orderBy(string $column, string $direction = 'asc'): QueryAdapter
    {
        $this->builder->orderBy($column, $direction);

        return $this;
    }

    #[Override]
    public function limit(int $limit): QueryAdapter
    {
        $this->builder->limit($limit);

        return $this;
    }

    #[Override]
    public function first(): ?Model
    {
        return $this->builder->first();
    }

    /**
     * @return Collection<int, Model>
     */
    #[Override]
    public function get(): Collection
    {
        /** @var Collection<int, Model> $models */
        $models = $this->builder->get();

        return $models;
    }

    #[Override]
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->builder->paginate($perPage);
    }
}
