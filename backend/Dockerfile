#build stage
FROM dunglas/frankenphp:1.10.1-php8.4

# 1. System dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

#PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    gd \
    intl \
    zip \
    opcache

WORKDIR /app

#composer setup
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#Copy the app
COPY . /app

#RUN composer install
RUN composer install --no-interaction --prefer-dist --no-dev

RUN chown -R www-data:www-data /app


# Build stage
FROM dunglas/frankenphp:1.10.1-php8.4 AS builder

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions pdo_mysql gd intl zip opcache

WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /app

RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader


# Production stage
FROM dunglas/frankenphp:1.10.1-php8.4 AS production

WORKDIR /app

COPY --from=builder /app /app

RUN chown -R www-data:www-data /app

