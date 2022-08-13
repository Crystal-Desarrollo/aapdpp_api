# To deploy

1. Push your changes to branch `master`
1. Login via ssh to hostinger
1. Clone git repo on the corresponding folder of the server
1. Copy `.env.example` into `.env` and configure the required variables
1. Run `composer install`
1. Run `php key: generate`
1. Run `php artisan migrate:seed --force` to create the DB tables and fill the initial data
