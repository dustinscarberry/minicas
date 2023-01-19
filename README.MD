# MiniCAS

MiniCAS is a simplified CAS replacement server, built on Symfony. Currently it only services CAS services but has the ability to be extended to other protocols.

## Tech

* [Symfony 6.2] - PHP MVC Framework
* [Composer] - PHP Package Manager
* [Yarn] - JS Package Manager
* [jQuery] - More JS stuff

## Requirements

* [PHP 8.0 or higher](https://www.php.net/)
* [MariaDB 10.0+](https://mariadb.org/)
* [Composer](https://getcomposer.org/)
* [Yarn](https://yarnpkg.com/)

## Supported Protocols

MiniCAS support the following CAS protocols.

CAS 2.0
CAS 3.0

(Note: Proxies are not supported)

SAML Validation is supported (currently in alpha)

## Installation (Standard)

1. Clone repository

```sh
$ cd /var/www
$ git clone git@github.com:dustinscarberry/minicas.git
```

2. Install dependencies

```sh
$ cd minicas
$ composer i --no-scripts --no-dev
$ yarn install --immutable
$ yarn prod
```

3. Create and configure .env.local at root of project directory

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

4. Run migrations

```sh
$ php bin/console doctrine:migrations:migrate
```

5. Run server (if needed)

Nginx Basic Config

```nginx
server {
    listen 443 ssl http2;
    ssl_certificate /etc/nginx/ssl/cert.cer;
    ssl_certificate_key /etc/nginx/ssl/cert.key;
    server_name __;
    root /var/www/minicas/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
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
```

6. Run setup (Visit http://localhost/setup)

## Installation (Docker)

1. Clone repository

```sh
$ cd /var/docker
$ git clone git@github.com:dustinscarberry/minicas.git
```

2. Create docker-compose.override.yml and modify ports and env vars for your environment
```sh
$ cd minicas
$ cp docker-compose.override.example.yml docker-compose.override.yml
```

3. Run docker container

```sh
$ docker-compose up -d --build
```

4. Start server (nginx, if needed)

Nginx Basic Proxy Config

```nginx
server {
    listen 443 ssl http2;
    ssl_certificate /etc/nginx/ssl/cert.cer;
    ssl_certificate_key /etc/nginx/ssl/cert.key;
    server_name __;

    location / {
        proxy_pass http://127.0.0.1:8001;
        proxy_redirect off;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Host $server_name;
    }
}
```

5. Run setup (Visit http://localhost/setup)

## Upgrades (Standard)

1. Get updates from repo

```sh
git pull
```

2. Run database migrations

```sh
php bin/console doctrine:migrations:migrate
```

3. Update dependencies

```sh
composer i --no-scripts --no-dev
yarn install --immutable
yarn prod
```

4. Clear cache

```sh
rm -rf var/cache/*
```

## Documentation

* Each service must be a registered service provider in order to use the MiniCAS server. Identity Providers are required (you will only need one for sso) to delegate authentication to for each registered service.

* Attributes are managed under the attributes panel. Custom attribute mappings to AD can be added as needed.

* When adding an IDP you will need to set the username mapping to the correct AD attribute. This is used to map the returned IDP user to the AD search.

* When adding an SP you can override the returned user attribute by specifying a user mapping.

* Admin user's can be managed under the users panel.

* User sessions are kept with a commonauth cookie allowing future login requests after the first one to not require delegation with the IDP.
