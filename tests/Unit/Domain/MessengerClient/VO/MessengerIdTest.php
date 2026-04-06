<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\MessengerClient\VO;

use Domain\MessengerClient\MessengerProviderEnum;
use Domain\MessengerClient\VO\MessengerId;
use Domain\Shared\DomainException;
use PHPUnit\Framework\TestCase;

final class MessengerIdTest extends TestCase
{
    public function test_it_accepts_numeric_telegram_identifier(): void
    {
        $messengerId = new MessengerId(MessengerProviderEnum::TELEGRAM, '123456789');

        self::assertSame(MessengerProviderEnum::TELEGRAM, $messengerId->getProvider());
        self::assertSame('123456789', $messengerId->getValue());
        self::assertSame('123456789', (string) $messengerId);
    }

    public function test_it_rejects_non_numeric_telegram_identifier(): void
    {
        $this->expectException(DomainException::class);

        new MessengerId(MessengerProviderEnum::TELEGRAM, 'tg-user-1');
    }

    public function test_it_accepts_uuid_like_identifier_for_non_telegram_provider(): void
    {
        $messengerId = new MessengerId(
            MessengerProviderEnum::WHATSAPP,
            '550e8400-e29b-41d4-a716-446655440000',
        );

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $messengerId->getValue());
    }
}
