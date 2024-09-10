# Clair Backend - Take Home Assignment
```
This Laravel 11 application is designed to :
- sync pay items from an external API
- store them in MongoDB
The job dispatch system :
- fetches pay items
- handles responses
- processes data using MongoDB collections
```

## Environment
- PHP v8.2.23
- Laravel v11
- MongoDB
- Windows 10

## Tech Stacks
- PHP + Laravel
- MongoDB
- PHPUnit

## How to setup
- Clone the repository
  ```shell
    git clone git@github.com:dwaynewe/clair.git
    cd clair
  ```
- Install dependencies
  ```shell
    composer install
  ```
- MongoDB setup
    Follow this [Guide](https://medium.com/@jsxfardin/mongodb-integration-with-laravel-11-2d191fadfe9e) to set up MongoDB in Laravel.
- Run migrations
  ```shell
    php artisan migrate
  ```
- Run
  ```shell
    php artisan serve
  ```
  This will host the project on http://localhost:8000

## Testing the sync job
- Dispatch the sync job
  ```shell
    php artisan dispatch:sync
  ```
- Run unit test
  ```shell
    php artisan test
  ```
