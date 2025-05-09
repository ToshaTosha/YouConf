# Этап 1: Сборка фронтенда
FROM node:18 AS frontend-builder

WORKDIR /var/www

# 1. Копируем только package.json для кэширования слоя
COPY package.json .

# 2. Генерируем новый package-lock.json внутри контейнера
RUN npm install --package-lock-only

# 3. Устанавливаем зависимости с фиксом для rollup
RUN npm install --no-cache --legacy-peer-deps --force

# 4. Копируем остальные файлы фронтенда
COPY vite.config.js .
COPY resources ./resources

# 5. Сборка проекта
RUN npm run build

# Этап 2: Основной образ
FROM php:8.2-fpm

# Установка системных зависимостей
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
    && docker-php-ext-install gd pdo pdo_mysql zip bcmath exif

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www

# Копируем бэкенд (исключая node_modules)
COPY . .

# Копируем собранный фронтенд
COPY --from=frontend-builder /var/www/public/build /var/www/public/build

# Установка PHP зависимостей
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Настройка прав
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Запуск сервисов
CMD ["sh", "-c", "php-fpm && nginx -g 'daemon off;'"]

EXPOSE 80
