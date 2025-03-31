.PHONY: lint cbf

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

cbf:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin
