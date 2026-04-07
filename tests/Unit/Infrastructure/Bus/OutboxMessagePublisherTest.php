<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Bus;

use Application\DTO\OutboxMessage;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use DateTimeImmutable;
use Illuminate\Contracts\Events\Dispatcher;
use Infrastructure\Bus\LaravelEventBusAdapter;
use Infrastructure\Bus\OutboxMessagePublisher;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class OutboxMessagePublisherTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_dispatches_pending_messages_and_marks_them_as_published(): void
    {
        /** @var OutboxMessageRepositoryInterface&MockInterface $outboxMessages */
        $outboxMessages = Mockery::mock(OutboxMessageRepositoryInterface::class);
        /** @var Dispatcher&MockInterface $dispatcher */
        $dispatcher = Mockery::mock(Dispatcher::class);
        $event = new \stdClass();
        $message = new OutboxMessage(
            id: 'message-id',
            eventClass: $event::class,
            event: $event,
            occurredOn: new DateTimeImmutable(),
        );

        /** @var Expectation $pendingExpectation */
        $pendingExpectation = $outboxMessages->shouldReceive('pending');
        $pendingExpectation->once()->with(50)->andReturn([$message]);

        /** @var Expectation $dispatchExpectation */
        $dispatchExpectation = $dispatcher->shouldReceive('dispatch');
        $dispatchExpectation->once()->with($event)->andReturnNull();

        /** @var Expectation $markExpectation */
        $markExpectation = $outboxMessages->shouldReceive('markAsPublished');
        $markExpectation->once()->with('message-id')->andReturnNull();

        $publisher = new OutboxMessagePublisher($outboxMessages, new LaravelEventBusAdapter($dispatcher));

        self::assertSame(1, $publisher->publishPending(50));
    }
}
