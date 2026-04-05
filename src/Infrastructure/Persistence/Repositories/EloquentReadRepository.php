<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Adapter\Persistence\QueryAdapter;
use Application\Port\Persistence\ReadRepositoryPort;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Override;

final class EloquentReadRepository implements ReadRepositoryPort
{
    /**
     * @param class-string<Model> $modelClass
     */
    public function __construct(
        private readonly Container $container,
        private readonly string $modelClass,
    ) {
    }

    /**
     * @return Collection<int, Model>
     */
    #[Override]
    public function all(): Collection
    {
        /** @var Collection<int, Model> $models */
        $models = $this->newModel()->newQuery()->get();

        return $models;
    }

    #[Override]
    public function findById(int|string $id): ?Model
    {
        return $this->newModel()->newQuery()->find($id);
    }

    /**
     * @param array<string, mixed> $criteria
     */
    #[Override]
    public function findOneBy(array $criteria): ?Model
    {
        return $this->newModel()->newQuery()->where($criteria)->first();
    }

    #[Override]
    public function query(): QueryAdapter
    {
        /** @var \Illuminate\Database\Eloquent\Builder<Model> $builder */
        $builder = $this->newModel()->newQuery();

        return new EloquentQueryAdapter($builder);
    }

    private function newModel(): Model
    {
        /** @var Model $model */
        $model = $this->container->make($this->modelClass);

        return $model;
    }
}
