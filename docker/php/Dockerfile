FROM php:8.5-fpm-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql \
    && rm -rf /var/lib/apt/lists/* \
    && cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY --from=composer/composer:2-bin /composer /usr/local/bin/composer

COPY docker/php/conf.d/errors.ini /usr/local/etc/php/conf.d/worldgraph-errors.ini

ENV COMPOSER_HOME=/var/www/html/storage/cache/composer

WORKDIR /var/www/html
