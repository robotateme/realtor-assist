<?php

declare(strict_types=1);

namespace Application\Port\Persistence;

use Application\DTO\OutboxMessage;
use DateTimeImmutable;

interface OutboxMessageRepositoryInterface
{
    public function add(object $event): void;

    /**
     * @param iterable<object> $events
     */
    public function addAll(iterable $events): void;

    /**
     * @return list<OutboxMessage>
     */
    public function pending(int $limit = 100): array;

    public function markAsPublished(string $messageId, ?DateTimeImmutable $publishedAt = null): void;
}
