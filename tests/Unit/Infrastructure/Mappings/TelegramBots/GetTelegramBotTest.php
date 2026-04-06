<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Mappings\TelegramBots;

use App\Models\TelegramBot;
use Domain\TelegramBot\TelegramBot as DomainTelegramBot;
use Infrastructure\Mappings\TelegramBots\GetTelegramBot;
use PHPUnit\Framework\TestCase;

final class GetTelegramBotTest extends TestCase
{
    public function test_it_maps_model_to_domain_aggregate(): void
    {
        $bot = new TelegramBot();
        $bot->forceFill([
            'token' => '123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789',
            'name' => 'Main bot',
        ]);

        $domainBot = (new GetTelegramBot())->fromModel($bot);

        self::assertInstanceOf(DomainTelegramBot::class, $domainBot);
        self::assertSame('123456789', $domainBot->getBotId()->getValue());
        self::assertSame('123456789:ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789', $domainBot->getToken()->getValue());
        self::assertSame('Main bot', $domainBot->getName());
    }
}
