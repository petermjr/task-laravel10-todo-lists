

## Requirements

php ^8.1

MySQL

Laravel 10

## Installation

(If vendor and node_modules are not included)

Run `composer install`

Run `npm install --force`

---
## Database seed

Seed and migration will create necessary tables and roles + admin user
and some additional users. No image and comment seeds are included.

Run `php artisan migrate`

Run `php artisan db:seed`

## Running

Run with `php artisan serve` or through web server (apache)

## Default config

`APP_URL=http://task-laravel10-todo-lists.test`

`DB_CONNECTION=mysql`

`DB_HOST=127.0.0.1`

`DB_PORT=3306`

`DB_DATABASE=todolists`

`DB_USERNAME=root`

`DB_PASSWORD=`
