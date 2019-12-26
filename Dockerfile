FROM php:7.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git-core \
    unzip \
    libssl-dev \
    libzip-dev \
    libpq-dev \
    libevent-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) zip opcache pdo pdo_pgsql sockets pcntl bcmath \
    && pecl update-channels \
    && pecl install APCu event ds \
    && docker-php-ext-enable apcu event ds \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

## Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# RUN pecl install xdebug && docker-php-ext-enable xdebug && rm -rf /tmp/* /var/tmp/*

RUN useradd --create-home --uid 1000 --user-group --system app

ARG OPCACHE_DIR=/tmp/php-opcache
RUN mkdir ${OPCACHE_DIR} && chmod -R 777 ${OPCACHE_DIR}

# ARG MOD
# COPY ./build/local/${MOD} /usr/local/etc/php

## Install application:
WORKDIR /opt/app

USER app

ARG MOD
COPY --chown=app:app ./entrypoint.sh /home/app/entrypoint.sh

CMD /home/app/entrypoint.sh