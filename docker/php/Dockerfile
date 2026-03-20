FROM php:8.4-fpm-alpine


# Устанавливаем зависимости
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    zip \
    unzip \
    libzip-dev \
    librdkafka-dev \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install rdkafka \
    && docker-php-ext-enable rdkafka \
    && apk del autoconf g++ make
# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 9000
CMD ["php-fpm"]
