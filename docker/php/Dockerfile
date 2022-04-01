FROM php:7.4-fpm

WORKDIR /var/www/html/

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get update \
    && apt-get install --yes zip unzip

CMD bash -c "composer install"
