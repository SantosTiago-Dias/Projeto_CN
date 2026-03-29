FROM dunglas/frankenphp:1.10.1-php8.4 AS base

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions pdo_mysql gd intl zip opcache

RUN pecl install redis && docker-php-ext-enable redis


# Build stage
FROM base AS builder

WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Production stage
FROM base AS production

WORKDIR /app

ARG USER=appuser

COPY --from=builder /app ./

#Com base na documentação:https://frankenphp.dev/docs/docker/
RUN \
    # Use "adduser -D ${USER}" for alpine based distros
    useradd ${USER}; \
    # Add additional capability to bind to port 80 and 443
    setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
    # Give write access to /config/caddy and /data/caddy
    chown -R ${USER}:${USER} /config/caddy /data/caddy; \
    # Give Laravel write access to storage and cache
    chown -R ${USER}:${USER} /app/storage /app/bootstrap/cache; \
    chmod -R 775 /app/storage /app/bootstrap/cache

USER ${USER}
