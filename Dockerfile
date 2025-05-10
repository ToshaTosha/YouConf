FROM php:8.1-fpm

# Определяем переменные для пользователя
ARG uid=1000
ARG user=laravel

# Установка системных зависимостей и PHP расширений
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создание пользователя
RUN useradd -G www-data,root -u ${uid} -d /home/${user} ${user} \
    && mkdir -p /home/${user}/.composer \
    && chown -R ${user}:${user} /home/${user}

# Рабочая директория
WORKDIR /var/www

RUN ls -la /var/www

COPY . /var/www

RUN chown -R ${user}:${user} /var/www

USER ${user}