<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Command;

use Application\Command\ClientRegisterCommand;
use PHPUnit\Framework\TestCase;

final class ClientRegisterCommandTest extends TestCase
{
    public function test_it_exposes_given_payload(): void
    {
        $command = new ClientRegisterCommand(
            clientId: 17,
            name: 'John Doe',
            email: 'john@example.com',
            phone: '+123456789',
            location: 'Simferopol',
        );

        self::assertSame(17, $command->clientId);
        self::assertSame('John Doe', $command->name);
        self::assertSame('john@example.com', $command->email);
        self::assertSame('+123456789', $command->phone);
        self::assertSame('Simferopol', $command->location);
    }
}
