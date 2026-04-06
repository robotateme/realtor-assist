<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Migrations;

use Application\Port\Persistence\MigrationsPortInterface;
use Illuminate\Database\Migrations\Migration;

/**
 *
 */
abstract class InfrastructureMigration extends Migration
{
    final protected function schema(): MigrationsPortInterface
    {
        /** @var MigrationsPortInterface $migrations */
        $migrations = app(MigrationsPortInterface::class);

        return $migrations;
    }
}
