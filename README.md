# Realtor Assist

Laravel 13 project for realtor workflow automation with Telegram webhook integration, Horizon, Telescope, and an Ollama-backed legal assistant flow.

## Stack

- PHP 8.5
- Laravel 13
- PostgreSQL
- SQLite for tests
- Redis
- Docker Compose

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
- Event publishing uses the Outbox pattern.
- `EventBusPortInterface` writes to `outbox_messages`, and `OutboxMessagePublisher` publishes pending events through Laravel events.
- Ollama transport lives in `Infrastructure/Http/OllamaHttpClient` and is wired through PSR HTTP interfaces.
- Prompt rendering is isolated in `Infrastructure/Prompt/BladePromptRenderer`.
- Base legal-assistant prompts live in `resources/views/components/ai_prompts/ollama/base`.
- `Infrastructure/LLM/OllamaLegalAssistantClient` renders the base prompts, sends `/api/chat` requests to Ollama, and decodes the JSON response.
- Telegram webhook route is provider-driven through Telegraph config.
