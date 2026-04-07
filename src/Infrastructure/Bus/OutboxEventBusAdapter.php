<?php

declare(strict_types=1);

namespace Infrastructure\Bus;

use Application\Port\Bus\EventBusPortInterface;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Override;

final readonly class OutboxEventBusAdapter implements EventBusPortInterface
{
    public function __construct(
        private OutboxMessageRepositoryInterface $outboxMessages,
    ) {
    }

    #[Override]
    public function dispatch(object $event): mixed
    {
        $this->outboxMessages->add($event);

        return null;
    }

    #[Override]
    public function dispatchAll(iterable $events): void
    {
        $this->outboxMessages->addAll($events);
    }
}
