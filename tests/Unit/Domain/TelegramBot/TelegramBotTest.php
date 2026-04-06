<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\TelegramBot;

use Domain\Shared\DomainException;
use Domain\TelegramBot\TelegramBot;
use Domain\TelegramBot\VO\TelegramBotId;
use Domain\TelegramBot\VO\TelegramBotToken;
use PHPUnit\Framework\TestCase;

final class TelegramBotTest extends TestCase
{
    public function test_it_accepts_matching_bot_id_and_token(): void
    {
        $bot = new TelegramBot(
            new TelegramBotId('123456789'),
            new TelegramBotToken('123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789'),
            'Main bot',
        );

        self::assertSame('123456789', $bot->getBotId()->getValue());
        self::assertSame('123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789', $bot->getToken()->getValue());
        self::assertSame('Main bot', $bot->getName());
    }

    public function test_it_rejects_mismatched_bot_id_and_token(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Telegram bot id does not match token bot id');

        new TelegramBot(
            new TelegramBotId('1'),
            new TelegramBotToken('123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789'),
        );
    }
}
