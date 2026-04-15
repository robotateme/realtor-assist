<?php

declare(strict_types=1);

namespace Infrastructure\Messenger;

use Application\Port\Messenger\MessengerMessageSenderInterface;
use DefStudio\Telegraph\Facades\Telegraph;
use Override;

final class TelegramMessageSender implements MessengerMessageSenderInterface
{
    #[Override]
    public function send(int $chatId, string $message): void
    {
        Telegraph::chat((string) $chatId)
            ->message($message)
            ->send();
    }
}
