<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Bus;

use Application\Command\MessengerWebhookCommand;
use Application\DTO\MessengerClientDTO;
use Illuminate\Contracts\Bus\Dispatcher;
use Infrastructure\Bus\LaravelQueueBusAdapter;
use Infrastructure\Bus\Jobs\MessengerWebhookJob;
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

    public function test_it_wraps_messenger_webhook_command_into_laravel_job(): void
    {
        /** @var Dispatcher&MockInterface $dispatcher */
        $dispatcher = Mockery::mock(Dispatcher::class);
        $command = new MessengerWebhookCommand(
            messengerClientDTO: new MessengerClientDTO(
                username: 'john_doe',
                firstName: 'John',
                lastName: 'Doe',
                isBot: false,
                messengerId: '123456789',
                clientId: null,
            ),
            chatId: 123,
            messageText: '/start',
        );

        /** @var Expectation $expectation */
        $expectation = $dispatcher->shouldReceive('dispatch');

        $expectation
            ->once()
            ->withArgs(static function (object $job) use ($command): bool {
                return $job instanceof MessengerWebhookJob
                    && $job->command === $command;
            })
            ->andReturn('queued');

        $adapter = new LaravelQueueBusAdapter($dispatcher);

        self::assertSame('queued', $adapter->dispatch($command));
    }
}
