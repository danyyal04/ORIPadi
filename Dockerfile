# ============================================================
# PadiGuard AI — Multi-stage Dockerfile for Google Cloud Run
# ============================================================

# ── Stage 1: Composer dependencies ──────────────────────────
FROM composer:2.7 AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ── Stage 2: Production image ────────────────────────────────
FROM php:8.2-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libxml2-dev \
    oniguruma-dev \
    curl \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        xml \
        exif \
        bcmath \
        opcache

# PHP config for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "upload_max_filesize=15M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size=15M"       >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "memory_limit=256M"       >> /usr/local/etc/php/conf.d/uploads.ini

# Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Supervisor config
COPY docker/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/html

# Copy app from build stage
COPY --from=composer /app .

# Permissions for storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Laravel: cache config for production
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache

# Cloud Run listens on 8080
EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
