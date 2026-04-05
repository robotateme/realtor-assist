<?php

namespace Infrastructure\Persistence\Repositories;

use Application\Port\Persistence\ReadRepositoryFactoryPort;
use Application\Port\Persistence\ReadRepositoryPort;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Override;

final class EloquentReadRepositoryFactory implements ReadRepositoryFactoryPort
{
    public function __construct(
        private readonly Container $container,
    ) {
    }

    /**
     * @param class-string<Model> $modelClass
     */
    #[Override]
    public function forModel(string $modelClass): ReadRepositoryPort
    {
        return new EloquentReadRepository($this->container, $modelClass);
    }
}
