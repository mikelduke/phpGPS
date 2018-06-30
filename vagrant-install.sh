#!/usr/bin/env bash

source /vagrant/default-env.sh

apt-get update
apt-get install -y curl apache2 git mysql-server \
    php-pear php-fpm php-dev php-zip php-curl php-xmlrpc \
    php-gd php-mysql php-mbstring php-xml libapache2-mod-php

sudo service apache2 restart

usermod -aG www-data vagrant

php -r 'echo "\n\nPHP Installed.\n\n\n";'

sudo mysql -e "create database $PHPGPS_DB_NAME; GRANT ALL PRIVILEGES ON $PHPGPS_DB_NAME.* TO $PHPGPS_DB_USER@localhost IDENTIFIED BY '$PHPGPS_DB_PASS'"

cd /vagrant/src/install
php -f install.php

echo ''
echo 'mysql setup complete'
echo ''
