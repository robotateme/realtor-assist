<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use Application\DTO\ClientDTO;
use Domain\Client\VO\ClientEmail;
use Domain\Shared\DomainException;
use Infrastructure\Repositories\ClientsWriteRepository;
use PHPUnit\Framework\TestCase;

final class ClientsWriteRepositoryTest extends TestCase
{
    public function test_update_one_requires_client_id(): void
    {
        $repository = new ClientsWriteRepository();
        $dto = new ClientDTO('John Doe', new ClientEmail('client@example.com'), '+123456789');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Client id is required for update');

        $repository->updateOne($dto);
    }
}
