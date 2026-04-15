<?php

declare(strict_types=1);

namespace Application\Command;

use Application\Command\Repositories\DB\MessengerClientsReadRepositoryInterface;
use Application\Command\Repositories\DB\MessengerClientsWriteRepositoryInterface;
use Application\Port\Messenger\MessengerMessageSenderInterface;
use Domain\MessengerClient\MessengerProviderEnum;

/**
 *
 */
final readonly class MessengerWebhookHandler
{
    public function __construct(
        private MessengerClientsReadRepositoryInterface $messengerClientsReadRepository,
        private MessengerClientsWriteRepositoryInterface $messengerClientsWriteRepository,
        private MessengerMessageSenderInterface $messengerMessageSender,
    ) {
    }

    public function handle(MessengerWebhookCommand $command): void
    {
        $this->messengerMessageSender->send(
            $command->chatId,
            $this->resolveResponseMessage($command),
        );
    }

    private function resolveResponseMessage(MessengerWebhookCommand $command): string
    {
        if ($command->messageText !== '/start') {
            return 'Команда пока не поддерживается.';
        }

        if ($this->messengerClientsReadRepository->existsByMessengerId(
            MessengerProviderEnum::TELEGRAM,
            $command->messengerClientDTO->messengerId,
        )) {
            return 'Вы уже зарегистрированы.';
        }

        $this->messengerClientsWriteRepository->create(
            MessengerProviderEnum::TELEGRAM,
            $command->messengerClientDTO,
        );

        return 'Регистрация в мессенджере завершена.';
    }
}
