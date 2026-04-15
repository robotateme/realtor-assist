<?php

declare(strict_types=1);

namespace Application\Query\Clients\Repositories\DB;

use Application\Criteria\Persistence\Criteria;
use Domain\Client\ClientEntity;

/**
 *
 */
interface ClientsReadRepositoryInterface
{
    /**
     * @return iterable<int, ClientEntity>
     */
    public function all(Criteria $criteria): iterable;

    public function findById(int|string $id): ClientEntity;

    public function findOneBy(Criteria $criteria): ClientEntity;
}
