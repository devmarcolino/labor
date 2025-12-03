FROM dunglas/frankenphp:1.1-php8.1

# Instala extensões necessárias
RUN install-php-extensions pdo pdo_mysql mysqli intl opcache

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copia o projeto
COPY . .

# Instala dependências do Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Gera key caso não exista
RUN php artisan key:generate --force

# Permissões
RUN chmod -R 777 storage bootstrap/cache

# Build do Vite
RUN npm install && npm run build

# Expõe porta do FrankenPHP
EXPOSE 8000

# Config do frankenphp
COPY frankenphp.json /app/frankenphp.json

CMD ["frankenphp", "run", "--config", "/app/frankenphp.json"]
