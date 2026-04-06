<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Shared;

use Domain\Client\Event\ClientRegisteredDomainEvent;
use Domain\Shared\DomainEvents;
use Override;
use PHPUnit\Framework\TestCase;

final class DomainEventsTest extends TestCase
{
    #[Override]
    protected function tearDown(): void
    {
        DomainEvents::clear();

        parent::tearDown();
    }

    public function test_it_dispatches_subscribed_domain_events(): void
    {
        $handledEvents = [];

        DomainEvents::subscribe(
            ClientRegisteredDomainEvent::class,
            static function (ClientRegisteredDomainEvent $event) use (&$handledEvents): void {
                $handledEvents[] = $event->clientId;
            },
        );

        DomainEvents::dispatch(new ClientRegisteredDomainEvent(
            clientId: 42,
            fullName: 'Jane Doe',
            email: 'jane@example.com',
            phone: null,
            userId: null,
        ));

        self::assertSame([42], $handledEvents);
    }
}
