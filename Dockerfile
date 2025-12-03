# Build do Laravel
FROM ghcr.io/composer/composer:2 AS build

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

COPY package.json package-lock.json* ./
RUN npm install

COPY . .
RUN npm run build

# Runtime usando FrankenPHP pelo GHCR (não usa Docker Hub!)
FROM ghcr.io/dunglas/frankenphp:1.1.0

WORKDIR /app

COPY --from=build /app ./

EXPOSE 8000

CMD ["php", "public/index.php"]
