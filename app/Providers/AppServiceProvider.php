<?php

declare(strict_types=1);

namespace App\Providers;

use Application\Command\Repositories\DB\ClientsWriteRepositoryInterface;
use Application\Port\Bus\EventBusPortInterface;
use Application\Port\Bus\QueueBusPortInterface;
use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\LLM\OllamaLegalAssistantClientInterface;
use Application\Port\Outbox\EventSerializerInterface;
use Application\Port\Persistence\MigrationsPortInterface;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Application\Port\Persistence\RelationsPortInterface;
use Application\Port\Prompt\PromptRendererInterface;
use Application\Query\Clients\Repositories\DB\ClientsReadRepositoryInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Bus\LaravelEventBusAdapter;
use Infrastructure\Bus\LaravelQueueBusAdapter;
use Infrastructure\Bus\OutboxEventBusAdapter;
use Infrastructure\Bus\OutboxMessagePublisher;
use Infrastructure\Http\OllamaHttpClient;
use Infrastructure\LLM\OllamaLegalAssistantClient;
use Infrastructure\Mappings\Clients\GetClients;
use Infrastructure\Outbox\ReflectionEventSerializer;
use Infrastructure\Persistence\Migrations\LaravelMigrationsAdapter;
use Infrastructure\Persistence\Outbox\EloquentOutboxMessageRepository;
use Infrastructure\Persistence\Relations\EloquentRelationsAdapterInterface;
use Infrastructure\Persistence\Repositories\EloquentFilterContext;
use Infrastructure\Prompt\BladePromptRenderer;
use Infrastructure\Repositories\ClientsReadRepository;
use Infrastructure\Repositories\ClientsWriteRepository;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->singleton(PromptRendererInterface::class, BladePromptRenderer::class);
        $this->app->singleton(OllamaHttpClientInterface::class, function (): OllamaHttpClient {
            /** @var array{base_url:string,timeout:float,connect_timeout:float} $config */
            $config = config('realtor-assist.ollama');

            return new OllamaHttpClient(
                client: new GuzzleClient([
                    'timeout' => $config['timeout'],
                    'connect_timeout' => $config['connect_timeout'],
                ]),
                requestFactory: new HttpFactory(),
                streamFactory: new HttpFactory(),
                baseUri: $config['base_url'],
            );
        });
        $this->app->singleton(OllamaLegalAssistantClientInterface::class, OllamaLegalAssistantClient::class);
        $this->app->singleton(EventSerializerInterface::class, ReflectionEventSerializer::class);
        $this->app->singleton(OutboxMessageRepositoryInterface::class, EloquentOutboxMessageRepository::class);
        $this->app->singleton(LaravelEventBusAdapter::class, LaravelEventBusAdapter::class);
        $this->app->singleton(OutboxMessagePublisher::class, OutboxMessagePublisher::class);
        $this->app->singleton(EventBusPortInterface::class, OutboxEventBusAdapter::class);
        $this->app->singleton(QueueBusPortInterface::class, LaravelQueueBusAdapter::class);
        $this->app->singleton(MigrationsPortInterface::class, LaravelMigrationsAdapter::class);
        $this->app->singleton(RelationsPortInterface::class, EloquentRelationsAdapterInterface::class);
        $this->app->singleton(ClientsReadRepositoryInterface::class, function () {
            return new ClientsReadRepository(
                new EloquentFilterContext(),
                new GetClients(),
            );
        });
        $this->app->singleton(ClientsWriteRepositoryInterface::class, ClientsWriteRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
