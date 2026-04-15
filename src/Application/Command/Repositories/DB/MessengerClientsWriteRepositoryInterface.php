<?php

declare(strict_types=1);

namespace Application\Command\Repositories\DB;

use Application\DTO\MessengerClientDTO;
use Domain\MessengerClient\MessengerProviderEnum;

interface MessengerClientsWriteRepositoryInterface
{
    public function create(
        MessengerProviderEnum $provider,
        MessengerClientDTO $messengerClientDTO,
    ): void;
}
