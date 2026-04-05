<?php

namespace Infrastructure\Persistence\Migrations;

use Application\Port\Persistence\MigrationsPort;
use Illuminate\Database\Migrations\Migration;

abstract class InfrastructureMigration extends Migration
{
    final protected function schema(): MigrationsPort
    {
        /** @var MigrationsPort $migrations */
        $migrations = app(MigrationsPort::class);

        return $migrations;
    }
}
