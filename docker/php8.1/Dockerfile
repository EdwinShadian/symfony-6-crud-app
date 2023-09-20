FROM php:8.1-fpm-alpine

RUN apk update && \
    apk add --no-cache \
        git \
        bash

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
      @composer \
      opcache \
      xdebug \
      pdo_pgsql \
      iconv \
      ctype

COPY ./docker/php8.1/php-fpm/php-fpm.conf /etc/php/8.1/fpm/php-fpm.conf
COPY ./docker/php8.1/php-fpm/php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/html
COPY . .

EXPOSE 9000

CMD ["php-fpm"]
