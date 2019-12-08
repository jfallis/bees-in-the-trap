.PHONY: help

.DEFAULT_GOAL := help

help: ## help (default action)
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

analysis: vendor ## Static analysis testing
	vendor/bin/phpstan analyse

coverage: vendor ## Text Code coverage
	vendor/bin/phpunit --configuration=phpunit.xml.dist --coverage-text

coverage-html: vendor ## HTML code coverage
	vendor/bin/phpunit --configuration=phpunit.xml.dist --coverage-html var/build/coverage-report

cs: vendor ## Code fixer
	php-cs-fixer fix ${PWD} -v

infection: vendor ## Infection testing
	vendor/bin/infection --min-covered-msi=80 --min-msi=80

it: cs analysis test infection ## Bundle of tools for dev

test: vendor ## Unit testing
	vendor/bin/phpunit --configuration=phpunit.xml.dist

vendor: composer.json composer.lock ## Composer
	composer self-update
	composer validate
	composer install
