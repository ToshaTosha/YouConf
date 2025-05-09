# Этап 1: Сборка фронтенда (Vue)
FROM node:18 AS frontend-builder

WORKDIR /var/www

# Копируем только файлы, необходимые для установки зависимостей
COPY package*.json ./
COPY vite.config.js ./

# Чистая установка зависимостей (без кеша и с обходом багов npm)
RUN npm install --no-cache --legacy-peer-deps

# Копируем всё остальное и собираем фронтенд
COPY . .
RUN npm run build

# Этап 2: Основной образ с PHP и Nginx
FROM php:8.2-fpm

# Устанавливаем Nginx и системные зависимости
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

# Настраиваем Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# Рабочая директория
WORKDIR /var/www

# Копируем бэкенд (Laravel)
COPY . .

# Копируем собранный фронтенд из первого этапа
COPY --from=frontend-builder /var/www/public/build /var/www/public/build

# Устанавливаем зависимости PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Генерируем ключ приложения
RUN php artisan key:generate

# Настраиваем права
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Запуск Nginx и PHP-FPM
CMD sh -c "php-fpm && nginx -g 'daemon off;'"

# Открываем порты
EXPOSE 80