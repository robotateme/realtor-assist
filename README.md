# Realtor Assist

Laravel 13 project for realtor workflow automation with Telegram webhook integration, Horizon, Telescope, and an Ollama-backed legal assistant flow.

## Quality Status

Verified in Docker on April 9, 2026:

- Tests: `72 passed (228 assertions)` via `docker compose run --rm --no-deps laravel.test php artisan test`
- PHPStan: `No errors` via `docker compose run --rm --no-deps laravel.test ./vendor/bin/phpstan analyse --memory-limit=1G`

## Stack

- PHP 8.5
- Laravel 13
- PostgreSQL
- SQLite for tests
- Redis
- Docker Compose

## Configuration

Key application settings live in `config/realtor-assist.php`.

Ollama / LLM settings:

- `OLLAMA_BASE_URL`
- `OLLAMA_API_KEY`
- `OLLAMA_TIMEOUT`
- `OLLAMA_CONNECT_TIMEOUT`
- `OLLAMA_DEFAULT_MODEL`
- `OLLAMA_MODEL_QWEN`
- `OLLAMA_MODEL_GPT`

Redis-backed rate limit settings for Ollama chat:

- `REALTOR_ASSIST_REDIS_CONNECTION`
- `REALTOR_ASSIST_RATE_LIMIT_PREFIX`
- `OLLAMA_RATE_LIMIT_ENABLED`
- `OLLAMA_RATE_LIMIT_BUCKET`
- `OLLAMA_RATE_LIMIT_MAX_ATTEMPTS`
- `OLLAMA_RATE_LIMIT_WINDOW_SECONDS`
- `OLLAMA_RATE_LIMIT_COST`

Application cache port settings:

- `REALTOR_ASSIST_CACHE_STORE`
- `REALTOR_ASSIST_CACHE_PREFIX`
- `REALTOR_ASSIST_CACHE_TTL`

## Cache Strategy

Use the application cache through `Application\Port\Cache\CacheStoreInterface` rather than Laravel facades or direct Redis calls from application code.

Recommended cache key layout:

- `{context}:{shape}:v1`
- `{context}:{shape}:v1:{id}`
- `{context}:{shape}:v1:{version}:{query-hash}`

Examples:

- `clients:by-id:v1:123`
- `clients:list:v1:7f3d2a1c`
- `clients:list:v1:4:7f3d2a1c`
- `telegram-bot:token:v1:test-token`

Recommended invalidation rules:

- Entity cache: invalidate directly with `forget()`
- List/search/query cache: use namespace versioning instead of tracking every possible key
- Rarely changing reference data: long TTL is usually enough

Practical approach:

- `by-id` cache:
  - key: `clients:by-id:v1:{id}`
  - invalidation: `forget()` on create/update/delete of that entity
- `list/search` cache:
  - key: `clients:list:v1:{listVersion}:{queryHash}`
  - invalidation: bump `listVersion` when source data changes
- derived hot data:
  - combine short TTL with version bump when strong consistency matters

Use `v1` in keys so cache formats can be rotated safely without manual Redis cleanup.

## Run

```bash
docker compose up -d
docker compose run --rm laravel.test composer install
docker compose run --rm laravel.test php artisan key:generate
docker compose run --rm laravel.test php artisan migrate:fresh --force
```

App container service: `laravel.test`

## Useful Commands

Run tests:

```bash
docker compose run --rm --no-deps laravel.test php artisan test
```

The test environment is configured via `.env.testing` and `phpunit.xml` to use `database/testing.sqlite`.

Run PHPStan:

```bash
docker compose run --rm --no-deps laravel.test ./vendor/bin/phpstan analyse --memory-limit=1G
```

Run Psalm:

```bash
docker compose run --rm --no-deps laravel.test ./vendor/bin/psalm --no-progress
```

Rebuild database:

```bash
docker compose run --rm laravel.test php artisan migrate:fresh --force
```

## Notes

- Main application code lives in `app/` and `src/`.
- Domain layer lives in `src/Domain`.
- Application ports live in `src/Application/Port`; infrastructure adapters live in `src/Infrastructure`.
- Event publishing uses the Outbox pattern.
- `EventBusPortInterface` writes to `outbox_messages`, and `OutboxMessagePublisher` publishes pending events through Laravel events.
- Ollama transport lives in `Infrastructure/Http/OllamaHttpClient` and is wired through PSR HTTP interfaces.
- `OllamaHttpClientInterface` is wrapped by `Infrastructure/Http/RateLimitedOllamaHttpClient`, which applies a Redis sliding-window rate limit for `/api/chat`.
- Redis coordination for rate limiting goes through `Infrastructure/Redis/ScriptResolver`; Lua scripts are stored in `src/Infrastructure/Redis/Scripts/Lua`.
- Prompt rendering is isolated in `Infrastructure/Prompt/BladePromptRenderer`.
- Prompt composition is handled by `Infrastructure/Prompt/CompositePromptResolver`, so the legal-assistant flow can layer base and scenario-specific prompts.
- Base legal-assistant prompts live in `resources/views/components/ai_prompts/ollama/base`.
- `Infrastructure/LLM/OllamaLegalAssistantClient` resolves prompt layers, maps model aliases from config, sends `/api/chat` requests to Ollama, and decodes the JSON response.
- Cache access is abstracted behind `Application\Port\Cache\CacheStoreInterface` with the Laravel adapter in `Infrastructure/Cache/LaravelCacheStore`.
- Telegram webhook route is provider-driven through Telegraph config and exposed as the named route `telegraph.webhook`.
