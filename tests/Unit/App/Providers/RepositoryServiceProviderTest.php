<?php

declare(strict_types=1);

namespace Tests\Unit\App\Providers;

use Application\Command\Repositories\DB\ClientsWriteRepositoryInterface;
use Application\Command\Repositories\DB\MessengerClientsReadRepositoryInterface;
use Application\Command\Repositories\DB\MessengerClientsWriteRepositoryInterface;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Application\Query\Clients\Repositories\DB\ClientsReadRepositoryInterface;
use Infrastructure\Persistence\Outbox\EloquentOutboxMessageRepository;
use Infrastructure\Repositories\ClientsReadRepository;
use Infrastructure\Repositories\ClientsWriteRepository;
use Infrastructure\Repositories\MessengerClientsReadRepository;
use Infrastructure\Repositories\MessengerClientsWriteRepository;
use Tests\TestCase;

final class RepositoryServiceProviderTest extends TestCase
{
    public function test_it_binds_outbox_message_repository(): void
    {
        $service = $this->app->make(OutboxMessageRepositoryInterface::class);

        self::assertInstanceOf(EloquentOutboxMessageRepository::class, $service);
    }

    public function test_it_binds_clients_read_repository_interface(): void
    {
        $service = $this->app->make(ClientsReadRepositoryInterface::class);

        self::assertInstanceOf(ClientsReadRepository::class, $service);
    }

    public function test_it_binds_clients_write_repository_interface(): void
    {
        $service = $this->app->make(ClientsWriteRepositoryInterface::class);

        self::assertInstanceOf(ClientsWriteRepository::class, $service);
    }

    public function test_it_binds_messenger_clients_repositories(): void
    {
        $readRepository = $this->app->make(MessengerClientsReadRepositoryInterface::class);
        $writeRepository = $this->app->make(MessengerClientsWriteRepositoryInterface::class);

        self::assertInstanceOf(MessengerClientsReadRepository::class, $readRepository);
        self::assertInstanceOf(MessengerClientsWriteRepository::class, $writeRepository);
    }
}
