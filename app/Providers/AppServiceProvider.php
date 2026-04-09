<?php

declare(strict_types=1);

namespace App\Providers;

use Application\Command\Repositories\DB\ClientsWriteRepositoryInterface;
use Application\Port\Bus\EventBusPortInterface;
use Application\Port\Bus\QueueBusPortInterface;
use Application\Port\Cache\CacheStoreInterface;
use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\LLM\OllamaLegalAssistantClientInterface;
use Application\Port\Outbox\EventSerializerInterface;
use Application\Port\Persistence\MigrationsPortInterface;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Application\Port\Persistence\RelationsPortInterface;
use Application\Port\Prompt\PromptRendererInterface;
use Application\Port\Prompt\PromptResolverInterface;
use Application\Port\RateLimit\RateLimiterInterface;
use Application\Query\Clients\Repositories\DB\ClientsReadRepositoryInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Bus\LaravelEventBusAdapter;
use Infrastructure\Bus\LaravelQueueBusAdapter;
use Infrastructure\Bus\OutboxEventBusAdapter;
use Infrastructure\Bus\OutboxMessagePublisher;
use Infrastructure\Cache\LaravelCacheStore;
use Infrastructure\Http\OllamaHttpClient;
use Infrastructure\Http\RateLimitedOllamaHttpClient;
use Infrastructure\LLM\OllamaLegalAssistantClient;
use Infrastructure\Mappings\Clients\GetClients;
use Infrastructure\Outbox\ReflectionEventSerializer;
use Infrastructure\Persistence\Migrations\LaravelMigrationsAdapter;
use Infrastructure\Persistence\Outbox\EloquentOutboxMessageRepository;
use Infrastructure\Persistence\Relations\EloquentRelationsAdapterInterface;
use Infrastructure\Persistence\Repositories\EloquentFilterContext;
use Infrastructure\Prompt\BladePromptRenderer;
use Infrastructure\Prompt\CompositePromptResolver;
use Infrastructure\RateLimit\RedisRateLimiter;
use Infrastructure\Redis\ScriptExecutorInterface;
use Infrastructure\Redis\ScriptResolver;
use Infrastructure\Redis\Scripts\RateLimitHitScript;
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
        $this->app->singleton(CacheStoreInterface::class, function (): LaravelCacheStore {
            /** @var array{store:string,prefix:string,default_ttl:int|string|null} $config */
            $config = config('realtor-assist.cache');

            return new LaravelCacheStore(
                repository: cache()->store($config['store']),
                prefix: $config['prefix'],
                defaultTtl: $config['default_ttl'] !== null ? (int) $config['default_ttl'] : null,
            );
        });
        $this->app->singleton(RateLimitHitScript::class, RateLimitHitScript::class);
        $this->app->singleton(ScriptResolver::class, function (): ScriptResolver {
            /** @var array{connection:string} $config */
            $config = config('realtor-assist.redis');

            return new ScriptResolver(
                redis: $this->app->make(RedisFactory::class),
                connection: $config['connection'],
            );
        });
        $this->app->singleton(ScriptExecutorInterface::class, ScriptResolver::class);
        $this->app->singleton(RateLimiterInterface::class, function (): RedisRateLimiter {
            /** @var array{prefix:string} $config */
            $config = config('realtor-assist.rate_limit');

            return new RedisRateLimiter(
                scriptExecutor: $this->app->make(ScriptExecutorInterface::class),
                script: $this->app->make(RateLimitHitScript::class),
                prefix: $config['prefix'],
            );
        });
        $this->app->singleton(PromptRendererInterface::class, BladePromptRenderer::class);
        $this->app->singleton(PromptResolverInterface::class, CompositePromptResolver::class);
        $this->app->singleton(OllamaHttpClient::class, function (): OllamaHttpClient {
            /** @var array{base_url:string,api_key:?string,timeout:float,connect_timeout:float,default_model:string,models:array<string,string>} $config */
            $config = config('realtor-assist.ollama');

            return new OllamaHttpClient(
                client: new GuzzleClient([
                    'timeout' => $config['timeout'],
                    'connect_timeout' => $config['connect_timeout'],
                ]),
                requestFactory: new HttpFactory(),
                streamFactory: new HttpFactory(),
                baseUri: $config['base_url'],
                apiKey: $config['api_key'],
            );
        });
        $this->app->singleton(OllamaHttpClientInterface::class, function (): RateLimitedOllamaHttpClient {
            /** @var array{enabled:bool,bucket:string,max_attempts:int,window_seconds:int,cost:int} $config */
            $config = config('realtor-assist.rate_limit.ollama');

            return new RateLimitedOllamaHttpClient(
                inner: $this->app->make(OllamaHttpClient::class),
                rateLimiter: $this->app->make(RateLimiterInterface::class),
                config: $config,
            );
        });
        $this->app->singleton(OllamaLegalAssistantClientInterface::class, function (): OllamaLegalAssistantClient {
            /** @var array{base_url:string,api_key:?string,timeout:float,connect_timeout:float,default_model:string,models:array<string,string>} $config */
            $config = config('realtor-assist.ollama');

            return new OllamaLegalAssistantClient(
                httpClient: $this->app->make(OllamaHttpClientInterface::class),
                promptResolver: $this->app->make(PromptResolverInterface::class),
                models: $config['models'],
                defaultModel: $config['default_model'],
            );
        });
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
