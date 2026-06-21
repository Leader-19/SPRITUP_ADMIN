# syntax=docker/dockerfile:1

# -------------------- COMPOSER + PHP EXTENSIONS --------------------
FROM php:8.3-cli AS vendor

WORKDIR /app

# Install PHP extensions needed for Composer (IMPORTANT FIX: gd included here)
RUN apt-get update && apt-get install -y \
    git unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo_mysql \
        zip \
        intl \
        bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first (better caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# Copy full project
COPY . .

RUN composer dump-autoload --optimize


# -------------------- FRONTEND BUILD (VITE / NODE) --------------------
FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# -------------------- FINAL RUNTIME (APACHE + PHP) --------------------
FROM php:8.3-apache

WORKDIR /var/www/html

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Install PHP extensions for runtime
RUN apt-get update && apt-get install -y \
    git unzip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        intl \
        gd \
        zip \
        bcmath \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Fix Apache root for Laravel
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Copy backend + vendor + build
COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

# Permissions (VERY IMPORTANT)
RUN chown -R www-data:www-data storage bootstrap/cache

# Render HTTPS fix (important for mixed content)
RUN echo "SetEnvIf X-Forwarded-Proto https HTTPS=on" >> /etc/apache2/apache2.conf

# Laravel safety (avoid crash on build)
RUN php artisan optimize:clear || true
RUN php artisan storage:link || true

EXPOSE 80

CMD ["apache2-foreground"]
