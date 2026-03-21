# Makefile for Laravel Docker commands

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

app:
	docker-compose exec app bash

bash:
	docker-compose exec app bash

migrate:
	docker-compose exec app php artisan migrate

seed:
	docker-compose exec app php artisan db:seed

test:
	docker-compose exec app php artisan test

key:
	docker-compose exec app php artisan key:generate

tinker:
	docker-compose exec app php artisan tinker
