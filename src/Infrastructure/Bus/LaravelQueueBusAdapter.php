<?php

declare(strict_types=1);

namespace Infrastructure\Bus;

use Application\Command\MessengerWebhookCommand;
use Application\Port\Bus\QueueBusPortInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use Infrastructure\Bus\Jobs\MessengerWebhookJob;
use Override;

final readonly class LaravelQueueBusAdapter implements QueueBusPortInterface
{
    public function __construct(
        private Dispatcher $dispatcher,
        private ?string $messengerWebhookQueue = null,
    ) {
    }

    #[Override]
    public function dispatch(object $command): mixed
    {
        if ($command instanceof MessengerWebhookCommand) {
            $job = new MessengerWebhookJob($command);

            if ($this->messengerWebhookQueue !== null && $this->messengerWebhookQueue !== '') {
                $job->onQueue($this->messengerWebhookQueue);
            }

            return $this->dispatcher->dispatch($job);
        }

        return $this->dispatcher->dispatch($command);
    }
}
