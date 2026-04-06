# Realtor Assist

Laravel 13 project for realtor workflow automation with Telegram webhook integration, Horizon, and Telescope.

## Stack

- PHP 8.5
- Laravel 13
- PostgreSQL
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
- Telegram webhook route is provider-driven through Telegraph config.
