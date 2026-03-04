.PHONY: help build up down restart logs shell artisan composer migrate fresh seed test clean

# Default target
help:
	@echo "Dashboard SDM FIF - Docker Commands"
	@echo "===================================="
	@echo ""
	@echo "Setup:"
	@echo "  make build        Build Docker containers"
	@echo "  make up           Start all containers"
	@echo "  make down         Stop all containers"
	@echo "  make restart      Restart all containers"
	@echo ""
	@echo "Development:"
	@echo "  make logs         View logs (follow mode)"
	@echo "  make shell        Access app container shell"
	@echo "  make artisan CMD='...'  Run artisan command"
	@echo "  make composer CMD='...' Run composer command"
	@echo ""
	@echo "Database:"
	@echo "  make migrate      Run migrations"
	@echo "  make fresh        Fresh migration + seed"
	@echo "  make seed         Run database seeders"
	@echo "  make db-shell     Access MySQL shell"
	@echo ""
	@echo "Testing & Quality:"
	@echo "  make test         Run tests"
	@echo "  make pint         Run Laravel Pint"
	@echo ""
	@echo "Maintenance:"
	@echo "  make cache-clear  Clear all caches"
	@echo "  make optimize     Optimize application"
	@echo "  make clean        Clean and rebuild"

# Build containers
build:
	docker-compose build --no-cache

# Start containers
up:
	docker-compose up -d

# Stop containers
down:
	docker-compose down

# Restart containers
restart:
	docker-compose restart

# View logs
logs:
	docker-compose logs -f

# Access shell
shell:
	docker-compose exec app bash

# Run artisan command
artisan:
	docker-compose exec app php artisan $(CMD)

# Run composer command
composer:
	docker-compose exec app composer $(CMD)

# Database commands
migrate:
	docker-compose exec app php artisan migrate

fresh:
	docker-compose exec app php artisan migrate:fresh --seed

seed:
	docker-compose exec app php artisan db:seed

db-shell:
	docker-compose exec mysql mysql -u root -p

# Testing
test:
	docker-compose exec app php artisan test

pint:
	docker-compose exec app ./vendor/bin/pint

# Cache commands
cache-clear:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear

optimize:
	docker-compose exec app php artisan config:cache
	docker-compose exec app php artisan route:cache
	docker-compose exec app php artisan view:cache

# Clean and rebuild
clean:
	docker-compose down -v
	docker-compose build --no-cache
	docker-compose up -d
	docker-compose exec app php artisan migrate:fresh --seed

# Quick setup
setup:
	cp .env.docker .env
	docker-compose up -d --build
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan migrate
	docker-compose exec app php artisan storage:link
	docker-compose exec app php artisan config:cache
