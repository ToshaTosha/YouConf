# Инструкция по развертыванию веб-приложения на сервере

## 1. Подготовка сервера

### 1.1. Обновление пакетов
Выполните следующие команды для обновления списка пакетов и установки обновлений:

```bash
sudo apt update
sudo apt upgrade -y
```

## 2. Установка и настройка веб-сервера Nginx

### 2.1. Установка Nginx
```bash
sudo apt install nginx -y
```

### 2.2. Запуск и настройка автозагрузки
```bash
sudo systemctl start nginx
sudo systemctl enable nginx
```

Для проверки работоспособности откройте в браузере адрес сервера. Должна отобразиться стартовая страница Nginx.

## 3. Установка PHP 8.3

### 3.1. Добавление репозитория
```bash
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

### 3.2. Установка PHP и необходимых модулей
```bash
sudo apt install php8.3-fpm php8.3-mbstring php8.3-xml php8.3-mysql php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd -y
```

## 4. Установка и настройка MySQL

### 4.1. Установка сервера MySQL
```bash
sudo apt install mysql-server -y
```

### 4.2. Создание базы данных и пользователя
Выполните вход в консоль MySQL:
```bash
sudo mysql -u root
```

В консоли MySQL выполните следующие команды (замените значения на свои):
```sql
CREATE DATABASE your_db_name;
CREATE USER 'your_db_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON your_db_name.* TO 'your_db_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 5. Установка Node.js

```bash
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install -y nodejs
```

## 6. Установка Composer

```bash
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

## 7. Развертывание приложения

### 7.1. Клонирование репозитория
```bash
cd /var/www
sudo git clone https://github.com/ToshaTosha/YouConf.git
```

### 7.2. Настройка прав доступа
```bash
sudo chown -R $USER:www-data /var/www/YouConf
sudo chmod -R 775 /var/www/YouConf/storage
```

## 8. Настройка окружения

### 8.1. Создание файла конфигурации
```bash
cd /var/www/YouConf
cp .env.example .env
```

### 8.2. Редактирование конфигурации
Отредактируйте следующие параметры в файле .env:
```ini
APP_URL=http://your_domain_or_ip
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=strong_password
```

## 9. Установка зависимостей

### 9.1. PHP зависимости
```bash
composer install --optimize-autoloader --no-dev
```

### 9.2. JavaScript зависимости
```bash
npm install
npm run build
```

## 10. Генерация ключа приложения

```bash
php artisan key:generate
```

## 11. Настройка Nginx

### 11.1. Создание конфигурационного файла
Создайте файл /etc/nginx/sites-available/your-domain.conf со следующим содержимым:

```nginx
server {
    listen 80;
    server_name your_domain_or_ip;
    root /var/www/YouConf/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 11.2. Активация конфигурации
```bash
sudo ln -s /etc/nginx/sites-available/your-domain.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 12. Запуск миграций базы данных

```bash
php artisan migrate --force
```

После выполнения всех шагов приложение будет доступно по указанному домену или IP-адресу.