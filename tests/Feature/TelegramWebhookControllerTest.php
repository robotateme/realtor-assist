<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\MessengerClient;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Override;
use Tests\TestCase;

final class TelegramWebhookControllerTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        if (! extension_loaded('pdo_sqlite')) {
            self::markTestSkipped('pdo_sqlite is not available in the current PHP runtime.');
        }

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');
        Artisan::call('migrate:fresh');
    }

    public function test_it_registers_client_on_start_command(): void
    {
        Telegraph::shouldReceive('chat')->once()->with(987654321)->andReturnSelf();
        Telegraph::shouldReceive('message')->once()->with('Регистрация в мессенджере завершена.')->andReturnSelf();
        Telegraph::shouldReceive('send')->once();

        $response = $this->postJson('/api/v1/webhooks/telegram/test-token', [
            'message' => [
                'message_id' => 1,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'username' => 'john_doe',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'chat' => [
                    'id' => 987654321,
                    'type' => 'private',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'date' => 1710000000,
                'text' => '/start',
            ],
            'update_id' => 10000,
        ]);

        $response->assertOk();
        $response->assertContent('"987654321"');
        $this->assertDatabaseCount('messenger_clients', 1);
        $this->assertDatabaseCount('clients', 0);
        $this->assertDatabaseCount('dialog_sessions', 0);
        $this->assertDatabaseHas('messenger_clients', [
            'client_id' => null,
            'provider' => 'telegram',
            'username' => 'john_doe',
            'messenger_id' => '123456789',
        ]);
    }

    public function test_it_does_not_duplicate_client_on_repeated_start_command(): void
    {
        MessengerClient::query()->create([
            'client_id' => null,
            'provider' => 'telegram',
            'username' => 'john_doe',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'is_bot' => false,
            'messenger_id' => '123456789',
        ]);

        Telegraph::shouldReceive('chat')->once()->with(987654321)->andReturnSelf();
        Telegraph::shouldReceive('message')->once()->with('Вы уже зарегистрированы.')->andReturnSelf();
        Telegraph::shouldReceive('send')->once();

        $response = $this->postJson('/api/v1/webhooks/telegram/test-token', [
            'message' => [
                'message_id' => 1,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
                    'username' => 'john_doe',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'chat' => [
                    'id' => 987654321,
                    'type' => 'private',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                ],
                'date' => 1710000000,
                'text' => '/start',
            ],
            'update_id' => 10000,
        ]);

        $response->assertOk();
        self::assertSame(1, MessengerClient::query()->count());
        self::assertSame(0, DB::table('clients')->count());
        self::assertSame(0, DB::table('dialog_sessions')->count());
    }
}
