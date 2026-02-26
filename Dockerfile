# ---------- BUILD ----------
FROM php:8.3-cli AS build

WORKDIR /app

# instala dependências necessárias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl

# instala composer manualmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# copia dependências primeiro (cache)
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# copia restante do projeto
COPY . .

RUN cp .env.example .env || true
RUN php artisan key:generate || true


# ---------- RUNTIME ----------
FROM php:8.3-apache

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

COPY --from=build /app /var/www/html

# Apache aponta para public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80