# FinancialApp
Financial App using VueJs and Laravel 6

##Instalation (backend)
install dependencies
composer install

Configure .env
run migrations and seeds
php artisan migrate --seed

create passport tokens
php artisan passport:install
php artisan passport:client --personal
