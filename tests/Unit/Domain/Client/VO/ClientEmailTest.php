<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Client\VO;

use Domain\Client\VO\ClientEmail;
use Domain\Shared\DomainException;
use PHPUnit\Framework\TestCase;

final class ClientEmailTest extends TestCase
{
    public function test_it_accepts_valid_email(): void
    {
        $email = new ClientEmail('jane@example.com');

        self::assertSame('jane@example.com', $email->getValue());
        self::assertSame('jane@example.com', (string) $email);
    }

    public function test_it_rejects_empty_email(): void
    {
        $this->expectException(DomainException::class);

        new ClientEmail('');
    }

    public function test_it_rejects_invalid_email(): void
    {
        $this->expectException(DomainException::class);

        new ClientEmail('invalid-email');
    }
}
