FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=8000
