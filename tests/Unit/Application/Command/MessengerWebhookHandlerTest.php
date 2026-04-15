<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Command;

use Application\Command\MessengerWebhookCommand;
use Application\Command\MessengerWebhookHandler;
use Application\Command\Repositories\DB\MessengerClientsReadRepositoryInterface;
use Application\Command\Repositories\DB\MessengerClientsWriteRepositoryInterface;
use Application\DTO\MessengerClientDTO;
use Application\Port\Messenger\MessengerMessageSenderInterface;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use Mockery;
use Override;
use Tests\TestCase;

final class MessengerWebhookHandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'realtor-assist.queues.webhook' => 'webhook',
        ]);
    }

    public function test_it_registers_new_messenger_client_via_ports(): void
    {
        /** @var MessengerClientsReadRepositoryInterface&MockInterface $readRepository */
        $readRepository = Mockery::mock(MessengerClientsReadRepositoryInterface::class);
        /** @var MessengerClientsWriteRepositoryInterface&MockInterface $writeRepository */
        $writeRepository = Mockery::mock(MessengerClientsWriteRepositoryInterface::class);
        /** @var MessengerMessageSenderInterface&MockInterface $messageSender */
        $messageSender = Mockery::mock(MessengerMessageSenderInterface::class);

        /** @var Expectation $readExpectation */
        $readExpectation = $readRepository->shouldReceive('existsByMessengerId');
        $readExpectation
            ->once()
            ->andReturn(false);
        /** @var Expectation $writeExpectation */
        $writeExpectation = $writeRepository->shouldReceive('create');
        $writeExpectation
            ->once();
        /** @var Expectation $sendExpectation */
        $sendExpectation = $messageSender->shouldReceive('send');
        $sendExpectation
            ->once()
            ->with(123, 'Регистрация в мессенджере завершена.');

        $handler = new MessengerWebhookHandler($readRepository, $writeRepository, $messageSender);

        $handler->handle(new MessengerWebhookCommand(
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
        ));
    }

    public function test_it_does_not_create_duplicate_messenger_client(): void
    {
        /** @var MessengerClientsReadRepositoryInterface&MockInterface $readRepository */
        $readRepository = Mockery::mock(MessengerClientsReadRepositoryInterface::class);
        /** @var MessengerClientsWriteRepositoryInterface&MockInterface $writeRepository */
        $writeRepository = Mockery::mock(MessengerClientsWriteRepositoryInterface::class);
        /** @var MessengerMessageSenderInterface&MockInterface $messageSender */
        $messageSender = Mockery::mock(MessengerMessageSenderInterface::class);

        /** @var Expectation $readExpectation */
        $readExpectation = $readRepository->shouldReceive('existsByMessengerId');
        $readExpectation
            ->once()
            ->andReturn(true);
        $writeRepository
            ->shouldNotReceive('create');
        /** @var Expectation $sendExpectation */
        $sendExpectation = $messageSender->shouldReceive('send');
        $sendExpectation
            ->once()
            ->with(123, 'Вы уже зарегистрированы.');

        $handler = new MessengerWebhookHandler($readRepository, $writeRepository, $messageSender);

        $handler->handle(new MessengerWebhookCommand(
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
        ));
    }
}
