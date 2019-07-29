# KD Tasker

API back-end of KD Tasker, application based on CQRS and event sourcing architecture.

## Requirements

* PHP 7.1.3 or greater
* MySQL 5.7 (MariaDB 10.3) or greater
* MongoDB 3.6 or greater

## Instructions

Clone this repository and install all required dependencies
```bash
$ git clone https://github.com/karol-dabrowski/kdtasker.git
$ cd ./kdtasker
$ composer install
```

Generate private and public keys (OpenSSL library required) and enter your private passphrase when you are asked for it
```bash
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

## Development environment setup

**Hint: Skip this step if you don't need to run your app in development mode**

Copy configuration file of development environment to local file
```bash
$ cp .env.dev .env.dev.local
```

Enter your local configuration values in ``.env.dev.local`` 
```bash
APP_ENV=dev #leave unchanged
APP_SECRET=your_secret_token #should have around 32 randomly chosen characters
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name" #your MySQL URL
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem #leave unchanged
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem #leave unchanged
JWT_PASSPHRASE_STRING=secret #the same string you provided during generating your keys in previous step
JWT_TOKEN_TTL=3600 #TTL for JWT token in seconds
MONGODB_HOST=localhost #MongoDB host
MONGODB_PORT=27017 #MongoDB port
MONGODB_USER= #MongoDB user
MONGODB_PASSWORD= #MongoDB password
MONGODB_DB=symfony #MongoDB database name
CORS_ALLOW_ORIGIN=. #leave unchanged
```

Run migrations and create event stream
```bash
$ php bin/console doctrine:migrations:migrate
$ php bin/console event-store:event-stream:create
```

Start projections, each one in different terminal windows
```bash
$ php bin/console event-store:projection:run task_projection
$ php bin/console event-store:projection:run open_task_projection
```

Run development server
```bash
php bin/console server:run
```

**Done! Application has been running in development mode.**

## Test environment setup

**Hint: Skip this step if you don't need to run your app in test mode**

Copy configuration file of test environment to local file
```bash
$ cp .env.test .env.test.local
```

Enter your local configuration values in ``.env.test.local`` 
```bash
APP_ENV=test #leave unchanged
APP_SECRET=your_secret_token #should have around 32 randomly chosen characters
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name" #your MySQL URL
DATABASE_HOST="127.0.0.1:3306" #MySQL host (the same as in DATABASE_URL)
DATABASE_USER="db_user" #MySQL username (the same as in DATABASE_URL)
DATABASE_PASSWORD="db_password" #MySQL password (the same as in DATABASE_URL)
DATABASE_NAME="db_name" #MySQL database name (the same as in DATABASE_URL)
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem #leave unchanged
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem #leave unchanged
JWT_PASSPHRASE_STRING=secret #the same string you provided during generating your keys in previous step
JWT_TOKEN_TTL=3600 #TTL for JWT token in seconds
KERNEL_CLASS=App\Kernel #leave unchanged
SYMFONY_DEPRECATIONS_HELPER=999999 #leave unchanged
MONGODB_HOST=localhost #MongoDB host
MONGODB_PORT=27017 #MongoDB port
MONGODB_USER= #MongoDB user
MONGODB_PASSWORD= #MongoDB password
MONGODB_DB=symfony #MongoDB database name
CORS_ALLOW_ORIGIN=. #leave unchanged
```

Run migrations and create event stream
```bash
$ php bin/console doctrine:migrations:migrate --env=test
$ php bin/console event-store:event-stream:create --env=test
```

Run tests
```bash
$ php vendor/bin/codecept run unit #Runs unit tests
$ php vendor/bin/codecept run api #Runs API tests
$ php vendor/bin/codecept run #Runs all tests
```

## Production environment setup

**This is alpha version of the application. Running in production mode is not recommended. This part of README will be completed in beta release.**

## License
Released under the MIT license.
