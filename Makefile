# Functions
define read_param
`cat app/config/parameters.yml | grep $1 | awk '{print $$2}' | sed -n '1p'`
endef

define get_env
`readlink -f web/.htaccess | sed -r 's/.*htaccess.(.*).dist/\1/'`
endef

define add_uid =
	if ! grep -xq "UID=.*" $1; then \
	  echo "UID=$$(id -u)" | tee --append $1; \
	  echo "GID=$$(id -g)" | tee --append $1; \
	fi
endef

define add_env =
	if ! grep -xq "APP_ENV=.*" $1; then \
	  echo "APP_ENV=docker" | tee --append $1; \
	fi
endef


# Project variables
dev := ffvolley.dev6.sutunam.com@ffvolley.dev6.sutunam.com
dev_database := ffvolley_dev6_sutunam_com

prod := example.com@example.com
prod_database := example_com

php_version := $(shell php -v | head -n 1 | cut -d " " -f 2 | cut -c 1,2,3)
PHP := phpx.xx

DOCKER_ENV := ./docker/.env

DOCKER_COMPOSE_EXISTS := $(shell command -v docker-compose 2> /dev/null)
ifdef DOCKER_COMPOSE_EXISTS
DOCKER_COMPOSE := docker-compose
EXEC := $(DOCKER_COMPOSE) exec
PHP=$(EXEC) php
NODE=$(EXEC) node
endif

CONSOLE=$(EXEC) php bin/console

# SSH
ssh_dev:
	ssh $(dev)

import_db_dev:
	ssh $(dev) mysqldump $(dev_database) | mysql -u $(call read_param,"database_user") $(call read_param,"database_name")

setup: create_env up vendor node_modules

create_env:
	touch ${DOCKER_ENV}
	$(call add_uid, ${DOCKER_ENV})
	$(call add_env, .env.local)

build:
	${DOCKER_COMPOSE} --env-file=${DOCKER_ENV} build

up:
	$(DOCKER_COMPOSE) --env-file=${DOCKER_ENV} up -d --remove-orphans

vendor: vendor/autoload.php

vendor/autoload.php: composer.lock
	$(PHP) composer install -n

composer.lock: composer.json
	@echo composer.lock is not up to date.

node_modules: yarn.lock
	$(NODE) yarn install

yarn.lock: package.json
	@echo yarn.lock is not up to date.

clear_cache:
	$(PHP) bin/console cache:clear --env=$(call get_env)

db_update:
	$(CONSOLE) doctrine:schema:update --force

test:
	$(PHP) composer run phpcs
	$(PHP) composer run ecs
	$(NODE) yarn run eslint
	$(NODE) yarn run prettier

format:
	$(NODE) yarn run prettier:fix
	$(NODE) yarn run eslint:fix
	$(PHP) composer run ecs-fix
