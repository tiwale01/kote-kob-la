FROM node:20 AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


FROM serversideup/php:8.3-fpm-nginx

USER root

RUN apt-get update \
    && apt-get install -y libicu-dev git unzip \
    && docker-php-ext-install intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data . /var/www/html
COPY --from=frontend --chown=www-data:www-data /app/public/build /var/www/html/public/build

RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

ENV SSL_MODE=off
ENV PHP_OPCACHE_ENABLE=1
