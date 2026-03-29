FROM php:8.2-fpm

# System dependencies required by PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libssl-dev \
    pkg-config \
    zip \
    unzip \
    netcat-openbsd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Core PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    bcmath \
    gd \
    zip

# MongoDB PHP extension via PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy entrypoint and ensure it is executable before switching context
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
