<?php

namespace Application\Port\Persistence;

use Application\Adapter\Persistence\QueryAdapter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ReadRepositoryPort
{
    /**
     * @return Collection<int, Model>
     */
    public function all(): Collection;

    public function findById(int|string $id): ?Model;

    /**
     * @param array<string, mixed> $criteria
     */
    public function findOneBy(array $criteria): ?Model;

    public function query(): QueryAdapter;
}
