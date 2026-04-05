<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Infrastructure\Mappings\Clients\GetClients;
use Infrastructure\Persistence\Repositories\ClientsReadRepositoryInterface;
use Infrastructure\Persistence\Repositories\EloquentFilterContext;
use Infrastructure\Repositories\ClientsReadRepository;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->singleton(ClientsReadRepositoryInterface::class, function () {
            return new ClientsReadRepository(
                new EloquentFilterContext(),
                new GetClients(),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
