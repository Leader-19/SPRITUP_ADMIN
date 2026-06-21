# syntax=docker/dockerfile:1

FROM composer:2 AS composer-bin

FROM node:22-bookworm-slim AS frontend
WORKDIR /var/www/html

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        php-cli \
        php-bcmath \
        php-curl \
        php-gd \
        php-intl \
        php-mbstring \
        php-pdo-mysql \
        php-xml \
        php-zip; \
    rm -rf /var/lib/apt/lists/*

COPY --from=composer-bin /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-progress --prefer-dist --no-dev --no-scripts

COPY . .
RUN composer dump-autoload --optimize && npm ci && npm run build

FROM php:8.3-apache
WORKDIR /var/www/html

ENV CACHE_STORE=array SESSION_DRIVER=array QUEUE_CONNECTION=sync

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libxml2-dev \
        libonig-dev \
        default-mysql-client; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        intl \
        pcntl \
        pdo_mysql \
        sockets \
        zip; \
    a2enmod rewrite; \
    rm -rf /var/lib/apt/lists/*

COPY --from=frontend /var/www/html/vendor ./vendor
COPY --from=frontend /var/www/html/public/build ./public/build
COPY . .

RUN set -eux; \
    chown -R www-data:www-data storage bootstrap/cache; \
    php artisan package:discover --ansi; \
    php artisan storage:link --force || true; \
    php artisan optimize:clear

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN set -eux; \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf; \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/sites-available/*.conf

EXPOSE 80

CMD ["apache2-foreground"]
