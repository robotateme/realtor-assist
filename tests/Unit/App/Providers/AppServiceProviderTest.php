<?php

declare(strict_types=1);

namespace Tests\Unit\App\Providers;

use Application\Command\Repositories\DB\ClientsWriteRepositoryInterface;
use Application\Port\Bus\EventBusPortInterface;
use Application\Port\Bus\QueueBusPortInterface;
use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\LLM\OllamaLegalAssistantClientInterface;
use Application\Port\Outbox\EventSerializerInterface;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Application\Port\Prompt\PromptRendererInterface;
use Application\Port\Persistence\RelationsPortInterface;
use Application\Query\Clients\Repositories\DB\ClientsReadRepositoryInterface;
use Infrastructure\Bus\LaravelEventBusAdapter;
use Infrastructure\Bus\LaravelQueueBusAdapter;
use Infrastructure\Bus\OutboxEventBusAdapter;
use Infrastructure\Bus\OutboxMessagePublisher;
use Infrastructure\Http\OllamaHttpClient;
use Infrastructure\LLM\OllamaLegalAssistantClient;
use Infrastructure\Outbox\ReflectionEventSerializer;
use Infrastructure\Persistence\Outbox\EloquentOutboxMessageRepository;
use Infrastructure\Persistence\Relations\EloquentRelationsAdapterInterface;
use Infrastructure\Prompt\BladePromptRenderer;
use Infrastructure\Repositories\ClientsReadRepository;
use Infrastructure\Repositories\ClientsWriteRepository;
use Tests\TestCase;

final class AppServiceProviderTest extends TestCase
{
    public function test_it_binds_event_bus_port_to_laravel_adapter(): void
    {
        $service = $this->app->make(EventBusPortInterface::class);
        $sameService = $this->app->make(EventBusPortInterface::class);

        self::assertInstanceOf(OutboxEventBusAdapter::class, $service);
        self::assertSame($service, $sameService);
    }

    public function test_it_binds_outbox_dependencies(): void
    {
        $serializer = $this->app->make(EventSerializerInterface::class);
        $repository = $this->app->make(OutboxMessageRepositoryInterface::class);
        $publisher = $this->app->make(OutboxMessagePublisher::class);
        $laravelEventBus = $this->app->make(LaravelEventBusAdapter::class);
        $ollamaHttpClient = $this->app->make(OllamaHttpClientInterface::class);
        $promptRenderer = $this->app->make(PromptRendererInterface::class);
        $ollamaLegalAssistant = $this->app->make(OllamaLegalAssistantClientInterface::class);

        self::assertInstanceOf(ReflectionEventSerializer::class, $serializer);
        self::assertInstanceOf(EloquentOutboxMessageRepository::class, $repository);
        self::assertInstanceOf(OutboxMessagePublisher::class, $publisher);
        self::assertInstanceOf(LaravelEventBusAdapter::class, $laravelEventBus);
        self::assertInstanceOf(OllamaHttpClient::class, $ollamaHttpClient);
        self::assertInstanceOf(BladePromptRenderer::class, $promptRenderer);
        self::assertInstanceOf(OllamaLegalAssistantClient::class, $ollamaLegalAssistant);
    }

    public function test_it_binds_queue_bus_port_to_laravel_adapter(): void
    {
        $service = $this->app->make(QueueBusPortInterface::class);

        self::assertInstanceOf(LaravelQueueBusAdapter::class, $service);
    }

    public function test_it_binds_relations_port_to_eloquent_adapter(): void
    {
        $service = $this->app->make(RelationsPortInterface::class);

        self::assertInstanceOf(EloquentRelationsAdapterInterface::class, $service);
    }

    public function test_it_binds_clients_read_repository_interface(): void
    {
        $service = $this->app->make(ClientsReadRepositoryInterface::class);

        self::assertInstanceOf(ClientsReadRepository::class, $service);
    }

    public function test_it_binds_clients_write_repository_interface(): void
    {
        $service = $this->app->make(ClientsWriteRepositoryInterface::class);

        self::assertInstanceOf(ClientsWriteRepository::class, $service);
    }
}
