<?php

declare(strict_types=1);

namespace Tests\Feature;

use DefStudio\Telegraph\Facades\Telegraph;
use Tests\TestCase;

final class TelegramWebhookControllerTest extends TestCase
{
    public function test_it_returns_chat_id_response_for_valid_webhook(): void
    {
        Telegraph::shouldReceive('chat')->once()->with(987654321)->andReturnSelf();
        Telegraph::shouldReceive('message')->once()->with('Hello world!')->andReturnSelf();
        Telegraph::shouldReceive('send')->once();

        $response = $this->postJson('/api/v1/webhooks/telegram/test-token', [
            'message' => [
                'message_id' => 1,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => false,
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
    }
}
