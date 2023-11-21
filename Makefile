CONSOLE = php bin/console
CONSOLE_TEST = php bin/console

server:
	symfony server:start -d
	npm run dev-server

prod:
	yarn run encore production

push:
	git push -u origin main

prod-install:
	APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

doctrine-validate:
	$(CONSOLE) doctrine:schema:validate --skip-sync $c

reset-database: drop-database database migrate load-fixtures ## Reset database with migration

database: ## Create database if no exists
	$(CONSOLE) migrate:status

drop-database: ## Drop the database
	$(CONSOLE) doctrine:database:drop --force --if-exists

migration: ## Apply doctrine migration
	$(CONSOLE) make:migration

migrate: ## Apply doctrine migrate
	$(CONSOLE) doctrine:migration:migrate -n --all-or-nothing

generate-jwt: ## Generate private and public keys
	$(CONSOLE) lexik:jwt:generate-keypair --overwrite -q $c

## —— Tests ✅ ————————————————————————————————————————————————————————————
test-database: ### load database schema
	$(CONSOLE_TEST) doctrine:database:drop --if-exists --force --env=test
	$(CONSOLE_TEST) doctrine:database:create --if-not-exists --env=test
	$(CONSOLE_TEST) doctrine:migration:migrate -n --all-or-nothing --env=test
	$(CONSOLE_TEST) doctrine:fixtures:load -n --env=test

pest:
	$(CONSOLE) ./vendor/bin/pest

test: phpunit.xml* ## Launch main functional and unit tests, stopped on failure
	$(CONSOLE) ./vendor/bin/pest --stop-on-failure $c

test-all: phpunit.xml* test-load-fixtures ## Launch main functional and unit tests
	$(DOCKER_TEST) ./vendor/bin/pest

test-report: phpunit.xml* test-load-fixtures ## Launch main functional and unit tests with report