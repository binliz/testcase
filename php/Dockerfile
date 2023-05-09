FROM composer:2.2 as builder
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
WORKDIR /tmp/

COPY ./src/composer.* .

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist


FROM php:8.2-fpm-alpine

ARG UID
ARG GID
ARG USER

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV UID=${UID}
ENV GID=${GID}
ENV USER=${USER}

RUN delgroup dialout

# Creating user and group
RUN addgroup -g ${GID} --system ${USER}
RUN adduser -G ${USER} --system -D -s /bin/sh -u ${UID} ${USER}

# Setup extensions
RUN apk add libzip-dev && \
    docker-php-ext-install mysqli pdo pdo_mysql zip bcmath exif && \
    apk add --no-cache pcre-dev $PHPIZE_DEPS \
        && pecl install redis \
        && docker-php-ext-enable redis.so && \
    apk add --update --no-cache libmemcached-dev && \
    pecl install memcached && docker-php-ext-enable memcached && \

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini && \
sed -i 's/session.save_handler = files/session.save_handler = memcached/g' $PHP_INI_DIR/php.ini && \
sed -i 's/;session.save_path = "\/tmp"/session.save_path = "memcached:11211"/g' $PHP_INI_DIR/php.ini

RUN sed -i "s/user = www-data/user = '${USER}'/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = '${USER}'/g" /usr/local/etc/php-fpm.d/www.conf

WORKDIR /app

COPY ./src /app
COPY --from=builder /usr/bin/composer /usr/bin/composer
COPY --from=builder /tmp/vendor /app/vendor

RUN /usr/bin/composer dump-autoload

USER ${USER}

