FROM php:8.4-rc-fpm

WORKDIR "/home/ffi/ffi.org"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# ------------------------------------------------------------------------------
# Install Dependencies
# ------------------------------------------------------------------------------

RUN apt-get update \
    && pecl channel-update pecl.php.net \
    && apt-get -y --no-install-recommends install \
      libzip-dev \
      libicu-dev \
      libpq-dev \
      libffi-dev \
      libsodium-dev \
      unzip \
      git \
      curl

# ------------------------------------------------------------------------------
# Install PHP
# ------------------------------------------------------------------------------

# 1. Composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

# 2. Extensions
RUN pecl install apcu && \
    docker-php-ext-enable apcu
RUN docker-php-ext-install intl && \
    docker-php-ext-enable intl
RUN docker-php-ext-install zip && \
    docker-php-ext-enable zip
RUN docker-php-ext-install opcache
RUN docker-php-ext-install sodium
RUN docker-php-ext-install sockets
RUN docker-php-ext-install ffi
RUN docker-php-ext-install pdo_pgsql && \
    docker-php-ext-enable pdo_pgsql
RUN docker-php-ext-install bcmath

# 3. Debug Extensions
# RUN pecl install xdebug && \
#     docker-php-ext-enable xdebug

# ------------------------------------------------------------------------------
# Cleanup Dependencies
# ------------------------------------------------------------------------------

RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && ln -sf /usr/share/zoneinfo/Etc/UTC /etc/localtime

# Composer cache permissions
RUN mkdir -p /home/ffi/.composer && \
    chmod +w /home/ffi/.composer -R && \
    chown 1000:1000 /home/ffi/.composer -R

RUN useradd -u 1000 -m ffi
USER ffi
