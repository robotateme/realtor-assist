<?php

declare(strict_types=1);

namespace Infrastructure\Bus\Jobs;

use Application\Command\MessengerWebhookCommand;
use Application\Command\MessengerWebhookHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

final class MessengerWebhookJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly MessengerWebhookCommand $command,
    ) {
    }

    public function handle(MessengerWebhookHandler $handler): void
    {
        $handler->handle($this->command);
    }
}
