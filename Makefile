COMPOSE_DEV=docker-compose -f docker/docker-compose.yml
COMPOSE_DEBUGGING=IDE_IP=$(IDE_IP) $(COMPOSE_DEV)
CURRENT_USER:=$(shell whoami)

build:
	echo "Cleaning cache..."
	sudo rm -Rf var/cache/*
	$(COMPOSE_DEV) build mysql_marketplace
	$(COMPOSE_DEV) build apache_marketplace
.PHONY: build

vendor-install:
	$(COMPOSE_DEV) run --rm apache_marketplace composer install --dev
	$(COMPOSE_DEV) run --rm apache_marketplace yarn install
.PHONY: vendor-install

tests:
	$(COMPOSE_DEV) run --rm apache_marketplace php bin/phpunit -v
.PHONY: tests

migrate:
	$(COMPOSE_DEV) run --rm apache_marketplace bin/console doctrine:migrations:migrate -n
.PHONY: migrate

kill-all:
	echo "STOPPING CURRENT COMPOSE"
	$(COMPOSE_DEV) stop
	$(COMPOSE_DEV) down || true
	echo "EVERYTHING STOPPED"
.PHONY: kill-all

dev-deploy: vendor-install
	echo "STARTING DEPLOY"
	@make kill-all
	echo "DEPLOYING..."
	$(COMPOSE_DEV) up -d
	echo "DEPLOYMENT FINISHED"
.PHONY: dev-deploy
