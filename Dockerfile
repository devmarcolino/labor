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

# ✅ FIX MPM: Remove FORÇADAMENTE todos os MPMs e habilita só o prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_event.* \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.* \
    && rm -f /etc/apache2/mods-enabled/mpm_prefork.* \
    && ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf \
    && a2enmod rewrite

# copia aplicação
COPY --from=build /app /var/www/html

# copia configuração customizada do Apache
COPY docker-apache.conf /etc/apache2/sites-available/000-default.conf

# permissões Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Apache ServerName para evitar warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]