FROM php:8.2.25-fpm-alpine3.20
# lumen packages
RUN docker-php-ext-install mysqli pdo_mysql
# 安裝 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# memcached
ENV MEMCACHED_DEPS="zlib-dev libmemcached-dev cyrus-sasl-dev"
RUN apk add --no-cache --update libmemcached-libs zlib
RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --no-cache --update --virtual .memcached-deps $MEMCACHED_DEPS \
    && pecl install memcached \
    && echo "extension=memcached.so" > /usr/local/etc/php/conf.d/20_memcached.ini \
    && rm -rf /usr/share/php7 \
    && rm -rf /tmp/* \
    && apk del .memcached-deps .phpize-deps
