<?php

declare(strict_types=1);

namespace Infrastructure\Bus;

use Application\Port\Persistence\OutboxMessageRepositoryInterface;

final readonly class OutboxMessagePublisher
{
    public function __construct(
        private OutboxMessageRepositoryInterface $outboxMessages,
        private LaravelEventBusAdapter $eventBus,
    ) {
    }

    public function publishPending(int $limit = 100): int
    {
        $published = 0;

        foreach ($this->outboxMessages->pending($limit) as $message) {
            $this->eventBus->dispatch($message->event);
            $this->outboxMessages->markAsPublished($message->id);
            $published++;
        }

        return $published;
    }
}
