.PHONY: install validate lint cbf

install:
	composer install

validate:
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

cbf:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin
