<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Client;

use DateTimeImmutable;
use Domain\Client\Event\ClientRegisteredDomainEvent;
use PHPUnit\Framework\TestCase;

final class ClientRegisteredDomainEventTest extends TestCase
{
    public function test_it_exposes_event_payload_and_occurrence_time(): void
    {
        $occurredOn = new DateTimeImmutable('2026-04-05 12:00:00');
        $event = new ClientRegisteredDomainEvent(
            clientId: 42,
            fullName: 'Jane Doe',
            email: 'jane@example.com',
            phone: '+10000000000',
            userId: 7,
            occurredOn: $occurredOn,
        );

        self::assertSame(42, $event->clientId);
        self::assertSame('Jane Doe', $event->fullName);
        self::assertSame('jane@example.com', $event->email);
        self::assertSame('+10000000000', $event->phone);
        self::assertSame(7, $event->userId);
        self::assertSame($occurredOn, $event->occurredOn());
    }
}
