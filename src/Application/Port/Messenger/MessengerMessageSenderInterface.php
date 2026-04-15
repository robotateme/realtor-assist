<?php

declare(strict_types=1);

namespace Application\Port\Messenger;

interface MessengerMessageSenderInterface
{
    public function send(int $chatId, string $message): void;
}
