.PHONY: install validate lint cbf test test-coverage

install:
	composer install

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

cbf:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin

test:
	composer exec --verbose phpunit

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit -- --coverage-clover=build/logs/clover.xml

test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit -- --coverage-text
