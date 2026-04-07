<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Bus;

use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Infrastructure\Bus\OutboxEventBusAdapter;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class OutboxEventBusAdapterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_stores_event_in_outbox(): void
    {
        /** @var OutboxMessageRepositoryInterface&MockInterface $outboxMessages */
        $outboxMessages = Mockery::mock(OutboxMessageRepositoryInterface::class);
        $event = new \stdClass();

        /** @var Expectation $expectation */
        $expectation = $outboxMessages->shouldReceive('add');

        $expectation
            ->once()
            ->with($event)
            ->andReturnNull();

        $adapter = new OutboxEventBusAdapter($outboxMessages);

        self::assertNull($adapter->dispatch($event));
    }

    public function test_it_stores_all_events_in_outbox(): void
    {
        /** @var OutboxMessageRepositoryInterface&MockInterface $outboxMessages */
        $outboxMessages = Mockery::mock(OutboxMessageRepositoryInterface::class);
        $events = [new \stdClass(), new \stdClass()];

        /** @var Expectation $expectation */
        $expectation = $outboxMessages->shouldReceive('addAll');

        $expectation
            ->once()
            ->with($events)
            ->andReturnNull();

        $adapter = new OutboxEventBusAdapter($outboxMessages);

        $adapter->dispatchAll($events);
    }
}
