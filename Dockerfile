# ---------- BUILD ----------
FROM php:8.3-cli AS build

WORKDIR /app

# dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl

# composer usando mesma versão de PHP
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

# cria env temporário para evitar erro no artisan
RUN cp .env.example .env || true
RUN php artisan key:generate || true
RUN php artisan package:discover --ansi || true


# ---------- RUNTIME ----------
FROM php:8.3-apache

WORKDIR /var/www/html

# extensões PHP
RUN docker-php-ext-install pdo pdo_mysql

# apache modules
RUN a2enmod rewrite

# 🔥 FIX: evita erro "More than one MPM loaded"
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# copia aplicação
COPY --from=build /app /var/www/html

# permissões Laravel (ESSENCIAL)
RUN chown -R www-data:www-data \
    storage bootstrap/cache

# Apache serve pasta public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80