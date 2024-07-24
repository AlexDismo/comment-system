# Comment System

This is a guide to set up and run comment system application. Please follow the instructions carefully.

## Important Note

Don't forget to modify the Dockerfile to replace the username with the owner of your local machine.

## Prerequisites

Before you start, make sure the following directories and files are created:

- `storage/framework/sessions`
- `storage/logs/backend.log`
- `bootstrap/cache`
- `storage/app/public/images&texts`
- Environment files

## Running the Application

To run the application, execute the following command:

```bash
docker-compose up --build
```

## After Launch

Once the Docker container is up and running, you need to perform the following steps inside the `app` container:

```bash
php artisan config:clear
php artisan migrate
php artisan db:seed
php artisan storage:link
composer dump-autoload
```

