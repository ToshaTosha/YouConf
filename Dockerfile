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
    && docker-php-ext-install gd zip bcmath exif

# Копирование Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копирование конфигурации Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# Установка рабочей директории
WORKDIR /var/www

# Копирование всех файлов приложения
COPY . .

# Установка зависимостей PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Установка Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Установка зависимостей Node.js
RUN npm install --no-cache

# Сборка Vue-приложения
RUN npm run build

# Генерация ключа приложения Laravel
RUN php artisan key:generate

# Запуск Nginx и PHP-FPM
CMD sh -c "php-fpm && nginx -g 'daemon off;'"