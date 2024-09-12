PROJECT_NAME=$(shell basename "$(PWD)")
PROJECT_DIR=$(shell pwd)
DOCKER_COMPOSE=$(shell which docker) compose
DOCKER=$(shell which docker)

PHP_CONTAINER_NAME=php
PHP_CONTAINER_EXEC=${DOCKER_COMPOSE} exec -it ${PHP_CONTAINER_NAME}
PHP_CONTAINER_ROOT_EXEC=${DOCKER_COMPOSE} exec -it -uroot ${PHP_CONTAINER_NAME}

NODE_CONTAINER_NAME=node
NODE_CONTAINER_EXEC=${DOCKER_COMPOSE} exec -it ${NODE_CONTAINER_NAME}
NODE_CONTAINER_ROOT_EXEC=${DOCKER_COMPOSE} exec -it -uroot ${NODE_CONTAINER_NAME}

COMPOSER_EXEC=${PHP_CONTAINER_EXEC} composer
CONSOLE_EXEC=${PHP_CONTAINER_EXEC} bin/console
NPM_EXEC=${NODE_CONTAINER_EXEC} npm

# Colors
G=\033[32m
Y=\033[33m
NC=\033[0m


help: ## List of all commands
	@grep -E '(^[a-zA-Z_0-9-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) \
	| awk 'BEGIN {FS = ":.*?## "}; {printf "${G}%-24s${NC} %s\n", $$1, $$2}' \
	| sed -e 's/\[32m## /[33m/' && printf "\n"; \
	printf "Project: ${Y}${PROJECT_NAME}${NC}\n"; \
	printf "Project directory: ${Y}${PROJECT_DIR}${NC}\n"; \
	printf "PHP: ${Y}${PHP_CONTAINER_EXEC} php${NC}\n"; \
	printf "PHP Composer: ${Y}${COMPOSER_EXEC}${NC}\n\n";

.DEFAULT_GOAL := help
.PHONY: help

cache-clear:
	${PHP_CONTAINER_EXEC} rm -rf var/cache/*

cc: cache-clear

phpcs:
	${COMPOSER_EXEC} phpcs:fix

linter:
	${COMPOSER_EXEC} linter

.PHONY: cache-clear cc phpcs linter



lint-container:
	${CONSOLE_EXEC} lint:container --verbose

lc: lint-container

.PHONY: lint-container lc



up:
	${DOCKER_COMPOSE} up -d

down:
	${DOCKER_COMPOSE} down

restart: down up

php:
	${PHP_CONTAINER_EXEC} bash

node:
	${NODE_CONTAINER_EXEC} bash

root:
	${PHP_CONTAINER_ROOT_EXEC} bash

build:
	${DOCKER_COMPOSE} --env-file .env build

push:
	${DOCKER_COMPOSE} --env-file .env push

.PHONY: up down restart build bash


npm-install:
	${NPM_EXEC} install

ni: npm-install


composer-install:
	${COMPOSER_EXEC} install --no-interaction --prefer-dist --optimize-autoloader

composer-refresh-lock:
	${COMPOSER_EXEC} update nothing

composer-validate:
	${COMPOSER_EXEC} validate --no-check-publish

composer-clear-cache:
	${COMPOSER_EXEC} clear-cache

ci: composer-install

crl: composer-refresh-lock

cv: composer-validate

.PHONY: composer-install composer-refresh-lock composer-validate composer-clear-cache ci crl cv
