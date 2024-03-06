RED=\033[0;31m
GREEN=\033[0;32m
YELLOW=\033[0;33m
BLUE=\033[0;34m
NO_COLOR=\033[0m

u: ## Update dependencies
	@echo "$(GREEN)Update dependencies$(NO_COLOR)"
	@composer update
	@echo "$(GREEN)Update dependencies done$(NO_COLOR)"

start: ## Start the server
	@echo "$(GREEN)Start the server$(NO_COLOR)"
	ddev start
	@echo "$(GREEN)Server started$(NO_COLOR)"

stop: ## Stop the server
	@echo "$(RED)Stop the server$(NO_COLOR)"
	ddev stop
	@echo "$(RED)Server stopped$(NO_COLOR)"

restart: ## Restart the server
	@echo "$(YELLOW)Restart the server$(NO_COLOR)"
	ddev restart
	@echo "$(YELLOW)Server restarted$(NO_COLOR)"

delete: ## Delete the server
	@echo "$(RED)Delete the server$(NO_COLOR)"
	ddev delete
	@echo "$(RED)Server deleted$(NO_COLOR)"

ssh: ## SSH connection to the server
	@echo "$(BLUE)SSH connection to the server$(NO_COLOR)"
	ddev ssh
	@echo "$(BLUE)SSH connection done$(NO_COLOR)"

req: ## Add a dependency with composer (option --dev available) | make req p=vendor/package dev=1
	@echo "$(GREEN)Add the dependency $(BLUE)$(p)$(NO_COLOR) $(if $(dev),as a development dependency)"
	@composer require $(p) $(if $(dev),--dev)
	@echo "$(GREEN)Dependency $(BLUE)$(p)$(NO_COLOR) added $(if $(dev),as a development dependency)"

rem: ## Remove a dependency with composer | make rem p=vendor/package
	@echo "$(RED)Remove the dependency $(BLUE)$(p)$(NO_COLOR)"
	@composer remove $(p)
	@echo "$(RED)Dependency $(BLUE)$(p)$(NO_COLOR) removed"
