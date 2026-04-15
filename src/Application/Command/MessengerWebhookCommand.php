<?php

declare(strict_types=1);

namespace Application\Command;

use Application\DTO\MessengerClientDTO;

final readonly class MessengerWebhookCommand
{
    public function __construct(
        public MessengerClientDTO $messengerClientDTO,
        public int $chatId,
        public string $messageText = '',
    ) {
    }
}
