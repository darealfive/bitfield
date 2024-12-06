.DEFAULT_GOAL := help

# magic help command
# https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


makefile_path := $(abspath $(lastword $(MAKEFILE_LIST)))
makefile_dir := $(dir $(makefile_path))


## -- DOCKER TESTING

.PHONY: docker-tester-build
docker-tester-build: ## Builds the testing docker image
	@printf "\e[1;35m"
	@echo "┌──────────────────────────────────────┐"
	@echo "│ Building docker image for testing... │"
	@echo "└──────────────────────────────────────┘"
	@printf "\e[0m"
	@$(makefile_dir)run.sh tester-build

.PHONY: docker-tester-run
docker-tester-run: ## Runs PHP tests within a docker container
	@printf "\e[1;35m"
	@echo "┌───────────────────────────────────────────┐"
	@echo "│ Running PHP tests via docker container... │"
	@echo "└───────────────────────────────────────────┘"
	@printf "\e[0m"
	@$(makefile_dir)run.sh tester-run

.PHONY: docker-tester-build-run
docker-tester-build-run: ## Build and runs PHP tests within a docker container
	@make docker-tester-build && make docker-tester-run