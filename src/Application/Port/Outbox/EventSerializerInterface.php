<?php

declare(strict_types=1);

namespace Application\Port\Outbox;

interface EventSerializerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function serialize(object $event): array;

    /**
     * @param array<string, mixed> $payload
     */
    public function deserialize(string $eventClass, array $payload): object;
}
