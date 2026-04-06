<?php

declare(strict_types=1);

namespace Tests\Unit\App\Providers;

use Application\Port\Persistence\RelationsPortInterface;
use Application\Query\Clients\Repositories\ClientsReadRepositoryInterface;
use Application\Query\Clients\Repositories\ClientsWriteRepositoryInterface;
use Infrastructure\Persistence\Relations\EloquentRelationsAdapterInterface;
use Infrastructure\Repositories\ClientsReadRepository;
use Infrastructure\Repositories\ClientsWriteRepository;
use Tests\TestCase;

final class AppServiceProviderTest extends TestCase
{
    public function test_it_binds_relations_port_to_eloquent_adapter(): void
    {
        $service = $this->app->make(RelationsPortInterface::class);

        self::assertInstanceOf(EloquentRelationsAdapterInterface::class, $service);
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
}
