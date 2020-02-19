.PHONY: install update clean help test dusk dev stop-dev build migrate bash
.DEFAULT_GOAL   = help

include .env

COM_COLOR       = \033[0;34m
PRIMARY_COLOR   = \033[0;36m
SUCCESS_COLOR   = \033[0;32m
DANGER_COLOR    = \033[0;31m
WARNING_COLOR   = \033[0;33m
NO_COLOR        = \033[m

# For test
filter      ?= tests
dir         ?=

php := docker-compose run --rm php_dev php
php_dusk := docker-compose run --rm php_dusk php
bash_php := docker-compose run --rm php_dev bash
mariadb := docker-compose exec mariadb mysql -uroot -proot -e
npm := npm
composer := docker-compose run --rm php_dev composer

node_modules: package.json
	@$(npm) install

vendor: composer.json
	@$(composer) install

install: vendor node_modules ## Install the composer dependencies and npm dependencies

update: ## Update the composer dependencies and npm dependencies
	@$(composer) update
	@$(npm) run update
	@$(npm) install
	@$(php) artisan app:build-translations

clean: ## Remove composer dependencies (vendor folder) and npm dependencies (node_modules folder)
	@echo "$(DANGER_COLOR) ### Delete the composer and npm files/directories$(NO_COLOR)"
	rm -rf vendor node_modules package-lock.json composer.lock

help:
	@awk 'BEGIN {FS = ":.*##"; } /^[a-zA-Z_-]+:.*?##/ { printf "$(PRIMARY_COLOR)%-10s$(NO_COLOR) %s\n", $$1, $$2 }' $(MAKEFILE_LIST) | sort

test: dev ## Run unit tests (parameters : dir=tests/Feature/LoginTest.php || filter=get)
	@$(mariadb) "drop database if exists shop_test; create database shop_test;"
	@$(php) vendor/bin/phpunit $(dir) --filter $(filter) --stop-on-failure

dusk: install ## Run dusk tests (parameters : build=1 to build assets before run dusk tests)
ifdef build
	make build
endif
	@docker-compose up -d nginx_dusk chrome
	@$(mariadb) "drop database if exists shop_test; create database shop_test;"
	@$(php) artisan dusk
	@docker-compose stop nginx_dusk php_dusk chrome
	@echo "$(PRIMARY_COLOR)End of browser tests$(NO_COLOR)"

dev: install ## Run development servers
	@docker-compose up -d nginx_dev webpack_dev_server #laravel_echo_server
	@echo "Dev server launched on $(PRIMARY_COLOR)http://localhost:$(APP_PORT)$(NO_COLOR)"
	@echo "Mail server launched on $(PRIMARY_COLOR)http://localhost:1080$(NO_COLOR)"
	@echo "Webpack dev server launched on $(PRIMARY_COLOR)http://localhost:$(WEBPACK_DEV_SERVER_PORT)$(NO_COLOR)"
	@echo "Laravel echo server launched on $(PRIMARY_COLOR)http://localhost:$(LARAVEL_ECHO_SERVER_PORT)$(NO_COLOR)"

stop-dev: ## Stop development servers
	@docker-compose down
	@echo "Dev server stopped: $(PRIMARY_COLOR)http://localhost:$(APP_PORT)$(NO_COLOR)"
	@echo "Mail server stopped: $(PRIMARY_COLOR)http://localhost:1080$(NO_COLOR)"
	@echo "Webpack dev server stopped: $(PRIMARY_COLOR)http://localhost:$(WEBPACK_DEV_SERVER_PORT)$(NO_COLOR)"
	@echo "Laravel echo server stopped: $(PRIMARY_COLOR)http://localhost:$(LARAVEL_ECHO_SERVER_PORT)$(NO_COLOR)"

build: install ## Build assets projects for production
	@$(npm) run build
	@$(php) artisan app:build-translations

migrate: install ## Refresh database by running new migrations
	@$(php) artisan migrate:fresh --seed

bash: install ## Run bash in PHP container
	@$(bash_php)

