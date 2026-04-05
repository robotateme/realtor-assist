<?php

namespace App\Providers;

use Application\Port\Persistence\MigrationsPort;
use Application\Port\Persistence\ReadRepositoryFactoryPort;
use Application\Port\Persistence\RelationsPort;
use Application\Port\Persistence\WriteRepositoryFactoryPort;
use Infrastructure\Persistence\Migrations\LaravelMigrationsAdapter;
use Infrastructure\Persistence\Repositories\EloquentReadRepositoryFactory;
use Infrastructure\Persistence\Repositories\EloquentWriteRepositoryFactory;
use Infrastructure\Persistence\Relations\EloquentRelationsAdapter;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RelationsPort::class, EloquentRelationsAdapter::class);
        $this->app->singleton(MigrationsPort::class, LaravelMigrationsAdapter::class);
        $this->app->singleton(ReadRepositoryFactoryPort::class, EloquentReadRepositoryFactory::class);
        $this->app->singleton(WriteRepositoryFactoryPort::class, EloquentWriteRepositoryFactory::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
