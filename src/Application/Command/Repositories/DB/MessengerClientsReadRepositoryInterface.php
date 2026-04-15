<?php

declare(strict_types=1);

namespace Application\Command\Repositories\DB;

use Domain\MessengerClient\MessengerProviderEnum;

interface MessengerClientsReadRepositoryInterface
{
    public function existsByMessengerId(MessengerProviderEnum $provider, string $messengerId): bool;
}
