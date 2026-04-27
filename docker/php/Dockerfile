FROM php:8.2-fpm-bookworm

COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends git unzip; \
    install-php-extensions \
        mysqli \
        pdo_mysql \
        mbstring \
        gd \
        zip \
        xml \
        sqlite3 \
        pdo_sqlite \
        exif \
        opcache \
    ; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/app
