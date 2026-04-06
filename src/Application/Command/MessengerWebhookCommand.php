<?php

namespace Application\Command;

use Application\DTO\MessengerClientDTO;

/**
 *
 */
final readonly class MessengerWebhookCommand
{
    public function __construct(
        public MessengerClientDTO $messengerClientDTO,
        public int $chatId,
    ) {
    }
}
