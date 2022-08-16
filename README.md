# To deploy

## First deploy

1. Push your changes to branch `master`
1. Login via ssh to hostinger
1. Clone git repo on the folder `aapdppapi`
1. Copy `.env.example` into `.env` and configure the required variables
1. Run `php composer.phar install`
1. Run `php key: generate`
1. Run `php artisan migrate:seed --force` to create the DB tables and fill the initial data
1. Make sure there is a `.htaccess` file in the root folder of the project with the content
    ```
    <IfModule mod_rewrite.c>
        RewriteEngine On
        # Send Requests To Front Controller...
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ public/index.php [L]
    </IfModule>
    ```

## Releases

1. Push your changes to branch `master`
1. Login via ssh to hostinger and access the folder `aapdppapi`
1. Run `php artisan migrate` to update the DB if needed
