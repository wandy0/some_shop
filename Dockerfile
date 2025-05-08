FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql zip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --prefer-dist

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]