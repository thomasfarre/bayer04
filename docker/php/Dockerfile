ARG PHP_MOD=fpm
ARG PHP_VERSION=8.1.3
FROM php:${PHP_VERSION}-${PHP_MOD}
WORKDIR "/php"

ARG UID=1000
ARG GID=1000

RUN usermod -u ${UID} www-data
RUN groupmod -g ${GID} www-data

RUN apt-get update && \
  apt-get install -y default-mysql-client \
  libxml2-dev \
  libpng-dev \
  libjpeg-dev \
  libfreetype6 \
  libfreetype6-dev \
  ssh \
  git \
  libzip-dev \
  libicu-dev \
  unzip

RUN docker-php-ext-configure gd --with-jpeg --with-freetype && \
  docker-php-ext-configure intl

RUN docker-php-ext-install soap && \
  docker-php-ext-install pdo_mysql && \
  docker-php-ext-install gd && \
  docker-php-ext-install zip && \
  docker-php-ext-install exif && \
  docker-php-ext-install intl

COPY php.ini /usr/local/etc/php/php.ini

# COMPOSER
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# PHPCS
RUN composer global require "squizlabs/php_codesniffer=*" && \
  ln -s /root/.config/composer/vendor/bin/phpcs /usr/local/bin/phpcs && \
  chmod +x /usr/local/bin/phpcs

# PHPSTAN
RUN composer global require phpstan/phpstan && \
  ln -s /root/.config/composer/vendor/bin/phpstan /usr/local/bin/phpstan && \
  chmod +x /usr/local/bin/phpstan

RUN chown -R www-data:www-data /var/www

EXPOSE 9000
USER www-data

WORKDIR /php
