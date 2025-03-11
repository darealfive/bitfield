.DEFAULT_GOAL := help

# magic help command
# https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


makefile_path := $(abspath $(lastword $(MAKEFILE_LIST)))
makefile_dir := $(dir $(makefile_path))
docker_image_php_version := 8.1.31-cli-alpine3.21
docker_image_composer_version := 2.7.6
docker_image_version := v1.0.0
docker_image_name_tester := darealfive/bitfield-tester:$(docker_image_version)
docker_image_working_dir := /php_library


## -- DOCKER TESTING

.PHONY: docker-tester-build
docker-tester-build: ## Builds the testing docker image
	@printf "\e[1;35m"
	@echo "┌──────────────────────────────────────┐"
	@echo "│ Building docker image for testing... │"
	@echo "└──────────────────────────────────────┘"
	@printf "\e[0m"
	@DOCKER_BUILDKIT=1 docker build \
	--build-arg COMPOSER_VERSION=$(docker_image_composer_version) \
	--build-arg PHP_VERSION=$(docker_image_php_version) \
	--build-arg WORKING_DIR=$(docker_image_working_dir) \
	-t $(docker_image_name_tester) \
	-f test.Dockerfile \
	--target tester \
	.

.PHONY: docker-tester-run
docker-tester-run: ## Runs PHP-UNIT tests within a docker container
	@printf "\e[1;35m"
	@echo "┌───────────────────────────┐"
	@echo "│ Running PHP-UNIT tests... │"
	@echo "└───────────────────────────┘"
	@printf "\e[0m"
	@make composer-install-dev && docker run \
	--rm \
	--volume "$(makefile_dir)":"$(docker_image_working_dir)" \
	$(docker_image_name_tester) \
	vendor/bin/phpunit tests

.PHONY: docker-tester-run-coverage
docker-tester-run-coverage: ## Runs PHP-UNIT tests with code-coverage within a docker container
	@printf "\e[1;35m"
	@echo "┌───────────────────────────────────────────┐"
	@echo "│ Running PHP-UNIT tests + code-coverage... │"
	@echo "└───────────────────────────────────────────┘"
	@printf "\e[0m"
	@make composer-install-dev && docker run \
	--rm \
	--volume "$(makefile_dir)":"$(docker_image_working_dir)" \
	$(docker_image_name_tester) \
	vendor/bin/phpunit tests --coverage-html=coverage

.PHONY: docker-tester-build-run
docker-tester-build-run: ## Build and runs PHP-UNIT tests within a docker container
	@make docker-tester-build && make docker-tester-run

.PHONY: docker-tester-run-shell
docker-tester-run-shell: ## Runs a shell within the testing docker container
	@printf "\e[1;35m"
	@echo "┌────────────────────┐"
	@echo "│ Executing shell... │"
	@echo "└────────────────────┘"
	@printf "\e[0m"
	@docker run \
	-it \
	--rm \
	--volume "$(makefile_dir)":"$(docker_image_working_dir)" \
	$(docker_image_name_tester)

.PHONY: composer-install-dev
composer-install-dev: ## Installs PHP dev dependencies
	@printf "\e[1;35m"
	@echo "┌────────────────────────────────┐"
	@echo "│ Installing DEV dependencies... │"
	@echo "└────────────────────────────────┘"
	@printf "\e[0m"
	@docker run \
	--rm \
	--volume "$(makefile_dir)":"$(docker_image_working_dir)" \
	$(docker_image_name_tester) \
	composer install
