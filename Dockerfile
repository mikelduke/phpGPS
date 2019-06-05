FROM php:7.3-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# https://docs.docker.com/samples/library/php/#configuration
# Use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ENV PORT 80
ENV APACHE_RUN_USER=daemon

COPY src/ /var/www/html/
RUN rm -rf /var/www/html/install

# Set Port https://github.com/docker-library/php/issues/94
CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground
