<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Repositories;

use Application\DTO\ClientDTO;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Domain\Client\VO\ClientEmail;
use Domain\Shared\DomainException;
use Infrastructure\Repositories\ClientsWriteRepository;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class ClientsWriteRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_update_one_requires_client_id(): void
    {
        $repository = new ClientsWriteRepository();
        $dto = new ClientDTO('John Doe', new ClientEmail('client@example.com'), '+123456789');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Client id is required for update');

        $repository->updateOne($dto);
    }

    public function test_create_can_be_instantiated_with_outbox_repository_dependency(): void
    {
        /** @var OutboxMessageRepositoryInterface&MockInterface $outboxMessages */
        $outboxMessages = Mockery::mock(OutboxMessageRepositoryInterface::class);
        /** @var Expectation $expectation */
        $expectation = $outboxMessages->shouldReceive('addAll');
        $expectation->zeroOrMoreTimes();

        $repository = new ClientsWriteRepository($outboxMessages);

        self::assertInstanceOf(ClientsWriteRepository::class, $repository);
    }
}
