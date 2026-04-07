<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Outbox;

use DateTimeImmutable;
use Domain\Client\Event\ClientRegisteredDomainEvent;
use Infrastructure\Outbox\ReflectionEventSerializer;
use PHPUnit\Framework\TestCase;

final class ReflectionEventSerializerTest extends TestCase
{
    public function test_it_serializes_and_deserializes_event_payload(): void
    {
        $occurredOn = new DateTimeImmutable('2026-04-07T10:20:30+00:00');
        $event = new ClientRegisteredDomainEvent(
            clientId: 17,
            fullName: 'John Doe',
            email: 'john@example.com',
            phone: '+123456789',
            userId: 91,
            occurredOn: $occurredOn,
        );

        $serializer = new ReflectionEventSerializer();
        $payload = $serializer->serialize($event);
        $restoredEvent = $serializer->deserialize(ClientRegisteredDomainEvent::class, $payload);

        self::assertSame([
            'clientId' => 17,
            'fullName' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+123456789',
            'userId' => 91,
            'occurredOn' => '2026-04-07T10:20:30+00:00',
        ], $payload);
        self::assertInstanceOf(ClientRegisteredDomainEvent::class, $restoredEvent);
        self::assertEquals($occurredOn, $restoredEvent->occurredOn());
    }
}
