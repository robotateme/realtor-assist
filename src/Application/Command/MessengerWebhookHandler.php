<?php

declare(strict_types=1);

namespace Application\Command;

use App\Models\MessengerClient;
use DefStudio\Telegraph\Facades\Telegraph;
use Domain\MessengerClient\MessengerProviderEnum;

final class MessengerWebhookHandler
{
    public function handle(MessengerWebhookCommand $command): void
    {
        Telegraph::chat((string) $command->chatId)->message($this->resolveResponseMessage($command))->send();
    }

    private function resolveResponseMessage(MessengerWebhookCommand $command): string
    {
        if ($command->messageText !== '/start') {
            return 'Команда пока не поддерживается.';
        }

        /** @var MessengerClient|null $messengerClient */
        $messengerClient = MessengerClient::query()
            ->where('provider', MessengerProviderEnum::TELEGRAM->value)
            ->where('messenger_id', $command->messengerClientDTO->messengerId)
            ->first();

        if ($messengerClient !== null) {
            return 'Вы уже зарегистрированы.';
        }

        MessengerClient::query()->create([
            'client_id' => null,
            'provider' => MessengerProviderEnum::TELEGRAM->value,
            'username' => $command->messengerClientDTO->username,
            'first_name' => $command->messengerClientDTO->firstName,
            'last_name' => $command->messengerClientDTO->lastName,
            'is_bot' => $command->messengerClientDTO->isBot,
            'messenger_id' => $command->messengerClientDTO->messengerId,
        ]);

        return 'Регистрация в мессенджере завершена.';
    }
}
