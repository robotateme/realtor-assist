<?php

declare(strict_types=1);

namespace Tests\Unit\App\Providers;

use Application\Port\Bus\EventBusPortInterface;
use Application\Port\Bus\QueueBusPortInterface;
use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\LLM\OllamaLegalAssistantClientInterface;
use Application\Port\Messenger\MessengerMessageSenderInterface;
use Application\Port\Outbox\EventSerializerInterface;
use Application\Port\Prompt\PromptRendererInterface;
use Application\Port\Prompt\PromptResolverInterface;
use Application\Port\RateLimit\RateLimiterInterface;
use Infrastructure\Bus\LaravelEventBusAdapter;
use Infrastructure\Bus\LaravelQueueBusAdapter;
use Infrastructure\Bus\OutboxEventBusAdapter;
use Infrastructure\Bus\OutboxMessagePublisher;
use Infrastructure\Http\OllamaHttpClient;
use Infrastructure\Http\RateLimitedOllamaHttpClient;
use Infrastructure\LLM\OllamaLegalAssistantClient;
use Infrastructure\Messenger\TelegramMessageSender;
use Infrastructure\Outbox\ReflectionEventSerializer;
use Infrastructure\Prompt\BladePromptRenderer;
use Infrastructure\Prompt\CompositePromptResolver;
use Infrastructure\RateLimit\RedisRateLimiter;
use Infrastructure\Redis\ScriptResolver;
use Tests\TestCase;

final class IntegrationsServiceProviderTest extends TestCase
{
    public function test_it_binds_event_bus_port_to_laravel_adapter(): void
    {
        $service = $this->app->make(EventBusPortInterface::class);
        $sameService = $this->app->make(EventBusPortInterface::class);

        self::assertInstanceOf(OutboxEventBusAdapter::class, $service);
        self::assertSame($service, $sameService);
    }

    public function test_it_binds_integration_dependencies(): void
    {
        $serializer = $this->app->make(EventSerializerInterface::class);
        $publisher = $this->app->make(OutboxMessagePublisher::class);
        $laravelEventBus = $this->app->make(LaravelEventBusAdapter::class);
        $ollamaHttpClient = $this->app->make(OllamaHttpClientInterface::class);
        $baseOllamaHttpClient = $this->app->make(OllamaHttpClient::class);
        $promptRenderer = $this->app->make(PromptRendererInterface::class);
        $promptResolver = $this->app->make(PromptResolverInterface::class);
        $ollamaLegalAssistant = $this->app->make(OllamaLegalAssistantClientInterface::class);
        $rateLimiter = $this->app->make(RateLimiterInterface::class);
        $scriptResolver = $this->app->make(ScriptResolver::class);

        self::assertInstanceOf(ReflectionEventSerializer::class, $serializer);
        self::assertInstanceOf(OutboxMessagePublisher::class, $publisher);
        self::assertInstanceOf(LaravelEventBusAdapter::class, $laravelEventBus);
        self::assertInstanceOf(RateLimitedOllamaHttpClient::class, $ollamaHttpClient);
        self::assertInstanceOf(OllamaHttpClient::class, $baseOllamaHttpClient);
        self::assertInstanceOf(BladePromptRenderer::class, $promptRenderer);
        self::assertInstanceOf(CompositePromptResolver::class, $promptResolver);
        self::assertInstanceOf(OllamaLegalAssistantClient::class, $ollamaLegalAssistant);
        self::assertInstanceOf(RedisRateLimiter::class, $rateLimiter);
        self::assertInstanceOf(ScriptResolver::class, $scriptResolver);
    }

    public function test_it_binds_queue_bus_port_to_laravel_adapter(): void
    {
        $service = $this->app->make(QueueBusPortInterface::class);

        self::assertInstanceOf(LaravelQueueBusAdapter::class, $service);
    }

    public function test_it_binds_messenger_message_sender(): void
    {
        $service = $this->app->make(MessengerMessageSenderInterface::class);

        self::assertInstanceOf(TelegramMessageSender::class, $service);
    }
}
