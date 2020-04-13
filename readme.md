# Address Intelligence

## Requirements
- Docker v19.03+
- Docker Compose v1.25+
- port 8082 must be available

## Installation
1) `docker-compose pull`
2) `docker-compose up -d`
3) `docker-compose run composer composer install`
4) `cp src/.env.example src/.env`
5) `docker-compose run composer composer dump-autoload`
6) `docker-compose run php php artisan migrate --seed`

## Run tests
1) `docker-compose run php php vendor/bin/phpunit`
