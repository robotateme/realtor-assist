<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\TelegramBot\VO;

use Domain\Shared\DomainException;
use Domain\TelegramBot\VO\TelegramBotId;
use PHPUnit\Framework\TestCase;

final class TelegramBotIdTest extends TestCase
{
    public function test_it_accepts_numeric_identifier(): void
    {
        $botId = new TelegramBotId('123456789');

        self::assertSame('123456789', $botId->getValue());
        self::assertSame('123456789', (string) $botId);
    }

    public function test_it_rejects_non_numeric_identifier(): void
    {
        $this->expectException(DomainException::class);

        new TelegramBotId('bot-123');
    }
}
