<?php

namespace Tests\Feature\Infrastructure;

use App\Models\Client;
use App\Models\DialogSession;
use App\Models\Property;
use App\Models\User;
use App\Models\Viewing;
use Application\Port\Persistence\MigrationsPort;
use Application\Port\Persistence\RelationsPort;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Schema\Blueprint;
use Infrastructure\Persistence\Migrations\LaravelMigrationsAdapter;
use Infrastructure\Persistence\Relations\EloquentRelationsAdapter;
use Tests\TestCase;

final class InfrastructurePortsTest extends TestCase
{
    public function test_ports_are_bound_to_laravel_adapters(): void
    {
        self::assertInstanceOf(EloquentRelationsAdapter::class, app(RelationsPort::class));
        self::assertInstanceOf(LaravelMigrationsAdapter::class, app(MigrationsPort::class));
    }

    public function test_models_use_relations_port_to_build_relations(): void
    {
        $client = new Client();
        $property = new Property();
        $viewing = new Viewing();
        $dialogSession = new DialogSession();
        $user = new User();

        self::assertInstanceOf(BelongsTo::class, $client->user());
        self::assertInstanceOf(HasMany::class, $client->viewings());
        self::assertInstanceOf(HasOne::class, $client->dialogSession());
        self::assertInstanceOf(HasMany::class, $property->viewings());
        self::assertInstanceOf(BelongsTo::class, $viewing->client());
        self::assertInstanceOf(BelongsTo::class, $viewing->property());
        self::assertInstanceOf(BelongsTo::class, $dialogSession->client());
        self::assertInstanceOf(HasMany::class, $user->clients());
    }

    public function test_migrations_port_can_create_and_drop_tables(): void
    {
        $table = 'port_test_table';
        $migrations = app(MigrationsPort::class);

        $migrations->dropIfExists($table);

        $migrations->create($table, static function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->string('name');
        });

        self::assertTrue($migrations->hasTable($table));

        $migrations->dropIfExists($table);

        self::assertFalse($migrations->hasTable($table));
    }
}
