<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class TelegramWebhookControllerTest extends TestCase
{
    public function test_it_returns_no_content_response(): void
    {
        $response = $this->get('/api/v1/webhooks/telegram/debug');

        $response->assertOk();
    }
}
