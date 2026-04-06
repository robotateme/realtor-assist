<?php

declare(strict_types=1);

namespace Domain\TelegramBot;

use Domain\Shared\DomainException;
use Domain\TelegramBot\VO\TelegramBotId;
use Domain\TelegramBot\VO\TelegramBotToken;

final readonly class TelegramBot
{
    /**
     * @throws DomainException
     */
    public function __construct(
        private TelegramBotId $botId,
        private TelegramBotToken $token,
        private ?string $name = null,
    ) {
        if ((string) $this->token->botId() !== (string) $this->botId) {
            throw new DomainException('Telegram bot id does not match token bot id');
        }
    }

    public function getBotId(): TelegramBotId
    {
        return $this->botId;
    }

    public function getToken(): TelegramBotToken
    {
        return $this->token;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
