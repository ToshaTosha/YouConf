FROM php:8.1-fpm

# Установка необходимых расширений
RUN docker-php-ext-install pdo pdo_mysql

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
