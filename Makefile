.DEFAULT_GOAL := build

USER:=$(shell id -u)
GROUP:=$(shell id -g)

PHPRUN:=@docker-compose run --rm php-cli

build:
	@docker-compose build
	@docker-compose run --rm --user=$(USER):$(GROUP) php-cli composer -vv install

comins:
	$(PHPRUN) composer install

phpcs:
	$(PHPRUN) vendor/bin/phpcs -p  --standard=./ruleset.xml --extensions=php src/
	$(PHPRUN) vendor/bin/phpcs -p --standard=./ruleset.xml --extensions=php tests/

phpcbf:
	$(PHPRUN) vendor/bin/phpcbf -p --standard=./ruleset.xml --extensions=php src/
	$(PHPRUN) vendor/bin/phpcbf -p --standard=./ruleset.xml --extensions=php tests/

phpstan:
	$(PHPRUN) vendor/bin/phpstan analyse src tests

test:
	@docker-compose run --rm php-cli vendor/bin/phpunit

parse:
	@docker-compose run --rm php-cli bin/app parse

bash:
	@docker-compose run --rm php-cli bash
