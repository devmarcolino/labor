# ---------- BUILD ----------
FROM php:8.3-cli AS build

WORKDIR /app

# dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl

# instala composer usando mesma versão de PHP
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# cache das dependências
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# copia projeto
COPY . .

# evita erro artisan durante build
RUN cp .env.example .env || true
RUN php artisan key:generate || true
RUN php artisan package:discover --ansi || true


# ---------- RUNTIME ----------
FROM php:8.3-apache

WORKDIR /var/www/html

# extensões PHP
RUN docker-php-ext-install pdo pdo_mysql

# habilita rewrite (Laravel precisa)
RUN a2enmod rewrite

# ✅ FIX DEFINITIVO DO APACHE (remove TODOS MPMs)
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
    && rm -f /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork

# copia aplicação
COPY --from=build /app /var/www/html

# permissões Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Apache aponta para /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80