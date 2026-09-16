# ==============================================================================
# Stage 1: Build Frontend Assets (Vite + Tailwind CSS)
# ==============================================================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Salin manifest dependensi Node untuk caching layer
COPY package*.json ./
RUN npm install

# Salin seluruh kode agar Tailwind CSS dapat mendeteksi semua class di Blade template
COPY . .

# Jalankan build Vite (output di public/build)
RUN npm run build

# ==============================================================================
# Stage 2: Runtime Production (PHP 8.3 FPM + Nginx + Composer)
# ==============================================================================
FROM php:8.3-fpm-alpine

LABEL maintainer="SlipGaji Dev"
LABEL description="Laravel Application optimized for Render.com"

# Install dependensi sistem dan library untuk ekstensi PHP
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev \
    bash

# Konfigurasi & instal ekstensi PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        bcmath \
        mbstring \
        zip \
        gd \
        intl \
        opcache \
        pcntl \
        exif

# Salin Composer binary dari official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin konfigurasi composer terlebih dahulu
COPY composer.json composer.lock ./

# Instal dependensi composer (tanpa dev packages)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Salin seluruh source code proyek
COPY . .

# Salin hasil build frontend dari Stage 1
COPY --from=frontend-builder /app/public/build ./public/build

# Generate optimized autoload
RUN composer dump-autoload --optimize --no-dev

# Salin file konfigurasi
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Samakan user nginx menjadi www-data & buat folder runtime nginx
RUN sed -i 's/user nginx;/user www-data;/' /etc/nginx/nginx.conf 2>/dev/null || true \
    && mkdir -p /run/nginx \
    && sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

# Atur ownership dan permission direktori storage, cache, dan log
RUN chown -R www-data:www-data /var/www/html /var/lib/nginx /var/log/nginx /run/nginx \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Port default Render (Render menginjeksikan environment variable $PORT dinamis)
EXPOSE 80 10000

# Jalankan entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
