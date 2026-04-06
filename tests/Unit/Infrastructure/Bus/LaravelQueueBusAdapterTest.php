<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Bus;

use Illuminate\Contracts\Bus\Dispatcher;
use Infrastructure\Bus\LaravelQueueBusAdapter;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class LaravelQueueBusAdapterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_dispatches_command_through_laravel_dispatcher(): void
    {
        /** @var Dispatcher&MockInterface $dispatcher */
        $dispatcher = Mockery::mock(Dispatcher::class);
        $command = new \stdClass();

        /** @var Expectation $expectation */
        $expectation = $dispatcher->shouldReceive('dispatch');

        $expectation
            ->once()
            ->with($command)
            ->andReturn('dispatched');

        $adapter = new LaravelQueueBusAdapter($dispatcher);

        self::assertSame('dispatched', $adapter->dispatch($command));
    }
}
