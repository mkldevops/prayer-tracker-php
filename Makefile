# Setup ————————————————————————————————————————————————————————————————————————
SHELL		= bash
EXEC_PHP	  = php
REDIS		= redis-cli
GIT			 = git
GIT_AUTHOR = COil
SYMFONY_BIN	= ./symfony
SYMFONY		 = $(SYMFONY_BIN) console
COMPOSER	  = $(SYMFONY_BIN) composer
.DEFAULT_GOAL = help
#.PHONY		 = # Not needed for now

include .env

## —— 🐝 The Strangebuzz Symfony Makefile 🐝 ———————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

wait: ## Sleep 5 seconds
	sleep 5

run: up wait reload serve ## Start docker, load fixtures, populate the Elasticsearch index and start the web server

reload: load-fixtures populate ## Reload fixtures and repopulate the Elasticserch index

abort: down unserve ## Stop docker and the Symfony binary server

cc-redis: ## Flush all Redis cache
	$(REDIS) flushall

pull: gup install


include vendor/fardus/makefile/src/docker.makefile
include vendor/fardus/makefile/src/php.makefile
include vendor/fardus/makefile/src/symfony-bin.makefile
include vendor/fardus/makefile/src/symfony.makefile
include vendor/fardus/makefile/src/php-test.makefile
include vendor/fardus/makefile/src/php-analyze.makefile
include vendor/fardus/makefile/src/git.makefile
