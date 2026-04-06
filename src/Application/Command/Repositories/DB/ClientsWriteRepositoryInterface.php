<?php

declare(strict_types=1);

namespace Application\Command\Repositories\DB;

use Application\DTO\ClientDTO;
use Domain\Client\ClientEntity;

/**
 *
 */
interface ClientsWriteRepositoryInterface
{
    /**
     * @param ClientDTO $clientDTO
     * @return ClientEntity
     */
    public function create(ClientDTO $clientDTO): ClientEntity;

    /**
     * @param ClientDTO $clientDTO
     * @return bool
     */
    public function updateOne(ClientDTO $clientDTO): bool;
}
