<?php

namespace Application\Port\Persistence;

use Illuminate\Database\Eloquent\Model;

interface ReadRepositoryFactoryPort
{
    /**
     * @param class-string<Model> $modelClass
     */
    public function forModel(string $modelClass): ReadRepositoryPort;
}
