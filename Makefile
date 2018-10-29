install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 src bin tests

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 src bin tests

test:
	phpunit

run:
	php -S localhost:8000 -t public

logs:
	tail -f storage/logs/lumen.log