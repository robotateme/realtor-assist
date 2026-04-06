<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

final class ApiRoutesTest extends TestCase
{
    public function test_api_user_route_is_registered(): void
    {
        $route = Route::getRoutes()->match(Request::create('/api/user', 'GET'));

        self::assertSame('api/user', $route->uri());
        self::assertContains('GET', $route->methods());
    }

    public function test_telegram_webhook_route_is_registered(): void
    {
        $route = Route::getRoutes()->match(Request::create('/api/v1/webhooks/telegram/test-token', 'POST'));

        self::assertSame('api/v1/webhooks/telegram/{token}', $route->uri());
        self::assertContains('POST', $route->methods());
    }
}
