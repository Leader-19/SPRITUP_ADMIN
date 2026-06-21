# syntax=docker/dockerfile:1

# -------------------- COMPOSER BUILD --------------------
FROM php:8.3-cli AS vendor

WORKDIR /app

# Install system + PHP extensions (for Composer)
RUN apt-get update && apt-get install -y \
    git unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip intl bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# IMPORTANT: copy FULL project FIRST
COPY . .

# Now Composer can safely run artisan scripts
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader


# -------------------- FRONTEND BUILD --------------------
FROM node:20-bookworm-slim AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# -------------------- FINAL IMAGE (APACHE) --------------------
FROM php:8.3-apache

WORKDIR /var/www/html

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Install PHP extensions
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

# Fix Apache for Laravel
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Copy application
COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Render HTTPS support
RUN echo "SetEnvIf X-Forwarded-Proto https HTTPS=on" >> /etc/apache2/apache2.conf

# Safe Laravel optimization (NEVER FAIL BUILD)
RUN php artisan optimize:clear || true
RUN php artisan storage:link || true

EXPOSE 80

CMD ["apache2-foreground"]
