<?php

declare(strict_types=1);

namespace Application\DTO;

use DateTimeImmutable;

final readonly class OutboxMessage
{
    public function __construct(
        public string $id,
        public string $eventClass,
        public object $event,
        public DateTimeImmutable $occurredOn,
        public ?DateTimeImmutable $publishedAt = null,
    ) {
    }
}
