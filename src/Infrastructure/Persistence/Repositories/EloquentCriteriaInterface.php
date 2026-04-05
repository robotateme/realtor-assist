<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repositories;

use Application\Adapter\Persistence\CriteriaInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Override;

final class EloquentCriteriaInterface implements CriteriaInterface
{
    /**
     * @param Builder<Model> $builder
     */
    public function __construct(
        private readonly Builder $builder,
    ) {
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

    /** @return LengthAwarePaginator<int, Model> */
    #[Override]
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, Model> $paginator */
        $paginator = $this->builder->paginate($perPage);

        return $paginator;
    }
}
