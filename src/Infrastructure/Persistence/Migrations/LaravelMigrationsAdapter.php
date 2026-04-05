<?php

namespace Infrastructure\Persistence\Migrations;

use Application\Port\Persistence\MigrationsPort;
use Closure;
use Illuminate\Support\Facades\Schema;
use Override;

final class LaravelMigrationsAdapter implements MigrationsPort
{
    #[Override]
    public function create(string $table, Closure $callback): void
    {
        Schema::create($table, $callback);
    }

    #[Override]
    public function dropIfExists(string $table): void
    {
        Schema::dropIfExists($table);
    }

    #[Override]
    public function table(string $table, Closure $callback): void
    {
        Schema::table($table, $callback);
    }

    #[Override]
    public function hasTable(string $table): bool
    {
        return Schema::hasTable($table);
    }
}
