<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\MessengerClient;

use Domain\MessengerClient\MessengerProviderEnum;
use PHPUnit\Framework\TestCase;

final class MessengerProviderEnumTest extends TestCase
{
    public function test_it_contains_expected_backed_values(): void
    {
        self::assertSame('telegram', MessengerProviderEnum::TELEGRAM->value);
        self::assertSame('facebook', MessengerProviderEnum::FACEBOOK->value);
        self::assertCount(5, MessengerProviderEnum::cases());
    }
}
