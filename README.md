# Installation
```shell
composer install \
    && ./bin/console doctrine:database:create \
    && ./bin/console --no-interaction doctrine:migration:migrate \
    && ./bin/console --no-interaction doctrine:fixtures:load
```
# Running
## Webserver
```shell
php -S localhost:8000 -t public/
```
Navigate to ```http://localhost:8000/api/docs``` to see the API
## Tests
```shell
./bin/phpunit
```