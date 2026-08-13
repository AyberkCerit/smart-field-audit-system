FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    unzip \
    git \
    curl

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd exif pcntl

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# --no-dev parametresi eklendi
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Build frontend assets
RUN npm ci && npm run build

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000

# Eski hali:
# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

# Yeni hali (Her deploy öncesi otomatik migration çalıştırır):
CMD php artisan migrate --seed --force && php artisan serve --host=0.0.0.0 --port=8000