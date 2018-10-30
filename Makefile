install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 public routes tests app

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 public routes tests app

test:
	vendor/bin/phpunit

run:
	php -S localhost:8000 -t public

logs:
	tail -f storage/logs/lumen.log