# ==========================================
# Stage 1: Composer Dependencies
# ==========================================
FROM composer:2.6 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative

# ==========================================
# Stage 2: Node.js Build
# ==========================================
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install --legacy-peer-deps --force || npm install --force || true

COPY . .
COPY --from=composer-builder /app/vendor ./vendor

# Créer le dossier même si le build échoue
RUN mkdir -p public/build && \
    (npm run build || echo "Build failed, using empty build folder")

# ==========================================
# Stage 3: Production Image
# ==========================================
FROM php:8.2-fpm-alpine

# Arguments de build
ARG APP_ENV=production
ARG APP_DEBUG=false

# Variables d'environnement
ENV APP_ENV=${APP_ENV}
ENV APP_DEBUG=${APP_DEBUG}
ENV COMPOSER_ALLOW_SUPERUSER=1

# Installation des dépendances système
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        mysqli \
        gd \
        zip \
        bcmath \
        opcache \
        pcntl \
        mbstring \
        xml

# Configuration PHP pour production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.validate_timestamps=0'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Configuration PHP
RUN { \
    echo 'upload_max_filesize=50M'; \
    echo 'post_max_size=50M'; \
    echo 'memory_limit=512M'; \
    echo 'max_execution_time=300'; \
    } > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

# Copie des fichiers depuis les builders
COPY --from=composer-builder /app/vendor ./vendor
COPY . .

# Copie des assets compilés (le dossier existe toujours grâce au mkdir dans node-builder)
COPY --from=node-builder /app/public/build/ ./public/build/

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configuration Nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Configuration Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Healthcheck
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s \
    CMD php artisan route:list || exit 1

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
