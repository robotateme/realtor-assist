<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Port\Persistence\WriteRepositoryFactoryPort;
use Application\Port\Persistence\WriteRepositoryPort;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Override;

final class EloquentWriteRepositoryFactory implements WriteRepositoryFactoryPort
{
    public function __construct(
        private readonly Container $container,
    ) {
    }

    /**
     * @param class-string<Model> $modelClass
     */
    #[Override]
    public function forModel(string $modelClass): WriteRepositoryPort
    {
        return new EloquentWriteRepository($this->container, $modelClass);
    }
}
