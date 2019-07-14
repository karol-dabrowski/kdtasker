# KD Tasker

API back-end of KD Tasker, todo application based on CQRS and event sourcing architecture.

## Instructions

Clone this repository and install all required dependencies.
```bash
$ git clone https://github.com/karol-dabrowski/kdtasker.git
$ cd ./kdtasker
$ composer install
```

Generate JWT keys (OpenSSL library required) and enter your private passphrase when you are asked about it.
```bash
$ openssl genrsa -out config/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

##### Development environment setup

