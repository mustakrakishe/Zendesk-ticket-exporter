FROM php:7.4-fpm

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get update \
    && apt-get install --yes zip unzip \
    && php composer.phar install
