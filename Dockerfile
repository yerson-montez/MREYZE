# Stage 1: Compilar assets frontend (Vite/Tailwind/Alpine) con Node
FROM node:22 AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

# Stage 2: Runtime PHP
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    tesseract-ocr \
    tesseract-ocr-spa \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer install --optimize-autoloader --no-interaction

CMD php artisan storage:link --force && (php artisan queue:work --queue=ocr,default --tries=3 --timeout=120 --sleep=3 &) && php artisan serve --host=0.0.0.0 --port=8080
