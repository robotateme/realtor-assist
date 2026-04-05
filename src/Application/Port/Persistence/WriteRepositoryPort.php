<?php

declare(strict_types=1);

namespace Application\Port\Persistence;

use Illuminate\Database\Eloquent\Model;

interface WriteRepositoryPort
{
    /**
     * @param class-string<Model> $modelClass
     * @param array<string, mixed> $attributes
     */
    public function create(string $modelClass, array $attributes): Model;

    /**
     * @param array<string, mixed> $attributes
     */
    public function update(Model $model, array $attributes): Model;

    public function save(Model $model): bool;

    public function delete(Model $model): bool;
}
