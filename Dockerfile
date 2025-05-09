FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    ca-certificates

# Установка Node.js 18.x
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Очистка кеша
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка расширений PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www

COPY composer.json composer.lock ./

# Установка PHP зависимостей
RUN composer install --no-dev --optimize-autoloader
# Копирование проекта
COPY . .

# Установка PHP зависимостей
RUN composer install --no-dev --optimize-autoloader

# Удаление возможных конфликтующих файлов
RUN rm -f package-lock.json && rm -rf node_modules

# Установка и сборка фронтенда
RUN npm install --force && npm run build

# Настройка прав
RUN chown -R www-data:www-data /var/www/storage \
    && chown -R www-data:www-data /var/www/bootstrap/cache