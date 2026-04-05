<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Client;

use Domain\Client\ClientEntity;
use Domain\Client\Event\ClientRegisteredDomainEvent;
use Domain\Client\VO\ClientEmail;
use PHPUnit\Framework\TestCase;

final class ClientEntityTest extends TestCase
{
    public function test_it_records_client_registered_event_on_registration(): void
    {
        $client = ClientEntity::register(
            id: 42,
            fullName: 'Jane Doe',
            email: new ClientEmail('jane@example.com'),
            phone: '+10000000000',
            userId: 7,
        );

        $events = $client->domainEvents();

        self::assertCount(1, $events);
        self::assertInstanceOf(ClientRegisteredDomainEvent::class, $events[0]);
        self::assertSame(42, $events[0]->clientId);
        self::assertSame('jane@example.com', $events[0]->email);
    }

    public function test_it_exposes_client_data(): void
    {
        $client = new ClientEntity(
            id: 42,
            fullName: 'Jane Doe',
            email: new ClientEmail('jane@example.com'),
            phone: '+10000000000',
            userId: 7,
        );

        self::assertSame(42, $client->getId());
        self::assertSame('Jane Doe', $client->getFullName());
        self::assertSame('jane@example.com', $client->getEmail());
        self::assertSame('+10000000000', $client->getPhone());
        self::assertSame(7, $client->getUserId());
        self::assertFalse($client->hasDomainEvents());
    }

    public function test_it_releases_recorded_events_once(): void
    {
        $client = ClientEntity::register(
            id: 42,
            fullName: 'Jane Doe',
            email: new ClientEmail('jane@example.com'),
            phone: null,
            userId: null,
        );

        $releasedEvents = $client->releaseEvents();

        self::assertCount(1, $releasedEvents);
        self::assertSame([], $client->releaseEvents());
    }
}
