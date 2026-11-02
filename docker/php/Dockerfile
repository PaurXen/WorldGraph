FROM php:8.5-fpm-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql \
    && rm -rf /var/lib/apt/lists/* \
    && cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html
