<?php

declare(strict_types=1);

namespace Infrastructure\Mappings\TelegramBots;

use App\Models\TelegramBot;
use Domain\TelegramBot\TelegramBot as DomainTelegramBot;
use Domain\TelegramBot\VO\TelegramBotToken;

final class GetTelegramBot
{
    public function fromModel(TelegramBot $bot): DomainTelegramBot
    {
        $token = new TelegramBotToken($bot->token);

        return new DomainTelegramBot(
            $token->botId(),
            $token,
            $bot->name,
        );
    }
}
