<?php

declare(strict_types=1);

namespace Infrastructure\Repositories;

use App\Models\MessengerClient;
use Application\Command\Repositories\DB\MessengerClientsReadRepositoryInterface;
use Domain\MessengerClient\MessengerProviderEnum;
use Override;

final readonly class MessengerClientsReadRepository implements MessengerClientsReadRepositoryInterface
{
    #[Override]
    public function existsByMessengerId(MessengerProviderEnum $provider, string $messengerId): bool
    {
        return MessengerClient::query()
            ->where('provider', $provider->value)
            ->where('messenger_id', $messengerId)
            ->exists();
    }
}
