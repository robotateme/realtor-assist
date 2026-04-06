<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\TelegramBot\VO;

use Domain\Shared\DomainException;
use Domain\TelegramBot\VO\TelegramBotId;
use Domain\TelegramBot\VO\TelegramBotToken;
use PHPUnit\Framework\TestCase;

final class TelegramBotTokenTest extends TestCase
{
    public function test_it_accepts_valid_token_and_extracts_bot_id(): void
    {
        $token = new TelegramBotToken('123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789');

        self::assertSame('123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789', $token->getValue());
        self::assertSame('123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789', (string) $token);
        self::assertEquals(new TelegramBotId('123456789'), $token->botId());
    }

    public function test_it_rejects_invalid_token_format(): void
    {
        $this->expectException(DomainException::class);

        new TelegramBotToken('invalid-token');
    }
}
