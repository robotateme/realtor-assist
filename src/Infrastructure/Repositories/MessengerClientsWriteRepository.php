<?php

declare(strict_types=1);

namespace Infrastructure\Repositories;

use App\Models\MessengerClient;
use Application\Command\Repositories\DB\MessengerClientsWriteRepositoryInterface;
use Application\DTO\MessengerClientDTO;
use Domain\MessengerClient\MessengerProviderEnum;
use Override;

final class MessengerClientsWriteRepository implements MessengerClientsWriteRepositoryInterface
{
    #[Override]
    public function create(
        MessengerProviderEnum $provider,
        MessengerClientDTO $messengerClientDTO,
    ): void {
        MessengerClient::query()->create([
            'client_id' => $messengerClientDTO->clientId,
            'provider' => $provider->value,
            'username' => $messengerClientDTO->username,
            'first_name' => $messengerClientDTO->firstName,
            'last_name' => $messengerClientDTO->lastName,
            'is_bot' => $messengerClientDTO->isBot,
            'messenger_id' => $messengerClientDTO->messengerId,
        ]);
    }
}
