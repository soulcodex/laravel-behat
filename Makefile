# Environment usefully vars
SHELL := /bin/bash
CURRENT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
USER := $(shell id -u)
GROUP := $(shell id -g)
DOCKER_RUN := docker run

# Colors
NC := '\033[0m'
RED := '\033[0;31m'


help: ## Show all available commands with explanations
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\/]+:.*?## / {sub("\\\\n",sprintf("\n%22c"," "), $$2);printf " \033[36m%-24s\033[0m  %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY=composer-install
composer-install: ## Install all composer dependencies
	${DOCKER_RUN} --rm --interactive --tty --volume ${CURRENT_DIR}:/app --user ${USER}:${GROUP} composer install

.PHONY=composer-req
composer-req: ## Add new composer dependency
	if [ ! -v PACKAGE ]; then printf ${RED}"PACKAGE not specified... PACKAGE=<package-name> make composer-req"${NC}"\nº"; exit 1; fi
	${DOCKER_RUN} --rm --interactive --tty --volume ${CURRENT_DIR}:/app --user ${USER}:${GROUP} composer require ${PACKAGE}

.PHONY=composer-rem
composer-rem: ## Remove existing composer dependency
	if [ ! -v PACKAGE ]; then printf ${RED}"PACKAGE not specified... PACKAGE=<package-name> make composer-rem"${NC}"\n"; exit 1; fi
	${DOCKER_RUN} --rm --interactive --tty --volume ${CURRENT_DIR}:/app --user ${USER}:${GROUP} composer remove ${PACKAGE}