<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Port\Persistence\WriteRepositoryPort;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Override;

final class EloquentWriteRepository implements WriteRepositoryPort
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
     * @param array<string, mixed> $attributes
     */
    #[Override]
    public function create(array $attributes): Model
    {
        $model = $this->newModel();
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    #[Override]
    public function update(Model $model, array $attributes): Model
    {
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    #[Override]
    public function save(Model $model): bool
    {
        return $model->save();
    }

    #[Override]
    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    private function newModel(): Model
    {
        /** @var Model $model */
        $model = $this->container->make($this->modelClass);

        return $model;
    }
}
