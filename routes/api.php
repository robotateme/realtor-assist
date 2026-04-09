<?php

use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::prefix('webhooks')->group(function () {
        Route::post('telegram/{token}', TelegramWebhookController::class)
            ->name('telegraph.webhook');
    });
});
