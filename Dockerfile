FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    git curl pkg-config libssl-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/
WORKDIR /var/www/html

RUN composer install

EXPOSE 80
