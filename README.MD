# DAS

DAS is a replacement for CAS and WSO2 Carbon Identity Server. Currently it only services CAS services but has the ability to be extended to other protocols.

## Tech

* [Symfony 4.2] - PHP MVC Framework
* [composer] - PHP Dependency Manager
* [npm] - JS Dependency Manager
* [jQuery] - More JS stuff

## Requirements

* [PHP 7.2.0 or higher](https://www.php.net/)
* [MariaDB 10.0+](https://mariadb.org/)
* [Composer](https://getcomposer.org/)
* [NPM](https://nodejs.org/)

## Installation

Clone repository.

```sh
$ cd /var/www
$ git clone https:\\github.com\dustinscarberry\das.git
```

Install dependencies.

```sh
$ cd das
$ composer install
$ npm i
```

Create and configure .env.local at root of project directory.

```php
#required properties
APP_ENV=prod
APP_SECRET=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
DATABASE_URL=mysql://username:password@127.0.0.1:3306/databasename
LDAP_HOST=sample.com
LDAP_ENCRYPTION=ssl
LDAP_PORT=636
LDAP_REFERRALS=false
LDAP_VERSION=3
LDAP_ADMIN_USER="cn=Account,ou=Groups,dc=sample,dc=com"
LDAP_ADMIN_PASSWORD="xxxxxxx"
LDAP_SEARCH_BASE="dc=sample,dc=com"
```

Clear cache.

> This will need to be ran before / after any upgrades to clear the production app cache.

```sh
$ rm -rf /var/www/das/var/cache/*
```

Run migrations.

```sh
$ php bin/console doctrine:migrations:migrate
```

Load basic data.
```sh
$ php bin/console doctrine:fixtures:load
```

Run server (if needed)

Nginx Basic Config

server {
    listen 443 ssl http2;
    ssl_certificate /etc/nginx/ssl/cert.cer;
    ssl_certificate_key /etc/nginx/ssl/cert.key;
    server_name __;
    root /var/www/das/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
       fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
       fastcgi_param DOCUMENT_ROOT $realpath_root;
       internal;
   }

   location ~ \.php$ {
     return 404;
   }
}

## Documentation

//add documentation here
