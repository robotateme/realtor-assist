<?php

declare(strict_types=1);

namespace Tests\Unit\Application\DTO;

use Application\DTO\ClientDTO;
use Domain\Client\VO\ClientEmail;
use PHPUnit\Framework\TestCase;

final class ClientDTOTest extends TestCase
{
    public function test_it_exposes_given_data(): void
    {
        $email = new ClientEmail('client@example.com');
        $dto = new ClientDTO('John Doe', $email, '+123456789', 7, 11);

        self::assertSame('John Doe', $dto->fullName);
        self::assertSame($email, $dto->email);
        self::assertSame('+123456789', $dto->phone);
        self::assertSame(7, $dto->userId);
        self::assertSame(11, $dto->id);
    }
}
