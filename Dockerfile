FROM serversideup/php:8.3-fpm-nginx

USER root

RUN apt-get update `
    && apt-get install -y libicu-dev `
    && docker-php-ext-install intl `
    && apt-get clean `
    && rm -rf /var/lib/apt/lists/*

USER www-data
