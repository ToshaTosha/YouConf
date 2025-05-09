# Этап 1: Сборка фронтенда (Vue) с фиксом для rollup
FROM node:18 AS frontend-builder

WORKDIR /var/www

# 1. Сначала копируем только package.json
COPY package.json package-lock.json* ./

# 2. Чистка кеша и установка зависимостей с явным указанием архитектуры
RUN npm ci --no-cache --force \
    && npm rebuild --arch=x64 --platform=linux --libc=glibc sharp

# 3. Копируем остальные файлы фронтенда
COPY vite.config.js ./
COPY resources ./resources

# 4. Сборка с явным указанием target
ENV NODE_ENV=production
RUN npm run build

# Этап 2: Основной образ с PHP и Nginx
FROM php:8.2-fpm

# Установка Nginx и зависимостей
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip bcmath exif pdo pdo_mysql

# Копируем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www

# Копируем только файлы Laravel (исключая node_modules)
COPY . .
COPY --from=frontend-builder /var/www/public/build /var/www/public/build

# Установка зависимостей PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Настройка прав
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Запуск сервисов
CMD ["sh", "-c", "php-fpm && nginx -g 'daemon off;'"]

EXPOSE 80