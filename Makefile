# Makefile file
# —— Inspired by ———————————————————————————————————————————————————————————————
# http://fabien.potencier.org/symfony4-best-practices.html
# https://speakerdeck.com/mykiwi/outils-pour-ameliorer-la-vie-des-developpeurs-symfony?slide=47
# https://blog.theodo.fr/2018/05/why-you-need-a-makefile-on-your-project/

# Setup ————————————————————————————————————————————————————————————————————————
SHELL		= bash
PROJECT		 = strangebuzz
EXEC_PHP	  = php
REDIS		= redis-cli
GIT			 = git
GIT_AUTHOR = COil
SYMFONY_BIN	= ./symfony
SYMFONY		 = $(SYMFONY_BIN) console
COMPOSER	  = $(SYMFONY_BIN) composer
DOCKER	 = docker-compose
.DEFAULT_GOAL = help
#.PHONY		 = # Not needed for now

include .env

## —— 🐝 The Strangebuzz Symfony Makefile 🐝 ———————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

wait: ## Sleep 5 seconds
	sleep 5
## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
install: composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install

update: composer.json ## Update vendors according to the composer.json file
	$(COMPOSER) update

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
symfony: $(SYMFONY_BIN)

sf: ## List all Symfony commands
	$(SYMFONY)

cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) c:c

warmup: ## Warmump the cache
	$(SYMFONY) cache:warmup

fix-perms: ## Fix permissions of all var files
	chmod -R 777 var/*

assets: ## Install the assets with symlinks in the public folder
	$(SYMFONY) assets:install public/ --symlink --relative

rm-var: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

purge: rm-var cc warmup assets fix-perms ## Purge symfony project (assets, permissions, wramup)

routes: ## get routes of project
	$(SYMFONY_BIN) console debug:router

translation: # update translation
	$(SYMFONY_BIN) console translation:update fr --force --output-format yaml

doctrine-validate: ## Check validate schema
	$(SYMFONY_BIN) console doctrine:schema:validate

migration: ## Make migration command
	$(SYMFONY_BIN) console make:migration

doctrine-migrate: ##  Migrate with doctrine migration
	$(SYMFONY_BIN) console doctrine:migrations:migrate -n

## —— Symfony binary 💻 ————————————————————————————————————————————————————————
bin-install: ## Download and install the binary in the project (file is ignored)
	curl -sS https://get.symfony.com/cli/installer | bash
	mv ~/.symfony/bin/symfony .

cert-install: symfony ## Install the local HTTPS certificates
	$(SYMFONY_BIN) server:ca:install

serve: symfony ## Serve the application with HTTPS support
	$(SYMFONY_BIN) serve --daemon --port=$(PORT)

unserve: symfony ## Stop the web server
	$(SYMFONY_BIN) server:stop

serve-log: symfony ## Stop the web server
	$(SYMFONY_BIN) server:log

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
ps: docker-compose.yaml ## show current processes
	$(DOCKER) -f docker-compose.yaml ps

up: docker-compose.yaml ## Start the docker hub (MySQL,redis,adminer,elasticsearch,head,Kibana)
	$(DOCKER) -f docker-compose.yaml up -d

down: docker-compose.yaml ## Stop the docker hub
	$(DOCKER) down --remove-orphans

## —— Project 🐝 ———————————————————————————————————————————————————————————————
run: up wait reload serve ## Start docker, load fixtures, populate the Elasticsearch index and start the web server

reload: load-fixtures populate ## Reload fixtures and repopulate the Elasticserch index

abort: down unserve ## Stop docker and the Symfony binary server

cc-redis: ## Flush all Redis cache
	$(REDIS) flushall

commands: ## Display all commands in the project namespace
	$(SYMFONY) list $(PROJECT)

load-fixtures: ## Build the db, control the schema validity, load fixtures and check the migration status
	$(SYMFONY) doctrine:fixtures:load -n

load-database: ## Build the db, control the schema validity, load fixtures and check the migration status
	$(SYMFONY) doctrine:cache:clear-metadata
	$(SYMFONY) doctrine:database:create --if-not-exists
	$(SYMFONY) doctrine:schema:drop --force
	$(SYMFONY) doctrine:schema:create
	$(SYMFONY) doctrine:schema:validate
	$(SYMFONY) doctrine:fixtures:load -n
	$(SYMFONY) doctrine:schema:validate

init-snippet: ## Initialize a new snippet
	$(SYMFONY) $(PROJECT):init-snippet

## —— Tests ✅ —————————————————————————————————————————————————————————————————
test: phpunit.xml* ## Launch main functionnal and unit tests
	./bin/phpunit --testsuite=main --stop-on-failure

test-external: phpunit.xml* ## Launch tests implying external resources (api, services...)
	./bin/phpunit --testsuite=external --stop-on-failure

test-all: phpunit.xml* ## Launch all tests
	./bin/phpunit --stop-on-failure

## —— Coding standards ✨ ——————————————————————————————————————————————————————
analyze-install:
	$(COMPOSER) req --dev phpstan/phpstan
	$(COMPOSER) req --dev phpstan/phpstan-symfony phpstan/phpstan-doctrine phpstan/phpstan-deprecation-rules phpstan/phpstan-phpunit slam/phpstan-extensions thecodingmachine/phpstan-strict-rules
	$(COMPOSER) req --dev squizlabs/php_codesniffer
	$(COMPOSER) req --dev friendsofphp/php-cs-fixer
	$(COMPOSER) req --dev vimeo/psalm

cs: codesniffer stan ## Launch check style and static analysis

codesniffer: ## Run php_codesniffer only
	./vendor/squizlabs/php_codesniffer/bin/phpcs --standard=phpcs.xml -n -p src/

stan: ## Run PHPStan only
	./vendor/bin/phpstan analyse -l max --memory-limit 1G -c phpstan.neon src/

psalm: ## Run psalm only
	./vendor/bin/psalm --show-info=false

init-psalm: ## Init a new psalm config file for a given level, it must be decremented to have stricter rules
	[ -f ./psalm.xml ] && rm ./psalm.xml || echo 'no ./psalm.xml'
	./vendor/bin/psalm --init src/ 3

cs-fix: ## Run php-cs-fixer and fix the code.
	./vendor/bin/php-cs-fixer fix src/

sonar: sonar-project.properties ## sonar scan src directory
	sonar-scanner

## —— Stats 📜 —————————————————————————————————————————————————————————————————
stats: ## Commits by hour for the main author of this project
	$(GIT) log --author="$(GIT_AUTHOR)" --date=iso | perl -nalE 'if (/^Date:\s+[\d-]{10}\s(\d{2})/) { say $$1+0 }' | sort | uniq -c|perl -MList::Util=max -nalE '$$h{$$F[1]} = $$F[0]; }{ $$m = max values %h; foreach (0..23) { $$h{$$_} = 0 if not exists $$h{$$_} } foreach (sort {$$a <=> $$b } keys %h) { say sprintf "%02d - %4d %s", $$_, $$h{$$_}, "*"x ($$h{$$_} / $$m * 50); }'
