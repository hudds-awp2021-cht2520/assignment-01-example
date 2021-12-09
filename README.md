# Annotare

Annotare is a small application that allows logged in users take and store notes.

## Tech.

- Laravel 8
- MySQL
- Tailwind

## Pre-reqs

- Web server capable of serving PHP
- Composer
- MySql
- Node.js & npm for front end pipeline

## Getting started with Annotare

1. Create a MySQL database to use for development.
2. Clone the repo:
```
git clone git@github.com:hudds-awp2021-cht2520/assignment-01-example.git
```
3. CD into the project directory:
```
cd assignment-01-example
```
4. Install the PHP dependencies:
```
php composer install
```
5. Install the front end dependencies:
```
npm install
```
6. Add your DB details to your `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mydb
DB_USERNAME=myuser
DB_PASSWORD=mypassword
```
7. Add the application key to the environment:
```
php artisan key:generate
```
8. Run the database migrations:
```
php artisan migrate
```
9. Run the database seeders:
```
php artisan db:seed
```
10. Configure a virtual host in your dev webserver, pointing to the `/public` directory.

## Running the tests

```
php artisan test
```
