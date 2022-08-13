# To deploy

1. Clone git repo on the server
2. Run `composer install`
3. Copy `.env.example` into `.env` and configure the required variables
4. Run `php key: generate`
5. Run `php artisan migrate:seed --force` to create the DB tables and fill the initial data
