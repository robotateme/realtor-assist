<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\OutboxMessage;
use Application\Command\Repositories\DB\ClientsWriteRepositoryInterface;
use Application\DTO\ClientDTO;
use Application\Port\Bus\EventBusPortInterface;
use DateTimeImmutable;
use Domain\Client\Event\ClientRegisteredDomainEvent;
use Domain\Client\VO\ClientEmail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Infrastructure\Bus\OutboxMessagePublisher;
use Override;
use Tests\TestCase;

final class OutboxFlowTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            self::markTestSkipped('pdo_sqlite is not available in the current PHP runtime.');
        }

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');
        Artisan::call('migrate:fresh');
    }

    public function test_it_stores_and_publishes_events_from_outbox(): void
    {
        Event::fake();

        /** @var EventBusPortInterface $eventBus */
        $eventBus = $this->app->make(EventBusPortInterface::class);
        /** @var OutboxMessagePublisher $publisher */
        $publisher = $this->app->make(OutboxMessagePublisher::class);
        $event = new ClientRegisteredDomainEvent(
            clientId: 17,
            fullName: 'John Doe',
            email: 'john@example.com',
            phone: '+123456789',
            userId: 10,
            occurredOn: new DateTimeImmutable('2026-04-07T10:20:30+00:00'),
        );

        $eventBus->dispatch($event);

        $this->assertDatabaseCount('outbox_messages', 1);
        $this->assertDatabaseHas('outbox_messages', [
            'event_class' => ClientRegisteredDomainEvent::class,
            'published_at' => null,
        ]);

        self::assertSame(1, $publisher->publishPending());

        Event::assertDispatched(ClientRegisteredDomainEvent::class);
        self::assertNotNull(OutboxMessage::query()->first()?->published_at);
    }

    public function test_it_writes_client_domain_events_to_outbox_on_create(): void
    {
        /** @var ClientsWriteRepositoryInterface $repository */
        $repository = $this->app->make(ClientsWriteRepositoryInterface::class);

        $client = $repository->create(new ClientDTO(
            fullName: 'Jane Doe',
            email: new ClientEmail('jane@example.com'),
            phone: '+380123456789',
            userId: null,
        ));

        self::assertSame('Jane Doe', $client->getFullName());
        $this->assertDatabaseCount('clients', 1);
        $this->assertDatabaseCount('outbox_messages', 1);
        $this->assertDatabaseHas('outbox_messages', [
            'event_class' => ClientRegisteredDomainEvent::class,
            'published_at' => null,
        ]);
    }
}
