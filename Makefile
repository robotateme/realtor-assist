SHELL := /bin/bash

.DEFAULT_GOAL := help

DOCKER_COMPOSE := docker compose
APP_SERVICE := laravel.test
APP_EXEC := $(DOCKER_COMPOSE) exec -T $(APP_SERVICE)

.PHONY: help build up down restart ps logs shell root-shell composer-install npm-install key migrate fresh seed init \
	test stan psalm analyse static pint horizon telescope cache-clear optimize-clear

help:
	@printf "Available targets:\n"
	@printf "  make build           Build application images\n"
	@printf "  make up              Start docker environment\n"
	@printf "  make down            Stop docker environment\n"
	@printf "  make restart         Restart docker environment\n"
	@printf "  make ps              Show container status\n"
	@printf "  make logs            Tail container logs\n"
	@printf "  make shell           Open shell in app container\n"
	@printf "  make root-shell      Open root shell in app container\n"
	@printf "  make init            Full project bootstrap inside Docker\n"
	@printf "  make composer-install Install PHP dependencies\n"
	@printf "  make npm-install     Install Node dependencies\n"
	@printf "  make key             Generate APP_KEY\n"
	@printf "  make migrate         Run migrations\n"
	@printf "  make fresh           Rebuild database from scratch\n"
	@printf "  make seed            Run seeders\n"
	@printf "  make test            Run test suite\n"
	@printf "  make stan            Run PHPStan\n"
	@printf "  make psalm           Run Psalm\n"
	@printf "  make static          Run full static analysis\n"
	@printf "  make analyse         Run PHPStan and Psalm\n"
	@printf "  make pint            Run Laravel Pint\n"
	@printf "  make horizon         Open Horizon UI URL hint\n"
	@printf "  make telescope       Open Telescope UI URL hint\n"

build:
	$(DOCKER_COMPOSE) build

up:
	$(DOCKER_COMPOSE) up -d --build

down:
	$(DOCKER_COMPOSE) down

restart: down up

ps:
	$(DOCKER_COMPOSE) ps

logs:
	$(DOCKER_COMPOSE) logs -f --tail=200

shell:
	$(DOCKER_COMPOSE) exec $(APP_SERVICE) bash

root-shell:
	$(DOCKER_COMPOSE) exec -u root $(APP_SERVICE) bash

composer-install:
	$(APP_EXEC) composer install

npm-install:
	$(APP_EXEC) npm install --ignore-scripts

key:
	$(APP_EXEC) php artisan key:generate

migrate:
	$(APP_EXEC) php artisan migrate --force

fresh:
	$(APP_EXEC) php artisan migrate:fresh --force

seed:
	$(APP_EXEC) php artisan db:seed --force

cache-clear:
	$(APP_EXEC) php artisan optimize:clear

optimize-clear: cache-clear

init: up composer-install npm-install key migrate
	$(APP_EXEC) npm run build

test:
	$(APP_EXEC) php artisan test

stan:
	$(APP_EXEC) ./vendor/bin/phpstan analyse --memory-limit=1G

psalm:
	$(APP_EXEC) ./vendor/bin/psalm --no-progress

static:
	$(MAKE) stan
	$(MAKE) psalm

analyse:
	$(MAKE) static

pint:
	$(APP_EXEC) ./vendor/bin/pint

horizon:
	@printf "Horizon: http://localhost/horizon\n"

telescope:
	@printf "Telescope: http://localhost/telescope\n"
