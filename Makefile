.PHONY: up build start restart stop remove clear-cache execute-command-rclients test composer

build:
	docker compose build

start:
	docker compose up -d

restart:
	docker compose restart

stop:
	docker compose stop

remove:
	docker compose down

clear-cache:
	docker exec inbenta-test bin/console cache:clear

test:
	docker exec inbenta-test bin/phpunit

composer:
	docker exec -w /var/www/html inbenta-test composer install