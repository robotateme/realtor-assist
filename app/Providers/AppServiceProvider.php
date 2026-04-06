<?php

declare(strict_types=1);

namespace App\Providers;

use Application\Command\Repositories\DB\ClientsWriteRepositoryInterface;
use Application\Port\Bus\QueueBusPortInterface;
use Application\Port\Persistence\MigrationsPortInterface;
use Application\Port\Persistence\RelationsPortInterface;
use Application\Query\Clients\Repositories\DB\ClientsReadRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Bus\LaravelQueueBusAdapter;
use Infrastructure\Mappings\Clients\GetClients;
use Infrastructure\Persistence\Migrations\LaravelMigrationsAdapter;
use Infrastructure\Persistence\Relations\EloquentRelationsAdapterInterface;
use Infrastructure\Persistence\Repositories\EloquentFilterContext;
use Infrastructure\Repositories\ClientsReadRepository;
use Infrastructure\Repositories\ClientsWriteRepository;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->singleton(QueueBusPortInterface::class, LaravelQueueBusAdapter::class);
        $this->app->singleton(MigrationsPortInterface::class, LaravelMigrationsAdapter::class);
        $this->app->singleton(RelationsPortInterface::class, EloquentRelationsAdapterInterface::class);
        $this->app->singleton(ClientsReadRepositoryInterface::class, function () {
            return new ClientsReadRepository(
                new EloquentFilterContext(),
                new GetClients(),
            );
        });
        $this->app->singleton(ClientsWriteRepositoryInterface::class, ClientsWriteRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
