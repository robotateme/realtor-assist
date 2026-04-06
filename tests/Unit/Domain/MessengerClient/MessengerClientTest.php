<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\MessengerClient;

use Domain\MessengerClient\MessengerClient;
use Domain\MessengerClient\MessengerProviderEnum;
use Domain\MessengerClient\VO\MessengerId;
use Domain\Shared\DomainException;
use PHPUnit\Framework\TestCase;

final class MessengerClientTest extends TestCase
{
    public function test_it_accepts_matching_provider_and_messenger_id_provider(): void
    {
        $messengerId = new MessengerId(MessengerProviderEnum::TELEGRAM, '123456789');
        $client = new MessengerClient(
            MessengerProviderEnum::TELEGRAM,
            'johndoe',
            'John',
            'Doe',
            '42',
            $messengerId,
            false,
        );

        self::assertSame(MessengerProviderEnum::TELEGRAM, $client->getProvider());
        self::assertSame($messengerId, $client->getMessengerId());
    }

    public function test_it_rejects_mismatched_provider_and_messenger_id_provider(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Messenger provider does not match messenger id provider');

        new MessengerClient(
            MessengerProviderEnum::WHATSAPP,
            'johndoe',
            'John',
            'Doe',
            '42',
            new MessengerId(MessengerProviderEnum::TELEGRAM, '123456789'),
            false,
        );
    }
}
