FROM php:7.3-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# https://docs.docker.com/samples/library/php/#configuration
# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Override with custom opcache settings
# COPY config/opcache.ini $PHP_INI_DIR/conf.d/

ENV APACHE_RUN_USER=daemon
COPY src/ /var/www/html/
