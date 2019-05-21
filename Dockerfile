FROM php:7.3-apache

ENV APACHE_RUN_USER=daemon
COPY src/ /var/www/html/
