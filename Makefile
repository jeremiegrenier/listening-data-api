.PHONY: *

.DEFAULT_GOAL := help
SHELL := /bin/bash

IMAGE_NAME?=listening-data-api
IMAGE_TAG?=latest



## Build Docker images
docker-build:
	DOCKER_BUILDKIT=1 docker build -t ${IMAGE_NAME}-php:${IMAGE_TAG} --ssh default . --target php
	DOCKER_BUILDKIT=1 docker build -t ${IMAGE_NAME}-nginx:${IMAGE_TAG} --ssh default . --target nginx

docker-run: docker-stop
	docker-compose up -d --scale cenv=0

## Stop Docker images
docker-stop:
	docker-compose down --remove-orphans

## Attach to php Docker image
docker-sh:
	docker-compose exec ${IMAGE_NAME}-php bash

## Attach to nginx Docker image
docker-sh-nginx:
	docker-compose exec ${IMAGE_NAME}-nginx sh

## Attach to data Docker image
docker-sh-data:
	docker-compose exec ${IMAGE_NAME}-data sh

## Watch logs from all Docker images
docker-logs:
	docker-compose logs -f

## Fix permission on docker image
docker-fix-permissions:
	docker-compose run --rm ${IMAGE_NAME}-php chown -R $$(id -u):$$(id -g) .

## Run ci tests on docker
docker-test-ci:
	docker-compose run -T listening-data-api-php make ./vendor/bin/codecept run unit
	docker-compose down --remove-orphans

## Run tests on docker
docker-test:
	docker-compose run --rm -T listening-data-api-php ./vendor/bin/codecept run --coverage --coverage-html

## Run cs on docker
docker-cs:
	docker-compose run --rm -T listening-data-api-php ./vendor/bin/php-cs-fixer fix
