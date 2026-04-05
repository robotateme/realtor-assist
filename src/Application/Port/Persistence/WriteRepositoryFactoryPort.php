<?php

namespace Application\Port\Persistence;

use Illuminate\Database\Eloquent\Model;

interface WriteRepositoryFactoryPort
{
    /**
     * @param class-string<Model> $modelClass
     */
    public function forModel(string $modelClass): WriteRepositoryPort;
}
