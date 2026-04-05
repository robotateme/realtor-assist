<?php

namespace Application\Port\Persistence;

use Closure;

interface MigrationsPort
{
    /**
     * @param Closure(\Illuminate\Database\Schema\Blueprint):void $callback
     */
    public function create(string $table, Closure $callback): void;

    public function dropIfExists(string $table): void;

    /**
     * @param Closure(\Illuminate\Database\Schema\Blueprint):void $callback
     */
    public function table(string $table, Closure $callback): void;

    public function hasTable(string $table): bool;
}
