FROM php:8.1-fpm

# Установка необходимых расширений
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip bcmath exif
    
# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www

# Копирование файлов приложения
COPY . .

# Установка зависимостей
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Копирование конфигурации Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf
