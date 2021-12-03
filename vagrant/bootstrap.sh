#!/usr/bin/env bash

# may need to reboot box after provisioning for ldap
# also export certificate and add to trusted certs on local machine

# add repos
add-apt-repository ppa:ondrej/php
apt-get update

# install needed packages
apt-get install -y php8.1-fpm
apt-get install -y nginx php8.1 php8.1-cli php8.1-mysql php8.1-ldap php8.1-gd php8.1-imagick php8.1-xml php8.1-curl php8.1-mbstring php8.1-zip php8.1-bcmath php8.1-gmp mariadb-server mariadb-client
apt-get upgrade -y

# write out nginx config files
>/etc/nginx/sites-enabled/default
cat >> /etc/nginx/sites-enabled/sites.conf << 'EOF'
upstream php-fpm {
        server unix:/var/run/php/php8.1-fpm.sock;
}

server {
	server_name das.dev;
	root /vagrant/public;
	listen 443 ssl;
  ssl_certificate /etc/nginx/ssl/selfsigned.crt;
  ssl_certificate_key /etc/nginx/ssl/selfsigned.key;
	index index.php;

	location = /favicon.ico {
		log_not_found off;
		access_log off;
	}

	location = /robots.txt {
		allow all;
		log_not_found off;
		access_log off;
	}

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass php-fpm;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    # return 404 for all other php files not matching the front controller
    location ~ \.php$ {
        return 404;
    }


	location ~ /\. {
		deny all;
	}

	# Feed
	location ~* \.(?:rss|atom)$ {
		expires 1h;
		add_header Cache-Control "public";
	}

	# Media: images, icons, video, audio, HTC
	location ~* \.(?:jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc)$ {
		expires 1M;
		access_log off;
		add_header Cache-Control "public";
	}

	# CSS and Javascript
	location ~* \.(?:css|js|woff)$ {
		expires 1y;
		access_log off;
		add_header Cache-Control "public";
	}
}

server {
	server_name caslink.dev;
	root /vagrant/vagrant/caslink;
  listen 443 ssl;
  ssl_certificate /etc/nginx/ssl/selfsigned.crt;
  ssl_certificate_key /etc/nginx/ssl/selfsigned.key;
	index index.php;

	location = /favicon.ico {
		log_not_found off;
		access_log off;
	}

	location = /robots.txt {
		allow all;
		log_not_found off;
		access_log off;
	}

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ \.php$ {
        fastcgi_pass php-fpm;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }
}

server {
	server_name caslinksaml.dev;
	root /vagrant/vagrant/caslinksaml;
  listen 443 ssl;
  ssl_certificate /etc/nginx/ssl/selfsigned.crt;
  ssl_certificate_key /etc/nginx/ssl/selfsigned.key;
	index index.php;

	location = /favicon.ico {
		log_not_found off;
		access_log off;
	}

	location = /robots.txt {
		allow all;
		log_not_found off;
		access_log off;
	}

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ \.php$ {
        fastcgi_pass php-fpm;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }
}
EOF

# create ssl certificates
mkdir /etc/nginx/ssl
openssl req -x509 -nodes -days 730 -newkey rsa:2048 \
  -keyout /etc/nginx/ssl/selfsigned.key  -out /etc/nginx/ssl/selfsigned.crt \
  -config /vagrant/vagrant/certconfig.ext -sha256

systemctl reload nginx

# install composer
cd /home/vagrant
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm -rf composer-setup.php

# setup mariadb user account
mysql -e "USE mysql;"
mysql -e "CREATE USER 'vagrant'@'%' IDENTIFIED BY 'vagrant';"
mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'vagrant'@'%';"
mysql -e "FLUSH PRIVILEGES;"

# setup databases
mysql -e "CREATE DATABASE demo;"
mysql -e "CREATE DATABASE demo_test;"

# change user accounts for web stack
sed -i 's/www-data/vagrant/g' /etc/nginx/nginx.conf
sed -i 's/www-data/vagrant/g' /etc/php/8.1/fpm/pool.d/www.conf

# change mysql to listen externally for testing
sed -s 's/127.0.0.1/0.0.0.0/g' /etc/mysql/mariadb.conf.d/50-server.cnf

# fix ldap config for dev
echo 'TLS_REQCERT never' >> /etc/ldap/ldap.conf

# restart services
systemctl restart php8.1-fpm
systemctl restart nginx
systemctl restart mysqld

# last minute updates and upgrades
apt-get update
apt-get upgrade -y
