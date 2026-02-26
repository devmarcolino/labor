# ---------- BUILD ----------
FROM composer:2 AS build

WORKDIR /app

# copia apenas dependências primeiro (cache do docker)
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# copia o restante do projeto
COPY . .

# evita erro caso não exista .env ainda
RUN cp .env.example .env || true

# gera key apenas se possível
RUN php artisan key:generate || true
RUN php artisan package:discover --ansi || true


# ---------- RUNTIME ----------
FROM php:8.3-apache

WORKDIR /var/www/html

# extensões necessárias para Laravel
RUN docker-php-ext-install pdo pdo_mysql

# habilita rewrite (OBRIGATÓRIO)
RUN a2enmod rewrite

# copia projeto buildado
COPY --from=build /app /var/www/html

# Apache aponta para /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80