FROM php:8.2-fpm-alpine

ARG UID
ARG GID
ARG USER

ENV UID=${UID}
ENV GID=${GID}
ENV USER=${USER}

# Dialout group in alpine linux conflicts with MacOS staff group's gid, whis is 20. So we remove it.
RUN delgroup dialout

# Creating user and group
RUN addgroup -g ${GID} --system ${USER}
RUN adduser -G ${USER} --system -D -s /bin/sh -u ${UID} ${USER}

# Setup extensions
RUN apk add --no-cache \
      freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && apk add --no-cache linux-headers \
    && docker-php-ext-install sockets \
    && apk del --no-cache linux-headers \
    && apk del --no-cache \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && rm -rf /tmp/* && \
    apk add libzip-dev && \
    docker-php-ext-install mysqli pdo pdo_mysql zip bcmath exif && \
    apk add --no-cache pcre-dev $PHPIZE_DEPS \
        && pecl install redis \
        && docker-php-ext-enable redis.so && \
    apk add --update --no-cache libmemcached-dev && \
    pecl install memcached && docker-php-ext-enable memcached && \
    apk add --no-cache --virtual .build-deps \
        libxml2-dev \
        shadow \
        autoconf \
        g++ \
        make \
    && apk add --no-cache imagemagick-dev imagemagick \
    && pecl install imagick-beta \
    && apk del .build-deps && docker-php-ext-enable imagick \
    && apk --update --no-cache add build-base autoconf rabbitmq-c-dev \
    && pecl install amqp && docker-php-ext-enable amqp

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini && \
sed -i 's/session.save_handler = files/session.save_handler = memcached/g' $PHP_INI_DIR/php.ini && \
sed -i 's/;session.save_path = "\/tmp"/session.save_path = "memcached:11211"/g' $PHP_INI_DIR/php.ini

RUN sed -i "s/user = www-data/user = '${USER}'/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = '${USER}'/g" /usr/local/etc/php-fpm.d/www.conf


COPY --from=composer:2.2  /usr/bin/composer /usr/bin/composer
# Remove Cache
RUN rm -rf /var/cache/apk/*

WORKDIR /app
COPY ./src .
RUN /usr/bin/composer install

CMD ["php-fpm"]

EXPOSE 9000
