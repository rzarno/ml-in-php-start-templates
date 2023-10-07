FROM php:cli as builder
RUN apt-get update && apt-get install -y --no-install-recommends \
    unzip
ADD https://github.com/rindow/rindow-openblas/archive/refs/tags/0.3.0.zip /
RUN unzip 0.3.0.zip \
    && rm 0.3.0.zip
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt-get install -y --no-install-recommends \
        libopenblas-dev \
        liblapacke-dev \
    && cd rindow-openblas-0.3.0 \
    && composer update \
    && phpize \
    && ./configure --enable-rindow_openblas --with-php-config=php-config \
    && make \
    && make install \
    && cd .. \
    && rm -rf rindow-openblas-0.3.0
RUN apt-get install -y --no-install-recommends \
    libmagickwand-dev \
    && pecl install imagick
RUN apt-get install -y --no-install-recommends \
            libsqlite3-dev \
        && docker-php-ext-install pdo_sqlite
RUN apt-get install -y --no-install-recommends \
            zlib1g-dev libpng-dev \
        && docker-php-ext-install gd
RUN docker-php-ext-enable rindow_openblas imagick gd pdo_sqlite
ADD captcha-php /app
RUN cd /app \
    && composer install --no-dev --no-interaction --optimize-autoloader --no-suggest

FROM php:cli
WORKDIR /app
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
ARG TINI_VERSION=v0.19.0
ADD https://github.com/krallin/tini/releases/download/${TINI_VERSION}/tini /usr/local/bin/tini
RUN chmod +x /usr/local/bin/tini \
    && apt-get update && apt-get install -y --no-install-recommends \
        libopenblas-base \
        liblapacke \
        libmagickwand-6.q16-6 \
        libsqlite3-0 \
        libpng16-16 \
        zlib1g \
    && rm -rf /var/lib/apt/lists/* \
    && cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && sed \
      -e 's/memory_limit = .*/memory_limit = -1/' \
      -e 's/error_reporting = .*/error_reporting = E_ALL/' \
      -i /usr/local/etc/php/php.ini
COPY --from=builder /app /app

ENTRYPOINT ["tini", "--", "/app/bin/cli"]