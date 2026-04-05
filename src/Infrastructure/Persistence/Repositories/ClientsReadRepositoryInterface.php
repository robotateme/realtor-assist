<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Repositories;

use Application\Criteria\Persistence\Criteria;
use Application\Port\Persistence\ReadRepositoryInterface;
use Domain\Client\ClientEntity;
use Override;

interface ClientsReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * @return iterable<int, ClientEntity>
     */
    #[Override]
    public function all(Criteria $criteria): iterable;

    #[Override]
    public function findById(int|string $id): ?ClientEntity;

    #[Override]
    public function findOneBy(Criteria $criteria): ?ClientEntity;
}
