# ---------- BUILD ----------
FROM composer:2 AS build

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

COPY . .

RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true


# ---------- RUNTIME ----------
FROM php:8.3-apache

WORKDIR /var/www/html

# extensões necessárias do Laravel
RUN docker-php-ext-install pdo pdo_mysql

# habilita rewrite (Laravel precisa)
RUN a2enmod rewrite

# copia projeto
COPY --from=build /app /var/www/html

# Apache aponta para /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80