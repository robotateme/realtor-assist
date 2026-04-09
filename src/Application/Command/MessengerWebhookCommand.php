<?php

declare(strict_types=1);

namespace Application\Command;

use Application\DTO\MessengerClientDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 *
 */
final class MessengerWebhookCommand implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public MessengerClientDTO $messengerClientDTO,
        public int $chatId,
        public string $messageText = '',
    ) {
        $this->onQueue((string) config('realtor-assist.queues.webhook'));
    }

    public function handle(MessengerWebhookHandler $handler): void
    {
        $handler->handle($this);
    }
}
