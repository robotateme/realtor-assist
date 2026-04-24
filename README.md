# Realtor Assist

Техническая документация по локальному деплою и разработке.

Описание продукта вынесено в отдельный файл: [docs/PROJECT_DESCRIPTION.md](/home/oem/Work/realtor-assist/docs/PROJECT_DESCRIPTION.md).

## Назначение репозитория

- backend на Laravel 13 / PHP 8.5
- Docker Compose окружение для локальной разработки
- PostgreSQL как основная база
- Redis для очередей и rate limit
- Horizon для очередей
- Telescope для отладки
- Ollama-интеграция для AI-сценариев

## Локальный деплой через Docker

### Требования

- Docker
- Docker Compose
- GNU Make

### Первый запуск

```bash
cp .env.example .env
make init
```

Команда `make init` делает:

- сборку и запуск контейнеров
- `composer install`
- `npm install --ignore-scripts`
- генерацию `APP_KEY`
- запуск миграций
- production build фронтенда через `npm run build`

### Доступные сервисы

- приложение: `http://localhost`
- Horizon: `http://localhost/horizon`
- Telescope: `http://localhost/telescope`
- PostgreSQL: `localhost:5432`
- Redis: `localhost:6379`
- VarDumper server: `localhost:9912`

### Основные команды

```bash
make up
make down
make restart
make ps
make logs
make shell
make root-shell
make test
make stan
make psalm
make static
make analyse
make pint
make migrate
make fresh
```

### Описание make-команд

- `make init` — полный первый запуск: build, start, `composer install`, `npm install`, `key:generate`, миграции и `npm run build`
- `make build` — пересобрать docker-образы
- `make up` — поднять окружение в фоне
- `make down` — остановить окружение
- `make restart` — перезапустить окружение
- `make ps` — показать статус контейнеров
- `make logs` — смотреть логи контейнеров
- `make shell` — открыть shell в контейнере `laravel.test`
- `make root-shell` — открыть root shell в контейнере `laravel.test`
- `make composer-install` — установить PHP-зависимости внутри контейнера
- `make npm-install` — установить Node-зависимости внутри контейнера
- `make key` — сгенерировать `APP_KEY`
- `make migrate` — применить миграции
- `make fresh` — пересоздать базу через `migrate:fresh --force`
- `make seed` — выполнить сиды
- `make cache-clear` — очистить кэш Laravel через `optimize:clear`
- `make optimize-clear` — алиас для `make cache-clear`
- `make test` — запустить тесты внутри контейнера
- `make stan` — запустить `phpstan` внутри контейнера
- `make psalm` — запустить `psalm` внутри контейнера
- `make static` — полный прогон статического анализа: `phpstan` + `psalm`
- `make analyse` — алиас для `make static`
- `make pint` — запустить Laravel Pint
- `make horizon` — вывести URL Horizon
- `make telescope` — вывести URL Telescope

Команды, использующие `docker compose exec`, требуют уже поднятый сервис `laravel.test`. Если окружение ещё не запущено, сначала выполни `make up` или `make init`.

## Переменные окружения для Docker

`.env.example` настроен именно под Docker Compose.

Ключевые значения:

- `DB_HOST=pgsql`
- `DB_USERNAME=sail`
- `DB_PASSWORD=password`
- `REDIS_HOST=redis`
- `QUEUE_CONNECTION=redis`

AI и Telegram:

- `OLLAMA_BASE_URL`
- `OLLAMA_API_KEY`
- `OLLAMA_DEFAULT_MODEL`
- `TELEGRAM_WEBHOOK_DOMAIN`
- `TELEGRAM_WEBHOOK_URL`
- `TELEGRAM_BOT_API_KEY`

## Локальная разработка без Docker

Если запускать проект вне контейнеров, нужно вручную адаптировать `.env` минимум по этим полям:

- `DB_HOST`
- `DB_USERNAME`
- `DB_PASSWORD`
- `REDIS_HOST`

Минимальный сценарий:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm install
npm run build
php artisan serve
php artisan horizon
```

## Проверки и тесты

Актуальный статус на 24 апреля 2026:

- `php8.5 ./vendor/bin/phpstan analyse --memory-limit=1G` — `No errors`
- `php8.5 ./vendor/bin/psalm --no-progress` — `No errors`
- `docker compose run --rm --no-deps laravel.test php artisan test` — `78 passed`, `240 assertions`
- `php8.5 artisan test` на хосте — `74 passed`, `4 skipped`, `215 assertions`

Причина пропущенных feature-тестов: в текущем локальном PHP runtime недоступен `pdo_sqlite`, поэтому были пропущены:

- `Tests\Feature\OutboxFlowTest`
- `Tests\Feature\TelegramWebhookControllerTest`

Тесты:

```bash
docker compose run --rm --no-deps laravel.test php artisan test
```

Локальный запуск на хосте:

```bash
php8.5 artisan test
```

Статический анализ:

```bash
make static
```

По отдельности:

```bash
php8.5 ./vendor/bin/phpstan analyse --memory-limit=1G
php8.5 ./vendor/bin/psalm --no-progress
```

Форматирование:

```bash
make pint
```

## Структура проекта

```text
app/                 Laravel controllers, providers, models
config/              Конфигурация приложения
database/            Миграции, фабрики, сидеры
docker/              Docker runtime и build-файлы
resources/           Blade и prompt templates
routes/              Web/API routes
src/Application      Команды, DTO, порты
src/Domain           Доменная модель
src/Infrastructure   Интеграции и адаптеры
tests/               Feature и unit tests
```

## Замечания по среде

- `docker compose run ...` может ломаться, если локально используется старый собранный образ Sail. В таком случае пересобери окружение: `docker compose build --no-cache && docker compose up -d`.
- Основной рабочий путь для проекта сейчас рассчитан на `make` + `docker compose exec`.
