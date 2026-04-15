<?php

declare(strict_types=1);

namespace App\Providers;

use Application\Command\Repositories\DB\ClientsWriteRepositoryInterface;
use Application\Command\Repositories\DB\MessengerClientsReadRepositoryInterface;
use Application\Command\Repositories\DB\MessengerClientsWriteRepositoryInterface;
use Application\Port\Persistence\MigrationsPortInterface;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Application\Port\Persistence\RelationsPortInterface;
use Application\Query\Clients\Repositories\DB\ClientsReadRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Mappings\Clients\GetClients;
use Infrastructure\Persistence\Migrations\LaravelMigrationsAdapter;
use Infrastructure\Persistence\Outbox\EloquentOutboxMessageRepository;
use Infrastructure\Persistence\Relations\EloquentRelationsAdapterInterface;
use Infrastructure\Persistence\Repositories\EloquentFilterContext;
use Infrastructure\Repositories\ClientsReadRepository;
use Infrastructure\Repositories\ClientsWriteRepository;
use Infrastructure\Repositories\MessengerClientsReadRepository;
use Infrastructure\Repositories\MessengerClientsWriteRepository;
use Override;

final class RepositoryServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->singleton(OutboxMessageRepositoryInterface::class, EloquentOutboxMessageRepository::class);
        $this->app->singleton(MigrationsPortInterface::class, LaravelMigrationsAdapter::class);
        $this->app->singleton(RelationsPortInterface::class, EloquentRelationsAdapterInterface::class);
        $this->app->singleton(ClientsReadRepositoryInterface::class, function () {
            return new ClientsReadRepository(
                new EloquentFilterContext(),
                new GetClients(),
            );
        });
        $this->app->singleton(ClientsWriteRepositoryInterface::class, ClientsWriteRepository::class);
        $this->app->singleton(MessengerClientsReadRepositoryInterface::class, MessengerClientsReadRepository::class);
        $this->app->singleton(MessengerClientsWriteRepositoryInterface::class, MessengerClientsWriteRepository::class);
    }
}
